<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\OfficeUSA;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use \Exception;

class SettingController extends Controller
{
    public function update(Request $request, Setting $setting)
    {
        DB::beginTransaction();
        try {
            $setting->name = $request->name;
            $setting->value = $request->value;
            $setting->save();
            if ($request->name == 'state_package') {
                $places = json_decode(Setting::where('name', 'state_package')->get()->first()->value, true);
                foreach ($places as $place) {
                    if (count(OfficeUSA::where('name', $place)->get()) < 1) {
                        $newOffice = new OfficeUSA;
                        $newOffice->name = $place;
                        $newOffice->save();
                    }
                }
            }
            DB::commit();
            return Response::json(['message' => 'Ajuste actualizado exitosamente'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }
    public function get(Setting $setting)
    {
        return $setting;
    }
}
