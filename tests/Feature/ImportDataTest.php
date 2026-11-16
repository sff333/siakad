<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ImportDataTest extends TestCase
{
    use RefreshDatabase;

    private function upload(string $jenis, string $csv)
    {
        return $this->from("/{$jenis}/import")->post("/{$jenis}/import", [
            'file' => UploadedFile::fake()->createWithContent('data.csv', $csv),
        ]);
    }

    public function test_import_routes_require_authentication(): void
    {
        foreach (['siswa', 'guru'] as $jenis) {
            $this->get("/{$jenis}/import")->assertRedirect('/login');
            $this->get("/{$jenis}/import/template")->assertRedirect('/login');
            $this->upload($jenis, "nama,jenis_kelamin\nTest,L")->assertRedirect('/login');
        }
    }

    public function test_forms_and_templates_are_available_for_both_types(): void
    {
        $this->actingAs(User::factory()->create());
        foreach (['siswa', 'guru'] as $jenis) {
            $this->get("/{$jenis}/import")->assertOk()->assertInertia(fn (Assert $page) => $page
                ->component('Import/Index')->where('jenis', $jenis)->has('columns')->has('importErrors', 0));
            $response = $this->get("/{$jenis}/import/template")->assertDownload("template-import-{$jenis}.csv");
            $this->assertStringStartsWith("\xEF\xBB\xBFsep=;\r\n", $response->streamedContent());
            $this->assertStringContainsString('Nama;', $response->streamedContent());
        }
    }

    public function test_excel_templates_with_column_titles_can_be_filled_and_imported(): void
    {
        $this->actingAs(User::factory()->create());
        foreach (['siswa', 'guru'] as $jenis) {
            $template = $this->get("/{$jenis}/import/template")->streamedContent();
            $lines = explode("\r\n", substr($template, 3));
            $headings = str_getcsv($lines[1], ';', '"', '');
            $this->assertContains('Jenis Kelamin', $headings);
            $values = array_map(fn ($heading) => match ($heading) {
                'NIS', 'NIP' => '001234',
                'Nama' => 'Data Dari Excel',
                'Jenis Kelamin' => 'P',
                default => '',
            }, $headings);
            $this->upload($jenis, $template.implode(';', $values)."\r\n")
                ->assertSessionHasNoErrors()->assertRedirect("/{$jenis}");
            $this->assertDatabaseHas($jenis, ['nama' => 'Data Dari Excel', 'jenis_kelamin' => 'P']);
        }
    }

    public function test_excel_titles_without_separator_directive_are_supported_after_saving(): void
    {
        $this->actingAs(User::factory()->create());
        $this->upload('siswa', "NIS,Nama,Jenis Kelamin\r\n00125,Sari,P\r\n")
            ->assertSessionHasNoErrors()->assertRedirect('/siswa');
        $this->assertDatabaseHas('siswa', ['nis' => '00125', 'nama' => 'Sari']);
        $this->upload('guru', "sep=;\r\n")->assertSessionHasErrors('file');
    }

    public function test_student_import_preserves_identifiers_and_assigns_active_year_membership(): void
    {
        $this->actingAs(User::factory()->create());
        TahunAjaran::create(['tahun_ajaran' => '2026/2027', 'semester' => 'Ganjil', 'is_active' => true]);
        $kelas = Kelas::create(['nama_kelas' => '1A', 'tingkat' => 1]);
        $this->upload('siswa', "\xEF\xBB\xBFnis,nisn,nama,jenis_kelamin,kelas_id,status,telepon_ortu\n00123,000456,Andi,L,{$kelas->id},,0812345678\n00124,,Sari,P,,,\n")
            ->assertSessionHasNoErrors()->assertRedirect('/siswa')->assertSessionHas('success', '2 data siswa berhasil diimpor!');
        $this->assertDatabaseHas('siswa', ['nis' => '00123', 'nisn' => '000456', 'telepon_ortu' => '0812345678', 'status' => 'aktif']);
        $this->assertDatabaseHas('anggota_rombel', ['siswa_id' => Siswa::where('nis', '00123')->first()->id, 'kelas_id' => $kelas->id, 'tahun_ajaran' => '2026/2027']);
    }

    public function test_teacher_import_handles_semicolons_quoted_text_and_inactive_status(): void
    {
        $this->actingAs(User::factory()->create());
        $this->upload('guru', "nip;nama;jenis_kelamin;status_aktif;alamat\n001;\"Ibu; Sari\";P;0;\"Jalan Mawar\nNomor 2\"\n;Budi;L;;\n")
            ->assertSessionHasNoErrors()->assertRedirect('/guru');
        $this->assertDatabaseHas('guru', ['nip' => '001', 'nama' => 'Ibu; Sari', 'status_aktif' => false, 'alamat' => "Jalan Mawar\nNomor 2"]);
        $this->assertDatabaseHas('guru', ['nama' => 'Budi', 'nip' => null, 'status_aktif' => true]);
    }

    public function test_invalid_row_cancels_entire_import_and_exposes_all_row_errors(): void
    {
        $this->actingAs(User::factory()->create());
        $response = $this->followingRedirects()->upload('siswa', "nis,nama,jenis_kelamin,tanggal_lahir,kelas_id\n001,Valid,L,,\n002,Salah,X,2020-02-31,99999\n003,,X,,\n")
            ->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->has('importErrors', 2)
            ->where('importErrors.0', fn ($value) => str_contains($value, 'Baris 3:'))
            ->where('importErrors.1', fn ($value) => str_contains($value, 'Baris 4:')));
        $this->assertDatabaseCount('siswa', 0);
    }

    public function test_existing_and_repeated_identifiers_are_rejected_without_writes(): void
    {
        $this->actingAs(User::factory()->create());
        Siswa::create(['nis' => '001', 'nama' => 'Asli', 'jenis_kelamin' => 'L']);
        Guru::create(['nip' => '001', 'nama' => 'Asli', 'jenis_kelamin' => 'L']);
        foreach (['siswa' => 'nis', 'guru' => 'nip'] as $jenis => $key) {
            $this->upload($jenis, "{$key},nama,jenis_kelamin\n002,Baru,P\n001,Duplikat,L\n")
                ->assertSessionHasErrors('file');
            $this->assertDatabaseCount($jenis, 1);
            $this->upload($jenis, "{$key},nama,jenis_kelamin\n002,Baru,P\n002,Duplikat,L\n")
                ->assertSessionHasErrors('file');
            $this->assertDatabaseCount($jenis, 1);
        }
    }

    public function test_empty_malformed_and_unexpected_headers_are_rejected(): void
    {
        $this->actingAs(User::factory()->create());
        foreach ([
            '',
            "nama,jenis_kelamin\n",
            "nama,jenis_kelamin\nSari,P,lebih\n",
            "nama,nama,jenis_kelamin\nSari,Sari,P\n",
            "nama,jenis_kelamin,user_id\nSari,P,1\n",
            "kolom_tidak_dikenal,email\nSari,sari@example.com\n",
        ] as $csv) {
            $this->upload('guru', $csv)->assertSessionHasErrors('file');
            $this->assertDatabaseCount('guru', 0);
        }
    }

    public function test_import_rejects_invalid_file_and_row_limit(): void
    {
        $this->actingAs(User::factory()->create());
        $this->post('/guru/import')->assertSessionHasErrors('file');
        $this->post('/guru/import', ['file' => UploadedFile::fake()->createWithContent('data.xlsx', "nama,jenis_kelamin\nSari,P")])->assertSessionHasErrors('file');
        $this->post('/guru/import', ['file' => UploadedFile::fake()->create('data.csv', 2049, 'text/csv')])->assertSessionHasErrors('file');
        $this->upload('guru', "nama,jenis_kelamin\n".str_repeat("Sari,P\n", 2001))->assertSessionHasErrors('file');
        $this->assertDatabaseCount('guru', 0);
    }

    public function test_students_can_be_imported_with_multiple_blank_or_missing_nis_values(): void
    {
        $this->actingAs(User::factory()->create());
        $this->upload('siswa', "NIS,Nama,Jenis Kelamin\n,Andi,\n,Sari,\n,,\n")
            ->assertSessionHasNoErrors()->assertRedirect('/siswa');
        $this->upload('siswa', "Nama\nBudi\n")
            ->assertSessionHasNoErrors()->assertRedirect('/siswa');
        $this->assertDatabaseCount('siswa', 3);
        foreach (['Andi', 'Sari', 'Budi'] as $nama) {
            $this->assertDatabaseHas('siswa', ['nama' => $nama, 'nis' => null, 'jenis_kelamin' => null, 'status' => 'aktif']);
        }
    }

    public function test_both_imports_allow_missing_name_and_gender_if_other_data_is_present(): void
    {
        $this->actingAs(User::factory()->create());
        foreach (['siswa', 'guru'] as $jenis) {
            $this->upload($jenis, "Alamat\nJalan Melati\nJalan Mawar\n\n")
                ->assertSessionHasNoErrors()->assertRedirect("/{$jenis}");
            $this->assertDatabaseCount($jenis, 2);
            $this->assertDatabaseHas($jenis, ['nama' => null, 'jenis_kelamin' => null, 'alamat' => 'Jalan Melati']);
            $this->get("/{$jenis}")->assertOk();
        }
    }

    public function test_manual_entry_still_requires_required_fields(): void
    {
        $this->actingAs(User::factory()->create());
        $this->post('/siswa', ['nama' => 'Andi', 'jenis_kelamin' => 'L', 'status' => 'aktif'])
            ->assertSessionHasErrors(['nis', 'nisn']);
        $this->post('/siswa', ['nis' => '001', 'nama' => 'Andi', 'jenis_kelamin' => 'L', 'status' => 'aktif', 'nisn' => ''])
            ->assertSessionHasErrors('nisn');
        $this->post('/guru', ['alamat' => 'Jalan Melati'])
            ->assertSessionHasErrors(['nama', 'jenis_kelamin']);
        $this->assertDatabaseCount('siswa', 0);
        $this->assertDatabaseCount('guru', 0);
    }
}
