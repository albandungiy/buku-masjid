{{-- Plain HTML twin of reports/finance/*/detailed.blade.php, stripped of the filter
     form, buttons and layout chrome. --}}
@php
    $lastWeekDate = null;
@endphp
@foreach($groupedTransactions as $weekNumber => $weekTransactions)
    @php
        $lastWeekDate = $lastWeekDate ?: $lastMonthDate;
    @endphp
    <h3>{{ __('time.week') }} {{ $weekNumber + 1 }} ({{ $weekLabels[$weekNumber] }})</h3>
    @include('reports.finance._internal_content_detailed')
    @php
        $lastWeekDate = Carbon\Carbon::parse($weekTransactions->last()->last()->date);
    @endphp
@endforeach
