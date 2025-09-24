<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class GraficoController extends Controller
{
    public function index()
    {
        // Ejecuta el script de Python
       

        $python = 'C:\\Users\\alumno\\AppData\\Local\\Programs\\Python\\Python313\\python.exe';
        $script = public_path('scripts/graficos.py');

        $command = "\"$python\" \"$script\" 2>&1";

        exec($command, $output, $return_var);
            return view('grafico');
    }


}
