<?php

namespace App\Http\Controllers;

use App\Models\Baler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use \Exception;

class BalerController extends Controller
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
            return Baler::orderBy('id', 'desc')
                ->where('status', true)
                ->paginate($show);
        } else {
            $search = explode(" ", $request->search);
            return Baler::where(function ($query) use ($search) {
                foreach ($search as $item) {
                    $query->where('id', 'like', '%' . $item . '%')
                        ->orWhere('name', 'like', '%' . $item . '%')
                        ->orWhere('code', 'like', '%' . $item . '%');
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
            $baler = new Baler;
            $baler->name = $request->name;
            $baler->code = $request->code;
            $baler->save();
            DB::commit();
            return Response::json(['message' => 'Empacadora guardada exitosamente'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Baler  $baler
     * @return \Illuminate\Http\Response
     */
    public function show(Baler $baler)
    {
        return $baler;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Baler  $baler
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Baler $baler)
    {
        DB::beginTransaction();
        try {
            $baler->name = $request->name;
            $baler->code = $request->code;
            $baler->save();
            DB::commit();
            return Response::json(['message' => 'Empacadora actualizada exitosamente'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Baler  $baler
     * @return \Illuminate\Http\Response
     */
    public function destroy(Baler $baler)
    {
        DB::beginTransaction();
        try {
            $baler->status = false;
            $baler->save();
            DB::commit();
            return Response::json(['message' => 'Empacadora eliminada exitosamente'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }
    public function getBaler(Request $request)
    {
        $search = explode(" ", $request->filter);
        return Baler::where(function ($query) use ($search) {
            foreach ($search as $item) {
                $query->where('id', 'like', '%' . $item . '%')
                    ->orWhere('name', 'like', '%' . $item . '%')
                    ->orWhere('code', 'like', '%' . $item . '%');
            }
        })
            ->orderBy('created_at', 'desc')
            ->where('status', true)
            ->limit(10)->get();
    }
}
