{{-- Purpose-built tabular equivalent of the interactive dashboard (Livewire balance
     widget, top-category/top-transaction/daily-averages components) — see
     InternalFinanceController::dashboardExcel() and getTopCategories()/getTopTransactions()/
     getDailyAverages() for how each block below is computed. --}}
<h2>{{ __('transaction.balance') }} ({{ $startDate->isoFormat('D MMM Y') }} - {{ $endDate->isoFormat('D MMM Y') }})</h2>
<table>
    <tbody>
        <tr><th>{{ __('transaction.start_balance') }}</th><td>{{ format_number($startBalance) }}</td></tr>
        <tr><th>{{ __('transaction.income') }}</th><td>{{ format_number($incomeTotal) }}</td></tr>
        <tr><th>{{ __('transaction.spending') }}</th><td>{{ format_number($spendingTotal) }}</td></tr>
        <tr><th>{{ __('transaction.end_balance') }}</th><td>{{ format_number($endBalance) }}</td></tr>
    </tbody>
</table>

<h2>{{ __('dashboard.top_income_category') }}</h2>
<table>
    <thead><tr><th>{{ __('category.category') }}</th><th>{{ __('transaction.amount') }}</th></tr></thead>
    <tbody>
        @forelse ($topIncomeCategories as $category)
            <tr><td>{{ $category->name }}</td><td>{{ format_number($category->transactions_sum_amount ?? 0) }}</td></tr>
        @empty
            <tr><td colspan="2">-</td></tr>
        @endforelse
    </tbody>
</table>

<h2>{{ __('dashboard.top_spending_category') }}</h2>
<table>
    <thead><tr><th>{{ __('category.category') }}</th><th>{{ __('transaction.amount') }}</th></tr></thead>
    <tbody>
        @forelse ($topSpendingCategories as $category)
            <tr><td>{{ $category->name }}</td><td>{{ format_number($category->transactions_sum_amount ?? 0) }}</td></tr>
        @empty
            <tr><td colspan="2">-</td></tr>
        @endforelse
    </tbody>
</table>

<h2>{{ __('dashboard.top_income') }}</h2>
<table>
    <thead><tr><th>{{ __('app.date') }}</th><th>{{ __('app.description') }}</th><th>{{ __('transaction.amount') }}</th></tr></thead>
    <tbody>
        @forelse ($topIncomeTransactions as $transaction)
            <tr>
                <td>{{ Carbon\Carbon::parse($transaction->date)->isoFormat('DD MMM YYYY') }}</td>
                <td>{{ $transaction->description }}</td>
                <td>{{ format_number($transaction->amount) }}</td>
            </tr>
        @empty
            <tr><td colspan="3">-</td></tr>
        @endforelse
    </tbody>
</table>

<h2>{{ __('dashboard.top_spending') }}</h2>
<table>
    <thead><tr><th>{{ __('app.date') }}</th><th>{{ __('app.description') }}</th><th>{{ __('transaction.amount') }}</th></tr></thead>
    <tbody>
        @forelse ($topSpendingTransactions as $transaction)
            <tr>
                <td>{{ Carbon\Carbon::parse($transaction->date)->isoFormat('DD MMM YYYY') }}</td>
                <td>{{ $transaction->description }}</td>
                <td>{{ format_number($transaction->amount) }}</td>
            </tr>
        @empty
            <tr><td colspan="3">-</td></tr>
        @endforelse
    </tbody>
</table>

<h2>{{ __('dashboard.daily_averages') }}</h2>
<table>
    <thead><tr><th>{{ __('app.description') }}</th><th>{{ __('transaction.amount') }}</th></tr></thead>
    <tbody>
        @forelse ($dailyAverages as $average)
            <tr><td>{{ $average->description }}</td><td>{{ format_number($average->average) }}</td></tr>
        @empty
            <tr><td colspan="2">-</td></tr>
        @endforelse
    </tbody>
</table>
