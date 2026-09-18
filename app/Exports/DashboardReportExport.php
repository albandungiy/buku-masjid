<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

/**
 * Renders reports.finance._excel_dashboard (balance summary + top categories/transactions
 * + daily averages, computed in InternalFinanceController::dashboardExcel() — the
 * dashboard itself is built from interactive Livewire widgets with no single underlying
 * table, so this is a purpose-built tabular equivalent, not a reused partial).
 */
class DashboardReportExport implements FromView, WithTitle
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('reports.finance._excel_dashboard', $this->data);
    }

    public function title(): string
    {
        return 'Dashboard';
    }
}
