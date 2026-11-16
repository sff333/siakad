<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use Inertia\Inertia;
use Inertia\Response;

class LogAktivitasController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('LogAktivitas/Index', [
            'logs' => LogAktivitas::query()->latest('id')->paginate(25),
        ]);
    }
}
