@if ($lecturing)
    <div class="carousel-slide flex-shrink-0 w-full h-full flex flex-col items-center justify-center text-center p-6 rounded-2xl bg-gray-50">
        <div class="text-[1.5vw] leading-none font-semibold bm-txt-primary mb-4">{{ __('lecturing.friday_today_title') }}</div>
        <div class="text-lg text-gray-600 mb-4">{{ $lecturing->day_name }}, {{ $lecturing->full_date }} — {{ $lecturing->start_time }}</div>
        @if ($lecturing->title)
            <div class="text-2xl font-bold mb-6">{!! config('lecturing.emoji.title') !!} {{ $lecturing->title }}</div>
        @endif
        <div class="flex flex-col gap-2 text-xl">
            <div>{!! config('lecturing.emoji.lecturer') !!} {{ __('lecturing.friday_lecturer_name') }}: <span class="font-bold">{{ $lecturing->lecturer_name }}</span></div>
            @if ($lecturing->imam_name)
                <div>{!! config('lecturing.emoji.imam') !!} {{ __('lecturing.imam_name') }}: <span class="font-bold">{{ $lecturing->imam_name }}</span></div>
            @endif
            @if ($lecturing->muadzin_name)
                <div>{!! config('lecturing.emoji.muadzin') !!} {{ __('lecturing.muadzin_name') }}: <span class="font-bold">{{ $lecturing->muadzin_name }}</span></div>
            @endif
        </div>
    </div>
@endif
