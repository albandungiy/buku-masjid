<div class="w-full p-3 lg:p-0">
    <div class="lg:flex items-center">
        <div class="lg:w-1/5 font-bold text-center mb-3 lg:mb-0 border-r border-1 border-[#c0c4c5]">
            <h1 class="text-[1.5vw] leading-none font-semibold bm-txt-primary">Info Terkini</h1>
        </div>
        <div class="slider lg:w-4/5">
            <div class="slide-track">
                @if (count($texts))
                    @foreach ([1, 2] as $loop)
                        <div class="flex min-w-[100px] me-8 align-center items-center"></div>
                        @foreach ($texts as $text)
                            <div class="flex items-center align-center min-w-[400px] me-8">
                                <h1 class="bm-txt-primary font-bold text-2xl whitespace-nowrap">{{ $text }}</h1>
                            </div>
                        @endforeach
                    @endforeach
                @else
                    <div class="flex items-center align-center min-w-[400px] me-8">
                        <h1 class="bm-txt-primary font-bold text-2xl whitespace-nowrap">{{ Setting::get('masjid_name', config('masjid.name')) }}</h1>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
