<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\StockRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ShopController extends Controller
{
    public function dashboard(): View
    {
        $items = Item::where('quantity', '>', 0)->get();
        $requests = auth()->user()->stockRequests()->with('item')->latest()->get();

        return view('shop.dashboard', compact('items', 'requests'));
    }

    public function requestStock(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1'
        ]);

        auth()->user()->stockRequests()->create($validated);

        return redirect()->route('dashboard')->with('success', 'Stock request submitted successfully');
    }
}
