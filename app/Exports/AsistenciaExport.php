<?php

namespace App\Exports;

use App\Models\Entrenamiento;
use App\Models\Jugador;
use App\Models\Asistencia;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class AsistenciaExport implements
    FromView,
    ShouldAutoSize,
    WithStyles,
    WithEvents

{
    protected $entrenamiento;

    public function __construct(Entrenamiento $entrenamiento)
    {
        $this->entrenamiento = $entrenamiento;
    }


        public function view(): View
{
    $categorias = $this->entrenamiento->categorias->pluck('id');

    $jugadores = Jugador::where('equipo_id', $this->entrenamiento->equipo_id)
        ->whereIn('categoria_id', $categorias)
        ->where('activo', 1)
        ->orderBy('apellidos')
        ->orderBy('nombres')
        ->get();

    return view('excel.asistencia', [
        'entrenamiento' => $this->entrenamiento,
        'jugadores' => $jugadores
    ]);
}
    

    public function headings(): array
    {
        return [
            'Jugador',
            'Categoría',
            'Asistencia',
            'Observación'
        ];
    }
    public function styles(Worksheet $sheet)
{
    // Título
    $sheet->mergeCells('A1:D1');

    $sheet->getStyle('A1')->applyFromArray([
        'font' => [
            'bold' => true,
            'size' => 16,
            'color' => ['rgb' => 'FFFFFF'],
        ],
        'fill' => [
            'fillType' => 'solid',
            'startColor' => ['rgb' => '1F4E78'],
        ],
        'alignment' => [
            'horizontal' => 'center',
        ],
    ]);

    // Encabezado de la tabla
    $sheet->getStyle('A6:D6')->applyFromArray([
        'font' => [
            'bold' => true,
            'color' => ['rgb' => 'FFFFFF'],
        ],
        'fill' => [
            'fillType' => 'solid',
            'startColor' => ['rgb' => '305496'],
        ],
    ]);

    $ultimaFila = $sheet->getHighestRow();

$sheet->getStyle("A6:D{$ultimaFila}")
    ->getBorders()
    ->getAllBorders()
    ->setBorderStyle(
        \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
    );
    
    return [];
}
public function registerEvents(): array
{
    return [

        AfterSheet::class => function (AfterSheet $event) {

            $sheet = $event->sheet;

            $sheet->setAutoFilter(
                $sheet->calculateWorksheetDimension()
            );

            $sheet->freezePane('A2');

        },

    ];
}
}