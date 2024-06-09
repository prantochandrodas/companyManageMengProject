<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LedgerExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $ledgerEntries;

    public function __construct($ledgerEntries)
    {
        $this->ledgerEntries = $ledgerEntries;
    }

    public function collection()
    {

        return collect($this->ledgerEntries)->map(function ($entry) {

            return [
                [
                    'Date' => $entry->date,
                    'Description' => $entry->description,
                    'Type' => $entry->type,
                    'Debit' => $entry->debit,
                    'Credit' => $entry->credit,
                    'Balance' => $entry->balance,
                ],
            ];
        });
    }

    public function headings(): array
    {
        return [
            ['Leadger Excel sheet'],
            ['Date', 'Description', 'Type', 'Debit', 'Credit', 'Balance'],
        ];
    }
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the headings row
            1 => [
                'font' => [
                    'bold' => true, // Make the text bold
                    'size' => 18, // Set the font size
                    // You can add more font properties here
                ],
                // You can add more styling options here, such as borders, alignment, etc.
            ],
            2 => [
                'font' => [
                    'bold' => true, // Make the text bold
                    'size' => 12, // Set the font size
                    // You can add more font properties here
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, // Set fill type to solid
                    'startColor' => [
                        'rgb' => 'C0C0C0', // Set the background color (light grey)
                    ],
                ],
                // You can add more styling options here, such as borders, alignment, etc.
            ]
        ];
    }

}
