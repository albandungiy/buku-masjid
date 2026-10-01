<?php

namespace App\Http\Livewire\PublicDisplay;

use App\Models\RunningText as RunningTextModel;
use Livewire\Component;

class RunningText extends Component
{
    public $theme;
    public $texts = [];

    public function render()
    {
        $view = 'livewire.public_display.themes.default.running_text';
        if (view()->exists("livewire.public_display.themes.$this->theme.running_text")) {
            $view = "livewire.public_display.themes.$this->theme.running_text";
        }

        return view($view);
    }

    public function mount()
    {
        $this->texts = RunningTextModel::active()->pluck('content')->toArray();
    }
}
