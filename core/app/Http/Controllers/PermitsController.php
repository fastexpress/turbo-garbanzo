<?php

namespace App\Http\Controllers;

use App\Models\RolPermit;
use App\Models\Permit;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use \Exception;

class PermitsController extends Controller
{
    public function updatePermit(Request $request, $idPermiso)
    {
        DB::beginTransaction();
        try {
            $rolpermit = RolPermit::find($idPermiso);
            $rolpermit->status = $request->status;
            $rolpermit->save();
            DB::commit();
            return Response::json([
                'message' => 'Permiso actualizado exitosamente'
            ], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }
    public function getPermit($idRol, $idPermiso)
    {
        return RolPermit::select('rol_permits.id', 'permits.name', 'rol_permits.status')->join('permits', 'permits.id', '=', 'rol_permits.idPermit')
            ->where('rol_permits.idRol', $idRol)
            ->where('permits.idParent', $idPermiso)
            ->get();
    }
    public function getPermits(Request $request)
    {
        return Permit::select('id', 'name')->whereNull('idParent')->get();
    }
}
