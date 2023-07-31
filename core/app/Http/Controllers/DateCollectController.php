<?php

namespace App\Http\Controllers;

use App\Models\DateCollect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use \Exception;
use Carbon\Carbon;

class DateCollectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $show = $request->show;
        if ($search == '') {
            return DateCollect::orderBy('id', 'desc')
                ->where('status', true)
                ->paginate($show);
        } else {
            $search = explode(" ", $request->search);
            return DateCollect::where(function ($query) use ($search) {
                foreach ($search as $item) {
                    $query->where('id', 'like', '%' . $item . '%')
                        ->orWhere('name', 'like', '%' . $item . '%');
                }
            })
                ->orderBy('id', 'desc')
                ->where('status', true)
                ->paginate($show);
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
            $dateCollect = new DateCollect;
            $dateCollect->date = $request->date;
            $dateCollect->save();
            DB::commit();
            return Response::json(['message' => 'Fecha de recolección agregada exitosamente'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return DateCollect::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $dateCollect = DateCollect::find($id);
            $dateCollect->date = $request->date;
            $dateCollect->save();
            DB::commit();
            return Response::json(['message' => 'Fecha de recolección actualizada exitosamente'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $shipment = DateCollect::find($id);
            $shipment->status = false;
            $shipment->save();
            DB::commit();
            return Response::json(['message' => 'Fecha de recolección eliminada exitosamente'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }
    public function getShipments(Request $request)
    {
        $today = DateCollect::where('date', '=', Carbon::now()->format('Y-m-d'))->orderBy('date', 'ASC')->get()->first();
        if (empty($today)) {
            $today = DateCollect::where('date', '>', Carbon::now()->format('Y-m-d'))->orderBy('date', 'ASC')->get()->first();
        }
        if (empty($today)) {
            $today = DateCollect::where('date', '<', Carbon::now()->format('Y-m-d'))->orderBy('date', 'ASC')->get()->first();
        }
        $search = explode(" ", $request->filter);
        if (count($search) == 0) {
            $datesCollect = DateCollect::where('id', $request->filter)->limit(10)->get();
            return Response::json(['today' => $today, 'datesCollect' => $datesCollect], 200);
        } else {
            $datesCollect = DateCollect::where(function ($query) use ($search) {
                foreach ($search as $item) {
                    $query->where('id', 'like', '%' . $item . '%')
                        ->orWhere('date', 'like', '%' . $item . '%');
                }
            })
                ->orderBy('created_at', 'desc')->limit(10)->get();
            return Response::json(['today' => $today, 'datesCollect' => $datesCollect], 200);
        }
    }
}
