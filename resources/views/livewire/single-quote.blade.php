<div class="bg-gray-600 rounded p-4 my-2">
    <h2 class="text-2xl mb-1">"{{ $quote->quote }}"</h2>
    <hr>
    <section class="mt-3">
        <h3 class="text-2xl">OpenAI GPT-3 Quote Representation:</h3>
        <p class="text-xl">{{ $quote->gpt }}</p>
    </section>
    <section class="mt-3">
        <h3 class="text-2xl mb-2">OpenAI DALL&middot;E&nbsp;2 Quote Representation:</h3>
        <img src="{{ $quote->getUrl() }}">
    </section>
</div>
