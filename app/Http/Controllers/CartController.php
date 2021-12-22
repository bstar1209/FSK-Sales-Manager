<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use App\Models\Parts;
use App\Models\CartLog;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function list(Request $request)
    {
        $cart_list = CartLog::with(['part']);

        if (Auth::check()) {
            CartLog::with(['part'])->where('user_id', $request->uuid)->update(['user_id' => Auth::user()->customer->id]);
            $cart_list = $cart_list->where('user_id', Auth::user()->customer->id);
        } else
            $cart_list = $cart_list->where('user_id', $request->uuid);
        return json_encode($cart_list->get());
    }

    public function update(Request $request)
    {
        $cart = CartLog::find($request->id);
        if (isset($cart)) {
            $cart->qty = $request->qty;
            $cart->save();

            $part = Parts::find($cart->product_id);
            $part->qty = $request->qty;
            $part->save();

            return json_encode(CartLog::with('part')->find($request->id));
        }
        return 'failed';
    }

    public function store(Request $request)
    {
        $cart = CartLog::with('part')
            ->where('user_id', '=', $request->customerId)
            ->whereHas('part', function ($query) use ($request) {
                $query->where('katashiki', '=', $request->modelNumber);
            })->first();

        if (isset($cart)) {
            $cart->qty += $request->qty;
            $cart->part->qty += $request->qty;
            $cart->save();
            $cart->part->save();
        } else {
            $part = new Parts;
            $part->katashiki = $request->modelNumber;
            $part->qty = $request->qty;
            $part->maker = $request->maker;
            $part->kubun2 = '国内';
            $part->save();

            $cart = new CartLog;
            $cart->product_id = $part->id;
            $cart->qty = $request->qty;
            $cart->user_id = $request->customerId;
            $cart->save();
        }

        return json_encode(CartLog::with(['part'])->find($cart->id));
    }

    public function destroy(Request $request)
    {
        $cart = CartLog::find($request->id);
        if (isset($cart)) {
            $cart->delete();
        }
        return true;
    }
}
