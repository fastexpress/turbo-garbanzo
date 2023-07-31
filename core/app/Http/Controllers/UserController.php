<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use App\Models\User;
use App\Models\Rol;
use \Exception;

class UserController extends Controller
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
            return User::orderBy('id', 'desc')
                ->where('status', true)
                ->with('rol')
                ->paginate($show);
        } else {
            $search = explode(" ", $request->search);
            return User::where(function ($query) use ($search) {
                foreach ($search as $item) {
                    $query->where('id', 'like', '%' . $item . '%')
                        ->orWhere('name', 'like', '%' . $item . '%')
                        ->orWhere('user', 'like', '%' . $item . '%');
                }
            })
                ->orderBy('id', 'desc')
                ->where('status', true)
                ->with('rol')
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
            $user = new User;
            $user->name = $request->name;
            $user->user = $request->user;
            $user->password = bcrypt($request->password);
            if (!empty($request->telegram)) {
                $user->telegramId = $request->telegram;
            }
            if ($request->idRol == 4) {
                $user->passport = $request->passport;
            } else {
                $user->passport = "";
            }
            $user->idRol = $request->idRol;
            $user->save();
            DB::commit();
            return Response::json(['message' => 'Usuario guardado exitosamente'], 200);
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
        $user = User::find($id);
        $rol = Rol::find($user->idRol);
        return Response::json(['user' => $user, 'rol' => $rol], 200);
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
            $user = User::find($id);
            $user->name = $request->name;
            $user->user = $request->user;
            if (!empty($request->telegram)) {
                $user->telegramId = $request->telegram;
            }
            if (isset($request->password)) {
                $user->password = bcrypt($request->password);
            }
            if ($request->idRol == 4) {
                $user->passport = $request->passport;
            } else {
                $user->passport = "";
            }
            $user->idRol = $request->idRol;
            $user->save();
            DB::commit();
            return Response::json(['message' => 'Usuario actualizado exitosamente'], 200);
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
    public function destroy(User $user)
    {
        DB::beginTransaction();
        try {
            $user->status = false;
            $user->save();
            DB::commit();
            return Response::json(['message' => 'Usuario eliminado exitosamente'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }

    public function getUser(Request $request)
    {
        $search = explode(" ", $request->filter);
        return User::select('id', 'name')
            ->where(function ($query) use ($search) {
                foreach ($search as $item) {
                    $query->where('id', 'like', '%' . $item . '%')
                        ->orWhere('name', 'like', '%' . $item . '%')
                        ->orWhere('user', 'like', '%' . $item . '%');
                }
            })
            ->where('status', 1)
            ->orderBy('created_at', 'desc')->paginate(5);
    }

    public function getTravelers(Request $request)
    {
        $search = explode(" ", $request->filter);
        return User::select('id', 'name')
            ->where(function ($query) use ($search) {
                foreach ($search as $item) {
                    $query->where('id', 'like', '%' . $item . '%')
                        ->orWhere('name', 'like', '%' . $item . '%')
                        ->orWhere('user', 'like', '%' . $item . '%');
                }
            })
            ->where('status', 1)
            ->where('idRol', 4)
            ->orderBy('created_at', 'desc')->paginate(5);
    }
}
