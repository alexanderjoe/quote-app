<?php

namespace App\Http\Livewire;

use App\Models\Quote;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use OpenAI;

class Add extends Component
{
    public string $quote = '';
    public string $author = '';

    public function render(): View
    {
        return view('livewire.add');
    }

    public function addQuote(): void
    {
        $this->validate([
            'quote' => 'required|min:3',
            'author' => 'sometimes',
        ]);

        $client = OpenAI::client(config('app.api_key'));

        try {
            Log::debug('Fetching text generation from OpenAI');

            $text_resp = $client->chat()->create([
                'model' => 'gpt-4o',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => "Describe the following quote as if it were an image, this output will be used to generate an image representation of the quote. Do not repeat the quote in your description. Keep the message relatively short. Quote: \"{$this->quote}\""
                    ],
                ],
            ]);
            $text_result = trim($text_resp['choices'][0]['message']['content']);
//            dd($text_result);
            Log::debug("Text fetched from OpenAI {$text_resp->created}");

//            $text_resp = $client->completions()->create([
//                'model' => 'gpt-3.5-turbo-instruct',
//                'prompt' => "Describe the following quote as if it were an image, this output will be used to generate an image representation of the quote. Do not repeat the quote in your description. Quote: \"{$this->quote}\"",
//                'max_tokens' => 256,
//                'temperature' => 0.7,
//            ]);
//            $text_result = trim($text_resp['choices'][0]['text']);
//            Log::debug("Text fetched from OpenAI {$text_resp->created}");


            Log::debug('Fetching image from OpenAI...');
            $image_resp = $client->images()->create([
                'model' => 'dall-e-3',
                'prompt' => $text_result,
                'size' => '1024x1024',
                'n' => 1,
                'response_format' => 'b64_json',
            ]);
            Log::debug("Image fetched from OpenAI {$image_resp->created}");

            $image_path = 'public/images/'.$image_resp->created.'.png';

            Storage::put($image_path, base64_decode($image_resp['data'][0]['b64_json']));

            if (!empty($text_result) && !empty($image_path)) {
                Quote::create([
                    'quote' => $this->quote,
                    'gpt' => $text_result,
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
