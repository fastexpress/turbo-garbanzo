<?php

namespace App\Http\Controllers;

use App\Models\OfficeGT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use \Exception;

class OfficeGTController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $show = $request->show;

        $office = $request->office;
        $from = $request->from;
        $to = $request->to;
        if ($office == "Todos") {
            return OfficeGT::whereBetween('date', [$from, $to])->paginate($show);
        } else {
            return OfficeGT::where('name', $office)->whereBetween('date', [$from, $to])->paginate($show);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $office = new OfficeGT();
            $office->name = $request->name;
            $office->date = $request->date;
            $office->value = $request->value;
            $office->save();
            DB::commit();
            return Response::json(['message' => 'Libras de la oficina ' . $request->name . ' guardada exitosamente'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OfficeGT  $officeGT
     * @return \Illuminate\Http\Response
     */
    public function show(OfficeGT $officeGT)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OfficeGT  $officeGT
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OfficeGT $officeGT)
    {
        DB::beginTransaction();
        try {
            $officeGT->name = $request->name;
            $officeGT->date = $request->date;
            $officeGT->value = $request->value;
            $officeGT->save();
            DB::commit();
            return Response::json(['message' => 'Libras de la oficina ' . $request->name . ' actualizadas exitosamente'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OfficeGT  $officeGT
     * @return \Illuminate\Http\Response
     */
    public function destroy(OfficeGT $officeGT)
    {
        DB::beginTransaction();
        try {
            $officeGT->delete();
            DB::commit();
            return Response::json(['message' => 'Libras eliminadas exitosamente'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }
}
