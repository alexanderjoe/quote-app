<?php

namespace App\Http\Livewire;

use App\Models\Quote;
use Livewire\Component;

class Display extends Component
{
    public function render()
    {
        $quotes = Quote::latest()->get();

        return view('livewire.display', [
            'quotes' => $quotes,
        ]);
    }
}
