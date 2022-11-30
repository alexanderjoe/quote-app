<?php

namespace App\Http\Livewire;

use App\Models\Quote;
use Livewire\Component;

class SingleQuote extends Component
{
    public Quote $quote;

    public function render()
    {
        return view('livewire.single-quote', [
            'quote' => $this->quote,
        ]);
    }
}
