<?php

namespace App\Http\Controllers;

use App\Models\OfficeUSA;
use App\Models\Setting;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use \Exception;
use Image;

class OfficeUSAController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $places = json_decode(Setting::where('name', 'state_package')->get()->first()->value, true);
        return OfficeUSA::whereIn('name', $places)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OfficeUSA  $officeUSA
     * @return \Illuminate\Http\Response
     */
    public function show(OfficeUSA $officeUSA)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OfficeUSA  $officeUSA
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        $pathImages = public_path() . '/places/';
        try {
            $officeUSA = OfficeUSA::find($id);
            $officeUSA->name = $request->name;
            $officeUSA->address = $request->address;
            $officeUSA->addressManifest = $request->addressManifest;

            if (!empty($geolocation)) {
                $officeUSA->geolocation = $request->geolocation;
            }
            if ($request->hasFile('image')) {
                $image = Image::make($request->file('image'));
                $extension = $request->file('image')->getClientOriginalExtension();
                $name =  $request->name . '.' . $extension;
                $image->save($pathImages . $name);
                $officeUSA->img = $name;
            }
            $officeUSA->save();
            DB::commit();
            return Response::json(['message' => 'Oficina actualizada exitosamente'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OfficeUSA  $officeUSA
     * @return \Illuminate\Http\Response
     */
    public function destroy(OfficeUSA $officeUSA)
    {
        //
    }
}
