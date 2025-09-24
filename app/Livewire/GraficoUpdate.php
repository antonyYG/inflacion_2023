<?php

namespace App\Livewire;

use Livewire\Component;

class GraficoUpdate extends Component
{
    public $actualizaciones = []; // Array dinámico de países y meses
    public $mensaje;
    public $graficoUrl;

    public function mount()
    {
        $this->graficoUrl = asset('storage/grafico_update.png') . '?t=' . time();
        // Inicializamos con un país y un mes vacío
        $this->actualizaciones[] = [
            'pais' => 'peru',
            'meses' => [
                ['mes' => '', 'inflacion' => '']
            ]
        ];
    }

    public function agregarPais()
    {
        $this->actualizaciones[] = [
            'pais' => 'peru',
            'meses' => [
                ['mes' => '', 'inflacion' => '']
            ]
        ];
    }

    public function eliminarPais($index)
    {
        unset($this->actualizaciones[$index]);
        $this->actualizaciones = array_values($this->actualizaciones);
    }

    public function agregarMes($indexPais)
    {
        $this->actualizaciones[$indexPais]['meses'][] = ['mes' => '', 'inflacion' => ''];
    }

    public function eliminarMes($indexPais, $indexMes)
    {
        unset($this->actualizaciones[$indexPais]['meses'][$indexMes]);
        $this->actualizaciones[$indexPais]['meses'] = array_values($this->actualizaciones[$indexPais]['meses']);
    }

    public function actualizarGrafico()
    {
        // Validar cada país y mes
        foreach ($this->actualizaciones as $paisData) {
            foreach ($paisData['meses'] as $mesData) {
                if (!is_numeric($mesData['mes']) || $mesData['mes'] < 1 || $mesData['mes'] > 12) {
                    $this->mensaje = "Mes inválido";
                    return;
                }
                if (!is_numeric($mesData['inflacion'])) {
                    $this->mensaje = "Inflación inválida";
                    return;
                }
            }
        }

        $tmpJson = storage_path('app/actualizaciones.json');
        file_put_contents($tmpJson, json_encode($this->actualizaciones, JSON_UNESCAPED_UNICODE));

        $script = public_path('scripts/graficos_param.py');
        $command = "python \"$script\" \"$tmpJson\" 2>&1";
        $output = shell_exec($command);

        $this->mensaje = "Gráfico actualizado correctamente";
        $this->graficoUrl = asset('storage/grafico_update.png') . '?t=' . time();
    }

    public function render()
    {
        return view('livewire.grafico-update');
    }
}
