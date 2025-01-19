<!-- resources/views/stock-requests/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Stock Requests') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <a href="{{ route('stock-requests.create') }}"
                           class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600">
                            Create New Request
                        </a>
                    </div>

                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                        <tr>
                            <th class="px-6 py-3 text-left">Item</th>
                            <th class="px-6 py-3 text-left">Quantity</th>
                            <th class="px-6 py-3 text-left">Status</th>
                            <th class="px-6 py-3 text-left">Allocated</th>
                            <th class="px-6 py-3 text-left">Date</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                        @foreach($requests as $request)
                            <tr>
                                <td class="px-6 py-4">{{ $request->item->name }}</td>
                                <td class="px-6 py-4">{{ $request->quantity }}</td>
                                <td class="px-6 py-4">{{ $request->status }}</td>
                                <td class="px-6 py-4">{{ $request->allocated_quantity ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $request->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
