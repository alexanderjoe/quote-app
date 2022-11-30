<?php

namespace App\Http\Livewire;

use App\Models\Quote;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Add extends Component
{
    public $quote = '';

    public function render()
    {
        return view('livewire.add');
    }

    public function addQuote()
    {
        $this->validate([
            'quote' => 'required|min:3'
        ]);

        $request = Http::withToken(config('app.api_key'))->post('https://api.openai.com/v1/completions', [
            'model' => 'text-davinci-002',
            'prompt' => "Describe this quote as an image, \"{$this->quote}\"",
            'max_tokens' => 256,
            'temperature' => 0.7,
        ])->json();

        Log::debug('Fetching text generation from OpenAI', $request);
        Log::debug($request['choices'][0]['text']);

        $image = Http::withToken(config('app.api_key'))->post('https://api.openai.com/v1/images/generations', [
            'prompt' => $this->quote,
            'size' => '512x512',
            'n' => 1,
            'response_format' => 'url',
        ])->json();

        Log::debug('Fetching image from OpenAI', $image);
        Log::debug($image['data'][0]['url']);

        if(!is_null($request['choices'][0]['text'])) {
            Quote::create([
                'quote' => $this->quote,
                'gpt' => trim($request['choices'][0]['text']),
                'image_url' => $image['data'][0]['url'],
            ]);
        }

        $this->quote = '';
    }
}
