@if ($fundBooks->isNotEmpty())
<div class="col-lg-12">
    <div class="fs-4 pt-3 pb-3 d-flex align-items-center">
        <span class="fs-2 fw-bold pe-2">{{ __('ziswaf.homepage_summary_title') }}</span>
    </div>
    <div class="row align-items-end">
        @foreach ($fundBooks as $fundBook)
            <div class="col-lg col-6 ps-sm-0">
                <div class="card fw-bold p-3 mb-2 shadow-lg text-center">
                    <span class="badge bg-cyan-lt mb-2">{{ $fundBook->name }}</span><br>
                    <span class="date">{{ __('distribution.available_balance') }}</span>
                    <h1 class="pt-3 bm-txt-primary fw-bolder" style="font-size: 1.5rem;">
                        {{ config('money.currency_code') }} {{ format_number($fundBook->balance) }}
                    </h1>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif
