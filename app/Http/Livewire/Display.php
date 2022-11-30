<?php

namespace App\Http\Livewire;

use App\Models\Quote;
use Livewire\Component;

class Display extends Component
{
    protected $listeners = ['refreshQuotes' => '$refresh'];

    public function render()
    {
        return view('livewire.display', [
            'quotes' => $this->getQuotes(),
        ]);
    }

    public function getQuotes()
    {
        return Quote::latest()->get();
    }
}
