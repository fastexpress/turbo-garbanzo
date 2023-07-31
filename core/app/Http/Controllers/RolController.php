<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Models\Permit;
use App\Models\RolPermit;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use \Exception;

class RolController extends Controller
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
            return Rol::orderBy('id', 'desc')
                ->where('status', true)
                ->paginate($show);
        } else {
            $search = explode(" ", $request->search);
            return Rol::where(function ($query) use ($search) {
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
            $rol = new Rol;
            $rol->name = $request->name;
            $rol->save();
            $permits = Permit::get();

            foreach ($permits as $permit) {
                $myPermit = new RolPermit;
                $myPermit->idRol = $rol->id;
                $myPermit->idPermit = $permit->id;
                $myPermit->status = 0;
                $myPermit->save();
            }

            DB::commit();
            return Response::json(['message' => 'Rol guardado exitosamente'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Rol  $rol
     * @return \Illuminate\Http\Response
     */
    public function show(Rol $rol)
    {
        return $rol;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Rol  $rol
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rol $rol)
    {
        DB::beginTransaction();
        try {
            $rol->name = $request->name;
            $rol->save();
            DB::commit();
            return Response::json(['message' => 'Rol actualizado exitosamente'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Rol  $rol
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rol $rol)
    {
        DB::beginTransaction();
        try {
            $rol->status = false;
            $rol->save();
            DB::commit();
            return Response::json(['message' => 'Rol eliminado exitosamente'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }
    public function getRols(Request $request)
    {
        $search = explode(" ", $request->filter);
        return Rol::where(function ($query) use ($search) {
            foreach ($search as $item) {
                $query->where('id', 'like', '%' . $item . '%')
                    ->orWhere('name', 'like', '%' . $item . '%');
            }
        })
            ->orderBy('created_at', 'desc')
            ->limit(10)->get();
    }
}
