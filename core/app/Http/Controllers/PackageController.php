<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\HeaderPackage;
use App\Models\Setting;
use App\Models\Bag;
use App\Models\ShipmentUSA;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use \Exception;
use App\Traits\TransactionTrait;
use App\Traits\ToolTrait;
use Jimmyjs\ReportGenerator\Facades\ExcelReportFacade as ExcelReport;
use Carbon\Carbon;

use Illuminate\Http\Request;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $city = $request->city;
        $date = $request->date;
        if (is_null($search)) {
            return HeaderPackage::join('packages as p', 'header_packages.id', 'p.idHeaderPackage')
                ->join('bags as b', 'b.id', 'p.idBag')
                ->join('shipment_u_s_a_s as s', 's.id', 'b.idShipment')
                ->select('header_packages.id', 'header_packages.status', 'header_packages.receive', 'header_packages.telephone', 'header_packages.alternativeTelephone', DB::raw('count(p.id) as total'))
                ->groupBy('header_packages.id')
                ->where('s.city', $city)
                ->where('s.date', $date)
                ->where('b.status', 1)
                ->paginate(10);
        } else {
            $search = explode(" ", $request->search);
            return HeaderPackage::join('packages as p', 'header_packages.id', 'p.idHeaderPackage')
                ->join('bags as b', 'b.id', 'p.idBag')
                ->join('shipment_u_s_a_s as s', 's.id', 'b.idShipment')
                ->select('header_packages.id', 'header_packages.status', 'header_packages.receive', 'header_packages.telephone', 'header_packages.alternativeTelephone', DB::raw('count(p.id) as total'))
                ->groupBy('header_packages.id')
                ->where(function ($query) use ($search) {
                    foreach ($search as $item) {
                        $query->where('header_packages.id', 'like', '%' . $item . '%')
                            ->orWhere('p.id', 'like', '%' . $item . '%')
                            ->orWhere('p.send', 'like', '%' . $item . '%')
                            ->orWhere('p.codeFast', 'like', '%' . $item . '%')
                            ->orWhere('header_packages.receive', 'like', '%' . $item . '%');
                    }
                })
                ->where('s.city', $city)
                ->where('s.date', $date)
                ->where('b.status', 1)
                ->paginate(10);
        }

        // HeaderPackage::withCount('packages')->paginate($show);
        // if ($search == '') {
        //     $packages = HeaderPackage::withCount('packages')->paginate($show);
        //     return Response::json(['packages' => $packages], 200);
        // } else {
        //     $packages = HeaderPackage::withCount('packages')->paginate($show);
        //     return Response::json(['packages' => $packages], 200);
        // }
    }
    public function bag(Request $request)
    {
        $search = $request->search;
        $city = $request->city;
        $date = $request->date;
        if (is_null($search)) {
            return HeaderPackage::join('packages as p', 'header_packages.id', 'p.idHeaderPackage')
                ->select('header_packages.id', 'header_packages.status', 'header_packages.receive', 'header_packages.telephone', 'header_packages.alternativeTelephone', DB::raw('count(p.id) as total'))
                ->groupBy('header_packages.id')
                ->where('header_packages.city', $city)
                ->where('b.status', 1)
                ->paginate(10);
        } else {
            $search = explode(" ", $request->search);
            return HeaderPackage::join('packages as p', 'header_packages.id', 'p.idHeaderPackage')
                ->select('header_packages.id', 'header_packages.status', 'header_packages.receive', 'header_packages.telephone', 'header_packages.alternativeTelephone', DB::raw('count(p.id) as total'))
                ->groupBy('header_packages.id')
                ->where(function ($query) use ($search) {
                    foreach ($search as $item) {
                        $query->where('header_packages.id', 'like', '%' . $item . '%')
                            ->orWhere('p.id', 'like', '%' . $item . '%')
                            ->orWhere('p.send', 'like', '%' . $item . '%')
                            ->orWhere('p.codeFast', 'like', '%' . $item . '%')
                            ->orWhere('header_packages.receive', 'like', '%' . $item . '%');
                    }
                })
                ->where('header_packages.city', $city)
                ->where('b.status', 2)
                ->paginate(10);
        }

        // HeaderPackage::withCount('packages')->paginate($show);
        // if ($search == '') {
        //     $packages = HeaderPackage::withCount('packages')->paginate($show);
        //     return Response::json(['packages' => $packages], 200);
        // } else {
        //     $packages = HeaderPackage::withCount('packages')->paginate($show);
        //     return Response::json(['packages' => $packages], 200);
        // }
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
            $serie = Setting::find(7);
            $max = HeaderPackage::where('serie', $serie->value)->orderBy('id', 'desc')->get()->first();
            $id = "";
            if (isset($max)) {
                // SET VALUE
                $newID =  explode("-", $max->id);
                $newID = intval($newID[2]);
                $newID++;
                $number =  str_pad($newID, 10, "0", STR_PAD_LEFT);
                $id = "FE-{$serie->value}-{$number}-P";
            } else {
                // NOT SET VALUE
                $number =  str_pad(1, 10, "0", STR_PAD_LEFT);
                $id = "FE-{$serie->value}-{$number}-P";
            }

            $header = new HeaderPackage();
            $header->id = $id;
            $header->receive = $request->receive;
            $header->telephone = $request->telephone;
            $header->serie = $serie->value;
            $header->city = $request->city;

            if (!empty($request->alternativeTelephone)) {
                $header->alternativeTelephone = $request->alternativeTelephone;
            }

            $header->save();
            $packages = json_decode($request->packages, true);

            foreach ($packages as $package) {
                $newPackage = new Package;
                $newPackage->send = $package['send'];
                $newPackage->departament = json_encode($package['departament']);
                $newPackage->office = $package['office'];
                $newPackage->type = json_encode($package['type']);
                $newPackage->content = $package['content'];
                $newPackage->content_en = $package['content_en'];
                $newPackage->updatedMultiplier = $package['updatedMultiplier'];
                $newPackage->multiplier = $package['multiplier'];
                $newPackage->category = json_encode($package['category']);
                $newPackage->weight = $package['weight'];
                $newPackage->cost = $package['cost'];
                $newPackage->payment = $package['payment'];
                $newPackage->guide = $package['guide'];
                $newPackage->observation = $package['observation'];
                $newPackage->subtotal = $package['subtotal'];
                $newPackage->idBaler = $package['baler']['id'];
                $newPackage->idHeaderPackage = $id;
                $newPackage->idParent = $package['key'];
                $newPackage->correlative = $package['correlative'];
                $newPackage->codeFast = $package['baler']['code'] . "-" . $package['correlative'];
                $newPackage->save();

                if ($newPackage->payment == true) {
                    $account = json_decode(Setting::find(1));
                    TransactionTrait::storeTransaction(($newPackage->subtotal * (ToolTrait::maskToNumber(Setting::find(2)->value))), "SE PAGO EN GUATEMALA EL PAQUETE PARA {$header->receive}, EN {$header->city} CON CÓDIGO RASTREO {$id}", "+", $account->id, $request->user);
                }
            }
            DB::commit();
            return Response::json(['message' => 'Paquete guardado exitosamente'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $headerPackage = HeaderPackage::find($id);
        $packages = Package::with('baler')->where('idHeaderPackage', $id)->get();
        return Response::json(['headerPackage' => $headerPackage, 'packages' => $packages], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {


            $header = HeaderPackage::find($id);
            $header->receive = $request->receive;
            $header->telephone = $request->telephone;
            $header->serie = $request->serie;
            $header->city = $request->city;
            $header->status = 2;
            if (!empty($request->alternativeTelephone)) {
                $header->alternativeTelephone = $request->alternativeTelephone;
            }
            $header->save();
            // DELETE PACKAGES
            $packages = Package::where('idHeaderPackage', $id)->get();
            foreach ($packages as $package) {
                if ($package->payment == true) {
                    $account = json_decode(Setting::find(1));
                    TransactionTrait::storeTransaction(($package->subtotal * (ToolTrait::maskToNumber(Setting::find(2)->value))), "SE ANULO EL PAGO DEL PAQUETE PARA {$header->receive}, EN {$header->city} CON CÓDIGO RASTREO {$id}", "-", $account->id, $request->user);
                }
                $package->delete();
            }
            // END DELETE PACKAGES
            $packages = json_decode($request->packages, true);

            foreach ($packages as $package) {
                $newPackage = new Package;
                $newPackage->send = $package['send'];
                $newPackage->departament = json_encode($package['departament']);
                $newPackage->office = $package['office'];
                $newPackage->type = json_encode($package['type']);
                $newPackage->content = $package['content'];
                $newPackage->content_en = $package['content_en'];
                $newPackage->updatedMultiplier = $package['updatedMultiplier'];
                $newPackage->multiplier = $package['multiplier'];
                $newPackage->category = json_encode($package['category']);
                $newPackage->weight = $package['weight'];
                $newPackage->cost = $package['cost'];
                $newPackage->payment = $package['payment'];
                $newPackage->guide = $package['guide'];
                $newPackage->observation = $package['observation'];
                $newPackage->subtotal = $package['subtotal'];
                $newPackage->idBaler = $package['baler']['id'];
                $newPackage->idHeaderPackage = $id;
                $newPackage->idParent = $package['key'];
                $newPackage->correlative = $package['correlative'];
                $newPackage->codeFast = $package['baler']['code'] . "-" . $package['correlative'];
                $newPackage->save();

                if ($newPackage->payment == true) {
                    $account = json_decode(Setting::find(1));
                    TransactionTrait::storeTransaction(($newPackage->subtotal * (ToolTrait::maskToNumber(Setting::find(2)->value))), "SE PAGO EN GUATEMALA EL PAQUETE PARA {$header->receive}, QUE VA PARA {$header->city} CON CÓDIGO RASTREO {$id}", "+", $account->id, $request->user);
                }
            }
            DB::commit();
            return Response::json(['message' => 'Paquete guardado exitosamente'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function destroy(Package $package)
    {
        // DB::beginTransaction();
        // try {
        //     $updatePkg = Package::find($package->id);
        //     $updatePkg->status=
        //     DB::commit();
        // } catch (Exception $e) {
        //     DB::rollback();
        //     return Response::json(['message' => $e->getMessage()], 400);
        // }
    }
    public function getTotalWeight(Request $request)
    {
        return Package::join('header_packages', 'header_packages.id', 'packages.idHeaderPackage')
            ->whereNull('idBag')
            ->where('city', $request->city)
            ->where('inBag', 1)
            ->select(DB::raw('COALESCE(SUM(packages.weight),0) as total'))
            ->get()
            ->first();
    }
    public function report(Request $request)
    {
        $title = "Reporte de paquetes";
        $meta = [
            'Listado de paquetes' => $request->city,
        ];
        $queryBuilder = Package::join('header_packages', 'header_packages.id', 'packages.idHeaderPackage')
            ->select('packages.idHeaderPackage', 'header_packages.receive', 'packages.send', 'packages.content', 'packages.content_en', 'header_packages.telephone', 'packages.weight', 'packages.cost', 'packages.subtotal', 'packages.observation')
            ->where('inBag', 1)
            ->where('city', $request->city);
        $columns = [
            'Código' => 'idHeaderPackage',
            'Recibe' => 'receive',
            'Envía' => 'send',
            'Contenido' => 'content',
            'Contenido en ingles' => 'content_en',
            'Telefono' => 'telephone',
            'Peso' => 'weight',
            'Costo' => 'cost',
            'Telefono' => 'telephone',
            'Subtotal' => 'subtotal',
            'Observacion' => 'observation'
        ];
        return ExcelReport::of($title, $meta, $queryBuilder, $columns)
            ->download('REPORTE_PAQUETES_' . Carbon::now()->format('d_m_Y_h:mm'));
    }
    public function getPackages(Request $request)
    {
        $date = $request->date;
        $user = $request->user;
        $city = $request->city;

        $packages = Package::join('header_packages', 'header_packages.id', 'packages.idHeaderPackage')
            ->select('packages.id', 'packages.content', 'packages.idParent', 'packages.category', 'packages.weight', 'packages.idBaler', 'packages.correlative', 'packages.idHeaderPackage', 'packages.created_at')
            ->whereNull('idBag')
            ->where('inBag', 1)
            ->where('packages.status', 2)
            ->with('baler')
            ->where('header_packages.city', $city)
            ->get();

        $bags = Bag::join('shipment_u_s_a_s', 'shipment_u_s_a_s.id', 'bags.idShipment')
            ->select('bags.id', 'bags.bag', 'bags.capacity')
            ->whereDate('shipment_u_s_a_s.date', $date)
            ->where('userTraveler', $user)
            ->where('bags.status', 2)
            ->where('shipment_u_s_a_s.city', $city)
            ->get();
        $bagsFull = $bags->map(function ($bag) {
            $temporalBag['id'] = $bag->id;
            $temporalBag['bag'] = $bag->bag;
            $temporalBag['capacity'] = $bag->capacity;
            $temporalBag['packages'] = Package::select('packages.id', 'packages.content', 'packages.idParent', 'packages.category', 'packages.weight', 'packages.idBaler', 'packages.correlative', 'packages.idHeaderPackage', 'packages.created_at')
                ->where('idBag', $bag->id)
                ->with('baler')
                ->get();
            $temporalBag['total'] = Package::select(DB::raw('COALESCE(sum(packages.weight),0) as total'))
                ->where('idBag', $bag->id)
                ->with('baler')
                ->get()
                ->first()
                ->total;
            return $temporalBag;
        });
        return Response::json(['packages' => $packages, 'bags' => $bagsFull], 200);
    }
    public function moveListToBag(Request $request, $id)
    {
        $bag = $request->bag;
        DB::beginTransaction();
        try {
            $package = Package::find($id);

            if (!is_null($package->idBag)) {
                return Response::json(['message' => 'Este paquete ya tiene una maleta'], 200);
            }

            $package->inBag = 0;
            $package->idBag = $bag;
            $package->save();

            DB::commit();
            return Response::json(['message' => 'Paquete agregado a una maleta'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }
    public function moveBagToList($id)
    {
        DB::beginTransaction();
        try {

            $package = Package::find($id);
            if (is_null($package->idBag)) {
                return Response::json(['message' => 'Esta paquete ya no tenia maleta'], 200);
            }
            $package->inBag = 1;
            $package->idBag = null;
            $package->save();

            DB::commit();
            return Response::json(['message' => 'Paquete removido de su maleta'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }
    public function moveRestPackages(Request $request)
    {
        DB::beginTransaction();
        try {
            $date = $request->date;
            $user = $request->user;
            $city = $request->city;

            $packages = Package::join('header_packages', 'header_packages.id', 'packages.idHeaderPackage')
                ->select('packages.id')
                ->whereNull('idBag')
                ->where('inBag', 1)
                ->with('baler')
                ->where('header_packages.city', $city)
                ->get();

            $findShipment = ShipmentUSA::where('city', $city)
                ->where('date', $date)
                ->get()
                ->first();

            $newBag = new Bag();
            $newBag->bag = 'Mochila';
            $newBag->capacity = 0;
            $newBag->userTraveler = $user;
            $newBag->idShipment = $findShipment->id;
            $newBag->save();

            foreach ($packages as $p) {
                $package = Package::find($p->id);
                $package->inBag = 0;
                $package->idBag = $newBag->id;
                $package->save();
            }

            DB::commit();
            return Response::json(['message' => 'Paquetes restantes agregados a una mochila'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }
}
