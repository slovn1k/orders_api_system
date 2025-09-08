<?php

namespace App\Http\Controllers;

use App\Events\OrderStatusChange;
use App\Models\Order;
use App\Models\Tag;
use Illuminate\Http\Request;
use App\Enums\OrderStatus;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query();

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('tags')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->whereIn('slug', $request->tags);
            });
        }

        if ($query->doesntExist()) {
            return 'No orders found';
        }

        return response()->json($query->with('tags', 'items')->get());
    }

    public function show(Request $request)
    {
        $order = Order::with('tags', 'items')
            ->where('order_number', $request->order_number)
            ->firstOrFail();

        return response()->json($order);
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'order_number' => 'required|string|unique:orders,order_number',
            'total_amount' => 'required|numeric',
            'tags' => 'array',
            'tags.*' => 'exists:tags,slug',
            'items' => 'array',
            'items.*.product_name' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        $order = Order::create([
            'order_number' => $validate['order_number'],
            'status' => OrderStatus::PENDING,
            'total_amount' => $validate['total_amount'],
        ]);

        if (!empty($validate['tags'])) {
            try {
                $tagIds = Tag::whereIn('slug', $validate['tags'])->pluck('id')->toArray();
                $order->tags()->sync($tagIds);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Failed to sync tags'], 500);
            }
        }

        if (!empty($validate['items'])) {
            $order->items()->createMany($validate['items']);
        }

        return response()->json($order->load('tags', 'items'));
    }

    public function update(Request $request, $order_number)
    {
        $order = Order::where('order_number', $order_number)->firstOrFail();

        $validate = $request->validate([
            'status' => 'in:pending,shipped,delivered,canceled',
            'tags' => 'array',
            'tags.*' => 'exists:tags,slug',
        ]);

        if (!empty($validate['status']) && $validate['status'] != $order->status) {
            $oldStatus = $order->status;
            $order->status = $validate['status'];
            event(new OrderStatusChange($order, $oldStatus));
        }

        if (!empty($validate['tags'])) {
            try {
                $tagIds = Tag::whereIn('slug', $validate['tags'])->pluck('id')->toArray();
                $order->tags()->sync($tagIds);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Failed to sync tags'], 500);
            }
        }

        $order->save();
        return response()->json($order->load('tags', 'items'));
    }
}
