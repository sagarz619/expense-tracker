<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class TransactionsExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting, WithStyles
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function collection()
    {
        return $this->query->with(['category', 'account', 'toAccount'])->get();
    }

    public function headings(): array
    {
        return [
            'Date',
            'Type',
            'Category',
            'Account',
            'To Account',
            'Amount',
            'Description',
        ];
    }

    public function map($transaction): array
    {
        return [
            $transaction->date->format('Y-m-d'),
            ucfirst($transaction->type),
            $transaction->category->name ?? 'Transfer',
            $transaction->account->name,
            $transaction->toAccount->name ?? '-',
            $transaction->amount,
            $transaction->description ?? '-',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
