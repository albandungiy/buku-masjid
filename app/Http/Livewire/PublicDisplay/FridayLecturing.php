<?php

namespace App\Http\Livewire\PublicDisplay;

use App\Models\Lecturing;
use Livewire\Component;

class FridayLecturing extends Component
{
    public $theme;
    public $lecturing;

    public function mount()
    {
        if (!config('features.lecturings.is_active') || !today()->isFriday()) {
            return;
        }

        $this->lecturing = Lecturing::where('audience_code', Lecturing::AUDIENCE_FRIDAY)
            ->where('date', today()->format('Y-m-d'))
            ->first();
    }

    public function render()
    {
        $view = 'livewire.public_display.themes.default.friday_lecturing';
        if (view()->exists("livewire.public_display.themes.$this->theme.friday_lecturing")) {
            $view = "livewire.public_display.themes.$this->theme.friday_lecturing";
        }

        return view($view);
    }
}
