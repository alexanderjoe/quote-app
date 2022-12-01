<div class="mt-4 text-white pb-4">
    <h1 class="text-2xl">Previous Quotes</h1>
    @forelse($quotes as $quote)
        <livewire:single-quote :quote="$quote" :key="$quote->id" />
    @empty
        <div class="bg-gray-600 rounded p-4 my-2">
            <h2 class="text-2xl mb-1">No quotes yet!</h2>
        </div>
    @endforelse

    {{ $quotes->links() }}
</div>
