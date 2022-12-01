<?php

namespace App\Http\Livewire;

use App\Models\Quote;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Add extends Component
{
    public string $quote = '';
    public string $author = '';

    public function render(): View
    {
        return view('livewire.add');
    }

    public function addQuote()
    {
        $this->validate([
            'quote' => 'required|min:3',
            'author' => 'sometimes',
        ]);

        try {
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
                'response_format' => 'b64_json',
            ])->json();

            Log::debug('Fetching image from OpenAI', $image);
            Log::debug($image['data'][0]['b64_json']);

            $image_path = 'public/images/' . $image['created'] . '.png';

            Storage::put($image_path, base64_decode($image['data'][0]['b64_json']));

            if (!is_null($request['choices'][0]['text'])) {
                Quote::create([
                    'quote' => $this->quote,
                    'gpt' => trim($request['choices'][0]['text']),
                    'image' => $image_path,
                    'author' => $this->author,
                ]);

                $this->dispatchBrowserEvent('alert', [
                    'type' => 'success',
                    'message' => 'Quote added successfully!',
                ]);
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $this->dispatchBrowserEvent('alert', [
                'type' => 'error',
                'message' => 'Something went wrong, please try again.',
            ]);
        }

        $this->quote = '';
        $this->author = '';

        // emit the event to refresh the display component
        $this->emit('refreshQuotes');
    }
}
