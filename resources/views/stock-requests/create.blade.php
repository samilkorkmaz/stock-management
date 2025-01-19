<!-- resources/views/stock-requests/create.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Create Stock Request') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('stock-requests.store') }}">
                        @csrf
                        <div class="mb-4">
                            <label for="item_id" class="block mb-2">Item</label>
                            <select name="item_id" id="item_id" class="w-full rounded-md border-gray-300">
                                @foreach($items as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }} (Available: {{ $item->quantity }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="quantity" class="block mb-2">Quantity</label>
                            <input type="number" name="quantity" id="quantity"
                                   class="w-full rounded-md border-gray-300" min="1">
                        </div>

                        <button type="submit"
                                class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600">
                            Submit Request
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
