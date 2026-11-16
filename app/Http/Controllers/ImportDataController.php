<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Support\ImportRules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class ImportDataController extends Controller
{
    public function create(Request $request, string $jenis)
    {
        return Inertia::render('Import/Index', [
            'jenis' => $jenis,
            'columns' => array_keys(ImportRules::for($jenis)),
            'columnLabels' => ImportRules::headings($jenis),
            'importErrors' => $request->session()->get('errors')?->getBag('default')->get('file') ?? [],
            'kelasList' => $jenis === 'siswa' ? Kelas::orderBy('nama_kelas')->get(['id', 'nama_kelas']) : [],
        ]);
    }

    public function template(string $jenis)
    {
        return response()->streamDownload(function () use ($jenis) {
            $stream = fopen('php://output', 'w');
            fwrite($stream, "\xEF\xBB\xBF");
            fwrite($stream, "sep=;\r\n");
            fputcsv($stream, array_values(ImportRules::headings($jenis)), ';', '"', '', "\r\n");
            fclose($stream);
        }, "template-import-{$jenis}.csv", ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function store(Request $request, string $jenis)
    {
        $request->validate(['file' => ['required', 'file', 'extensions:csv', 'mimes:csv,txt', 'max:2048']], [
            'file.required' => 'Pilih file CSV terlebih dahulu.',
            'file.file' => 'File tidak dapat dibaca. Unggah kembali file CSV.',
            'file.uploaded' => 'File gagal diunggah. Maksimal ukuran file 2 MB.',
            'file.extensions' => 'Gunakan file dengan ekstensi .csv.',
            'file.mimes' => 'Format file harus CSV UTF-8.',
            'file.max' => 'Ukuran file maksimal 2 MB.',
        ]);
        $stream = fopen($request->file('file')->getRealPath(), 'r');

        try {
            $firstLine = fgets($stream);
            if ($firstLine === false || ! mb_check_encoding($firstLine, 'UTF-8')) {
                $this->invalid('File kosong atau bukan CSV UTF-8. Gunakan template yang tersedia.');
            }
            $firstLine = preg_replace('/^\xEF\xBB\xBF/', '', $firstLine);
            $line = 1;
            if (preg_match('/^sep=([;,])\s*$/i', $firstLine, $matches)) {
                $delimiter = $matches[1];
                $firstLine = fgets($stream);
                $line++;
                if ($firstLine === false || ! mb_check_encoding($firstLine, 'UTF-8')) {
                    $this->invalid('Judul kolom tidak ditemukan. Gunakan template yang tersedia.');
                }
            } else {
                $delimiter = count(str_getcsv($firstLine, ';', '"', '')) > count(str_getcsv($firstLine, ',', '"', '')) ? ';' : ',';
            }
            $headers = array_map(fn ($value) => preg_replace('/\s+/', '_', strtolower(trim($value ?? ''))), str_getcsv($firstLine, $delimiter, '"', ''));
            $rules = ImportRules::for($jenis);
            if (count(array_unique($headers)) !== count($headers) || array_diff($headers, array_keys($rules))) {
                $this->invalid('Header CSV tidak sesuai atau berulang. Gunakan minimal satu nama kolom dari template. Semua kolom data boleh dikosongkan atau dihapus.');
            }

            $rows = [];
            $seen = [];
            $errors = [];
            $identity = $jenis === 'siswa' ? 'nis' : 'nip';
            $model = $jenis === 'siswa' ? Siswa::class : Guru::class;
            while (($values = fgetcsv($stream, 0, $delimiter, '"', '')) !== false) {
                $line++;
                if (count(array_filter($values, fn ($value) => trim($value ?? '') !== '')) === 0) {
                    continue;
                }
                if (count($rows) >= 2000) {
                    $this->invalid('Maksimal 2.000 baris data dalam satu impor.');
                }
                if (! mb_check_encoding($values, 'UTF-8')) {
                    $errors[] = "Baris {$line}: teks harus menggunakan encoding UTF-8.";
                } elseif (count($values) !== count($headers)) {
                    $errors[] = "Baris {$line}: jumlah kolom tidak sesuai header.";
                } else {
                    $data = array_combine($headers, array_map(fn ($value) => trim($value ?? '') === '' ? null : trim($value), $values));
                    if ($jenis === 'siswa') {
                        $data['status'] ??= 'aktif';
                        $data['kewarganegaraan'] ??= 'WNI';
                    } else {
                        $data['status_aktif'] ??= '1';
                    }
                    $validator = Validator::make($data, $rules, [
                        'required' => ':attribute wajib diisi.',
                        'string' => ':attribute harus berupa teks.',
                        'unique' => ':attribute sudah terdaftar.',
                        'max.string' => ':attribute maksimal :max karakter.',
                        'max.numeric' => ':attribute maksimal :max.',
                        'min.numeric' => ':attribute minimal :min.',
                        'integer' => ':attribute harus berupa bilangan bulat.',
                        'in' => ':attribute berisi pilihan yang tidak valid.',
                        'date_format' => ':attribute harus berupa tanggal valid dengan format YYYY-MM-DD.',
                        'exists' => ':attribute tidak ditemukan.',
                        'email' => ':attribute harus berupa alamat email valid.',
                        'boolean' => ':attribute harus diisi 1 (aktif) atau 0 (nonaktif).',
                    ]);
                    $invalid = $validator->fails();
                    if ($invalid) {
                        $errors[] = "Baris {$line}: ".implode(' ', $validator->errors()->all());
                    }
                    $key = mb_strtolower($data[$identity] ?? '');
                    if ($key !== '' && isset($seen[$key])) {
                        $errors[] = "Baris {$line}: {$identity} berulang dalam file.";
                    }
                    $seen[$key] = true;
                    $rows[] = $invalid ? [] : $validator->validated();
                }
                if (count($errors) >= 20) {
                    break;
                }
            }
            if ($errors) {
                throw ValidationException::withMessages(['file' => array_slice($errors, 0, 20)]);
            }
            if (! $rows) {
                $this->invalid('File belum berisi data. Isi minimal satu baris di bawah header.');
            }

            DB::transaction(function () use ($rows, $model) {
                foreach ($rows as $row) {
                    $model::create($row);
                }
            });
        } finally {
            fclose($stream);
        }

        return redirect()->route("{$jenis}.index")->with('success', count($rows)." data {$jenis} berhasil diimpor!");
    }

    private function invalid(string $message): never
    {
        throw ValidationException::withMessages(['file' => $message]);
    }
}
