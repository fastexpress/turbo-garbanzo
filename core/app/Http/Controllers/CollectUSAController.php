<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use \Exception;
use App\Models\CollectGT;
use App\Models\Town;
use App\Models\User;
use App\Notifications\TelegramNotification;
use Carbon\Carbon;
use App\Models\Departament;
use Jimmyjs\ReportGenerator\Facades\PdfReportFacade as PDFReport;



class CollectUSAController extends Controller
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

        $departament = $request->departament;
        $town = $request->town;
        $type = $request->type;
        $to = $request->to;
        $from = $request->from;
        if ($departament == 0) {
            // ALL REGISTRES
            if ($search == '') {
                $collect = CollectGT::orderBy('id', 'desc')
                    ->with('town', 'departament')
                    ->orderBy('id', 'desc')
                    ->where('status', $type)
                    ->whereBetween('date', [$from, $to]);
                $total = $collect->get()->count();
                return ['collect' => $collect->with('userCreated:id,name', 'userUpdated:id,name', 'userCollect:id,name')->paginate($show), 'total' => $total];
            } else {
                $search = explode(" ", $request->search);
                $collect = CollectGT::where(function ($query) use ($search) {
                    foreach ($search as $item) {
                        $query->where('id', 'like', '%' . $item . '%')
                            ->orWhere('name', 'like', '%' . $item . '%')
                            ->orWhere('address', 'like', '%' . $item . '%')
                            ->orWhere('telephone', 'like', '%' . $item . '%')
                            ->orWhere('state', 'like', '%' . $item . '%')
                            ->orWhere('city', 'like', '%' . $item . '%');
                    }
                })
                    ->orderBy('id', 'desc')
                    ->where('status', $type)
                    ->whereBetween('date', [$from, $to]);
                $total = $collect->get()->count();
                return ['collect' => $collect->with('userCreated:id,name', 'userUpdated:id,name', 'userCollect:id,name')->paginate($show), 'total' => $total];
            }
        } else if ($departament != 0 && $town == 0) {
            // ALL REGISTRES OF DEPARTAMENT
            $towns = Town::where('idDepartament', $departament)->get()->pluck('id');
            if ($search == '') {
                $collect = CollectGT::orderBy('id', 'desc')
                    ->with('town', 'departament')
                    ->orderBy('id', 'desc')
                    ->where('status', $type)
                    ->whereIn('idTown', $towns)
                    ->whereBetween('date', [$from, $to]);
                $total = $collect->get()->count();
                return ['collect' => $collect->with('userCreated:id,name', 'userUpdated:id,name', 'userCollect:id,name')->paginate($show), 'total' => $total];
            } else {
                $search = explode(" ", $request->search);
                $collect = CollectGT::where(function ($query) use ($search) {
                    foreach ($search as $item) {
                        $query->where('id', 'like', '%' . $item . '%')
                            ->orWhere('name', 'like', '%' . $item . '%')
                            ->orWhere('address', 'like', '%' . $item . '%')
                            ->orWhere('telephone', 'like', '%' . $item . '%')
                            ->orWhere('state', 'like', '%' . $item . '%')
                            ->orWhere('city', 'like', '%' . $item . '%');
                    }
                })
                    ->orderBy('id', 'desc')
                    ->where('status', $type)
                    ->whereIn('idTown', $towns)
                    ->whereBetween('date', [$from, $to]);
                $total = $collect->get()->count();
                return ['collect' => $collect->with('userCreated:id,name', 'userUpdated:id,name', 'userCollect:id,name')->paginate($show), 'total' => $total];
            }
        } else {
            // SPECIFIC TOWN

            if ($search == '') {
                $collect = CollectGT::orderBy('id', 'desc')
                    ->with('town', 'departament')
                    ->orderBy('id', 'desc')
                    ->where('status', $type)
                    ->where('idTown', $town)
                    ->whereBetween('date', [$from, $to]);
                $total = $collect->get()->count();
                return ['collect' => $collect->with('userCreated:id,name', 'userUpdated:id,name', 'userCollect:id,name')->paginate($show), 'total' => $total];
            } else {
                $search = explode(" ", $request->search);
                $collect = CollectGT::where(function ($query) use ($search) {
                    foreach ($search as $item) {
                        $query->where('id', 'like', '%' . $item . '%')
                            ->orWhere('name', 'like', '%' . $item . '%');
                    }
                })
                    // ->with('town', 'departament', 'shipment')
                    ->orderBy('id', 'desc')
                    ->where('status', $type)
                    ->where('idTown', $town)
                    ->whereBetween('date', [$from, $to]);
                $total = $collect->get()->count();
                return ['collect' => $collect->with('userCreated:id,name', 'userUpdated:id,name', 'userCollect:id,name')->paginate($show), 'total' => $total];
            }
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
            $collect = new CollectGT;
            $collect->name = $request->name;
            $collect->address = $request->address;
            $collect->telephone = $request->telephone;
            if (isset($request->alternativeTelephone)) {
                $collect->alternativeTelephone = $request->alternativeTelephone;
            }
            $collect->date = $request->date;
            $collect->idTown = $request->town;
            $collect->userCreated = $request->userCreated;
            $collect->userUpdated = $request->userUpdated;
            $collect->save();
            // NOTIFICATION COLLECT
            $users = User::where('idRol', 2)->orWhere('idRol', 1)->whereNotNull('telegramId')->get();
            // MADE MESSAGE
            $date = Carbon::createFromDate($request->date);
            $day = mb_strtoupper($this->nameDay($date->format('l')) . " " . $date->format('d'));
            $month = mb_strtoupper($date->monthName);
            $year = mb_strtoupper($date->format('Y'));

            $town = Town::find($request->town);
            $departament = Departament::find($town->idDepartament);

            $town = mb_strtoupper($town->name);
            $departament = mb_strtoupper($departament->name);
            // END MADE MESSAGE
            foreach ($users as $user) {

                $body['message'] = "NUEVA RECOLECCIÓN CREADA PARA LA FECHA {$day} DE {$month} DEL {$year} EN {$departament},{$town}";
                $user->notify(new TelegramNotification($body));
            }
            DB::commit();
            return Response::json(['message' => 'Recolección guardada exitosamente'], 200);
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
        return CollectGT::with('town', 'departament')->where('id', $id)->get()->first();
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
            $collect = CollectGT::find($id);
            $collect->name = $request->name;
            $collect->address = $request->address;
            $collect->telephone = $request->telephone;
            if (isset($request->alternativeTelephone)) {
                $collect->alternativeTelephone = $request->alternativeTelephone;
            }
            $collect->date = $request->date;
            $collect->idTown = $request->town;
            $collect->userUpdated = $request->userUpdated;
            $collect->status = 2;
            $collect->save();
            // NOTIFICATION COLLECT
            $users = User::where('idRol', 2)->orWhere('idRol', 1)->whereNotNull('telegramId')->get();
            // MADE MESSAGE
            $date = Carbon::createFromDate($request->date);
            $day = mb_strtoupper($this->nameDay($date->format('l')) . " " . $date->format('d'));
            $month = mb_strtoupper($date->monthName);
            $year = mb_strtoupper($date->format('Y'));

            $town = Town::find($request->town);
            $departament = Departament::find($town->idDepartament);

            $town = mb_strtoupper($town->name);
            $departament = mb_strtoupper($departament->name);
            // END MADE MESSAGE
            foreach ($users as $user) {
                $body['message'] = "RECOLECCIÓN ACTUALIZADA A LA FECHA {$day} DE {$month} DEL {$year} EN {$departament},{$town} DEL CLIENTE {$collect->name}";
                $user->notify(new TelegramNotification($body));
            }
            DB::commit();
            return Response::json(['message' => 'Recolección guardada exitosamente'], 200);
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
        //
    }
    public function accept($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $collect = CollectGT::find($id);
            $collect->status = 1;
            $collect->userCollect = $request->userCollect;
            $collect->save();
            $users = User::where('idRol', 2)->orWhere('idRol', 1)->whereNotNull('telegramId')->get();
            // MADE MESSAGE
            $date = Carbon::createFromDate($request->date);
            $day = mb_strtoupper($this->nameDay($date->format('l')) . " " . $date->format('d'));
            $month = mb_strtoupper($date->monthName);
            $year = mb_strtoupper($date->format('Y'));

            $town = Town::find($collect->idTown);
            $departament = Departament::find($town->idDepartament);

            $town = mb_strtoupper($town->name);
            $departament = mb_strtoupper($departament->name);

            $nameUser = User::find($request->userCollect)->name;

            // END MADE MESSAGE
            foreach ($users as $user) {
                $body['message'] = "RECOLECCIÓN RECOLECTADA POR {$nameUser} EN {$departament},{$town} DEL CLIENTE {$collect->name} EN LA FECHA {$day} DE {$month} DEL {$year}";
                $user->notify(new TelegramNotification($body));
            }
            DB::commit();
            return Response::json(['message' => 'Recolección recolectada exitosamente'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }
    public function deny($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $collect = CollectGT::find($id);
            $collect->status = 0;
            $collect->userCollect = $request->userCollect;
            $collect->save();
            // MADE MESSAGE
            $users = User::where('idRol', 2)->orWhere('idRol', 1)->whereNotNull('telegramId')->get();
            $date = Carbon::createFromDate($request->date);
            $day = mb_strtoupper($this->nameDay($date->format('l')) . " " . $date->format('d'));
            $month = mb_strtoupper($date->monthName);
            $year = mb_strtoupper($date->format('Y'));

            $town = Town::find($collect->idTown);
            $departament = Departament::find($town->idDepartament);

            $town = mb_strtoupper($town->name);
            $departament = mb_strtoupper($departament->name);

            $nameUser = User::find($request->userCollect)->name;
            // END MADE MESSAGE
            foreach ($users as $user) {
                $body['message'] = "RECOLECCIÓN ANULADA POR {$nameUser} EN {$departament},{$town} DEL CLIENTE {$collect->name} EN LA FECHA {$day} DE {$month} DEL {$year}";
                $user->notify(new TelegramNotification($body));
            }
            DB::commit();
            return Response::json(['message' => 'Recolección anulada exitosamente'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }
    private function nameDay($day)
    {
        if ($day == "Monday")
            return "LUNES";
        else if ($day == "Tuesday")
            return "MARTES";
        else if ($day == "Wednesday")
            return "MIÉRCOLES";
        else if ($day == "Thursday")
            return "JUEVES";
        else if ($day == "Friday")
            return "VIERNES";
        else if ($day == "Saturday")
            return "SÁBADO";
        else if ($day == "Sunday")
            return "DOMINGO";
    }
    public function report(Request $request)
    {
        $departament = $request->departament;
        $town = $request->town;
        $type = $request->type;
        $to = $request->to;
        $from = $request->from;
        $collect = null;
        $name = null;

        if ($departament == 0) {
            // ALL REGISTRES
            $collect = CollectGT::select('id', 'name', 'address', 'telephone', 'alternativeTelephone', 'date', 'status', 'idTown', 'userCollect', 'created_at')
                ->orderBy('id', 'desc')
                ->with('town', 'departament')
                ->orderBy('id', 'desc')
                ->where('status', $type)
                ->whereBetween('date', [$from, $to]);
            $name = "Todos los departamentos";
        } else if ($departament != 0 && $town == 0) {
            // ALL REGISTRES OF DEPARTAMENT
            $towns = Town::where('idDepartament', $departament)->get()->pluck('id');
            $collect = CollectGT::select('id', 'name', 'address', 'telephone', 'alternativeTelephone', 'date', 'status', 'idTown', 'userCollect', 'created_at')
                ->orderBy('id', 'desc')
                ->with('town', 'departament')
                ->orderBy('id', 'desc')
                ->where('status', $type)
                ->whereIn('idTown', $towns)
                ->whereBetween('date', [$from, $to]);
            $nameDepartament = Departament::find($departament)->name;
            $name = "Departamento {$nameDepartament}";
        } else {
            // SPECIFIC TOWN
            $collect = CollectGT::select('id', 'name', 'address', 'telephone', 'alternativeTelephone', 'date', 'status', 'idTown', 'userCollect', 'created_at')
                ->orderBy('id', 'desc')
                ->with('town', 'departament')
                ->orderBy('id', 'desc')
                ->where('status', $type)
                ->where('idTown', $town)
                ->whereBetween('date', [$from, $to]);

            $nameTown = Town::find($town);
            $nameDepartament = Departament::find($nameTown->idDepartament)->name;
            $name = "Departamento {$nameDepartament}, Municipcio: {$nameTown->name}";
        }
        $title = "Reporte de recolecciones";
        $meta = [
            'Registros del ' => Carbon::parse($to)->format('d/m/Y') . ' al ' . Carbon::parse($from)->format('d/m/Y'),
            'De' => $name,
        ];
        $columns = [
            'Nombre de cliente' => 'name',
            'Dirección' => 'address',
            '____Teléfono____' => 'telephone',
            'Departamento' => 'idTown',
            'Municipio' => 'idTown',
            'Fecha' => 'date',
            'Recolector' => 'userCollect',
            'Estado' => 'status',
            'Fecha creación' => 'created_at',
        ];

        return PDFReport::of($title, $meta, $collect, $columns)
            ->editColumn('Departamento', [
                'displayAs' => function ($result) {
                    return Departament::find(Town::find($result->idTown)->idDepartament)->name;
                },
                'class' => 'left'
            ])
            ->editColumn('Municipio', [
                'displayAs' => function ($result) {
                    return Town::find($result->idTown)->name;
                },
                'class' => 'left'
            ])
            ->editColumn('Estado', [
                'displayAs' => function ($result) {
                    if ($result->status == 2)
                        return "Pendiente";
                    else if ($result->status == 1)
                        return "Recolectado";
                    else
                        return "Anulado";
                },
                'class' => 'left'
            ])
            ->editColumn('Fecha de recolección', [
                'displayAs' => function ($result) {
                    return Carbon::parse($result->date)->format('d/m/Y');
                },
                'class' => 'left'
            ])
            ->editColumn('Recolector', [
                'displayAs' => function ($result) {
                    return is_null($result->userCollect) ? 'Pendiente' : User::find($result->userCollect)->name;
                },
                'class' => 'left'
            ])
            ->editColumn('Fecha creación', [
                'displayAs' => function ($result) {
                    return Carbon::parse($result->created_at)->format('d/m/Y');
                },
                'class' => 'left'
            ])
            ->setOrientation('landscape')
            ->stream();
        // ->download("REPORTE_{$name}_" . Carbon::now()->format('d_m_Y_h:mm'));
    }
}
