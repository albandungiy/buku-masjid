<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

/**
 * Renders the exact same table as reports.finance._internal_content_summary (the web
 * & PDF summary report) into an .xlsx sheet — maatwebsite/excel's FromView concern
 * parses the rendered HTML <table> directly into spreadsheet cells, so the numbers
 * always match what's shown on screen without duplicating the report's data logic.
 */
class SummaryReportExport implements FromView, WithTitle
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('reports.finance._internal_content_summary', $this->data);
    }

    public function title(): string
    {
        // Excel sheet titles are capped at 31 chars and can't contain : \ / ? * [ ] —
        // keep this literal rather than reusing a report.* lang string that might not.
        return 'Ringkasan';
    }
}
