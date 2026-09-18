<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

/**
 * Renders reports.finance._excel_categorized (a plain-HTML twin of the periode-specific
 * categorized.blade.php, one <table> per category) into an .xlsx sheet.
 */
class CategorizedReportExport implements FromView, WithTitle
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('reports.finance._excel_categorized', $this->data);
    }

    public function title(): string
    {
        return 'Per Kategori';
    }
}
