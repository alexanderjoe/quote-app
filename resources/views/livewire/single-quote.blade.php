<div class="bg-gray-600 rounded p-4 mb-4 shadow">
    <h2 class="text-2xl mb-3">"{{ $quote->quote }}"{{ $quote->author ? ' ~ ' . $quote->author : '' }}</h2>
    <hr>
    <div class="grid grid-cols-1 lg:grid-cols-2">
        <section class="mt-3">
            <h3 class="text-2xl">OpenAI GPT-4o Quote Representation:</h3>
            <p class="text-xl">{{ $quote->gpt }}</p>
        </section>
        <section class="mt-3">
            <h3 class="text-2xl mb-2">OpenAI DALL&middot;E&nbsp;3 Quote Representation:</h3>
            <img src="{{ $quote->getUrl() }}" class="shadow w-2/3">
        </section>
    </div>
</div>
