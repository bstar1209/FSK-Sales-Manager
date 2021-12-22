<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\TableConfig;

class TableConfigController extends Controller
{
    public function table_config_edit(Request $request)
    {
        $table_name = TableConfig::$names[$request->type];
        if (isset($table_name)) {
            $table_info = TableConfig::where('table_name', '=', $table_name)->first();
            if (!isset($table_info))
                $table_info = new TableConfig;

            $table_info->user_id = Auth::id();
            $table_info->table_name = $table_name;
            $table_info->column_names = json_encode($request->names);
            $table_info->column_info = json_encode($request->width);
            $table_info->save();
            return 'success';
        }
        return 'false';
    }
}
