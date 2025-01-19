<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\StockRequest;
use Illuminate\Http\Request;

class StockRequestController extends Controller
{
    public function index()
    {
        $requests = auth()->user()->stockRequests()->with('item')->latest()->get();
        return view('stock-requests.index', compact('requests'));
    }

    public function create()
    {
        $items = Item::all();
        return view('stock-requests.create', compact('items'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
        ]);

        auth()->user()->stockRequests()->create($validated);

        return redirect()->route('stock-requests.index')
            ->with('success', 'Stock request created successfully.');
    }
}
