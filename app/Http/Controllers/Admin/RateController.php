<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Admin\RateReqest;
use App\Models\Rate;
use App\Models\RateLog;

class RateController extends Controller
{
    /**
     * Store a rate in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RateReqest $request)
    {
        // Store rate data
        if ($request->action === 'create') {
            $rate = new Rate;
            $rate->type_money = $request->type_money;
        } else {
            $rate = Rate::where('type_money', $request->type_money)->first();
        }
        $rate->buy_rate = $request->buy_rate;
        $rate->sale_rate = $request->sale_rate;
        $rate->save();

        // Store rate log data
        $log = new RateLog;
        $log->type_money = $request->type_money;
        $log->buy_rate = $request->buy_rate;
        $log->sale_rate = $request->sale_rate;
        $log->save();

        return json_encode([
            'result' => true,
            'updated_at' => $rate->updated_at->format('Y-m-d')
        ]);
    }

    public function log(Request $request)
    {
        $logs = RateLog::where('type_money', $request->type_money)
            ->orderBy('created_at', 'desc')
            ->get();
        return json_encode($logs);
    }

    public function delete(Request $request)
    {
        $rate = Rate::where('type_money', $request->type_money)->first();
        if ($rate) {
            $rate->delete();
            return json_encode([
                'result' => true
            ]);
        }
        RateLog::where('type_money', $request->type_money)->delete();
        return json_encode([
            'result' => false
        ]);
    }

    public function get_list(Request $request)
    {
        $rate_list = Rate::get()->keyBy('type_money')->all();
        return json_encode($rate_list);
    }
}
