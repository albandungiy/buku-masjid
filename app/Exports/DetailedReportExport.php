<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

/**
 * Renders reports.finance._excel_detailed (a plain-HTML twin of the periode-specific
 * detailed.blade.php, one <table> per week) into an .xlsx sheet.
 */
class DetailedReportExport implements FromView, WithTitle
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('reports.finance._excel_detailed', $this->data);
    }

    public function title(): string
    {
        return 'Rincian Mingguan';
    }
}
