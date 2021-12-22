<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Tax;
use App\Models\TaxLog;

class TaxController extends Controller
{
    public function update(Request $request)
    {
        $tax = Tax::first();
        if (!$tax) {
            $tax = new Tax;
        }
        $tax->tax = $request->tax;
        $tax->save();

        $log = new TaxLog;
        $log->tax = $request->tax;
        $log->save();

        return json_encode([
            'success' => true,
            'updated_at' => $tax->updated_at->format('Y-m-d')
        ]);
    }
}
