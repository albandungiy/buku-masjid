{{-- Plain HTML twin of reports/finance/*/categorized.blade.php, stripped of the filter
     form, buttons and layout chrome — Maatwebsite\Excel's FromView concern parses this
     into spreadsheet rows/tables, so only the report content itself belongs here. --}}
<h2>{{ __('transaction.income') }}</h2>

@if ($groupedTransactions->has(1) && !$groupedTransactions[1]->where('category_id', null)->isEmpty())
    <h4>~{{ __('transaction.no_category') }}~</h4>
    @include('reports.finance._internal_content_categorized', [
        'hasGroupedTransactions' => $groupedTransactions->has(1),
        'transactions' => $groupedTransactions[1]->where('category_id', null),
        'categoryName' => __('transaction.no_category'),
    ])
@endif

@foreach($incomeCategories->sortBy('id')->values() as $key => $incomeCategory)
    <h4>{{ $incomeCategory->name }}</h4>
    @include('reports.finance._internal_content_categorized', [
        'hasGroupedTransactions' => $groupedTransactions->has(1),
        'transactions' => $groupedTransactions[1]->where('category_id', $incomeCategory->id),
        'categoryName' => $incomeCategory->name,
    ])
@endforeach

<h2>{{ __('transaction.spending') }}</h2>

@if ($groupedTransactions->has(0) && !$groupedTransactions[0]->where('category_id', null)->isEmpty())
    <h4>~{{ __('transaction.no_category') }}~</h4>
    @include('reports.finance._internal_content_categorized', [
        'hasGroupedTransactions' => $groupedTransactions->has(0),
        'transactions' => $groupedTransactions[0]->where('category_id', null),
        'categoryName' => __('transaction.no_category'),
    ])
@endif

@foreach($spendingCategories->sortBy('id')->values() as $key => $spendingCategory)
    <h4>{{ $spendingCategory->name }}</h4>
    @include('reports.finance._internal_content_categorized', [
        'hasGroupedTransactions' => $groupedTransactions->has(0),
        'transactions' => $groupedTransactions[0]->where('category_id', $spendingCategory->id),
        'categoryName' => $spendingCategory->name,
    ])
@endforeach
