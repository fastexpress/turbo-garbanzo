<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HeaderPackage;
use App\Models\Package;
use App\Models\Account;
use App\Models\ShipmentUSA;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use App\Traits\TransactionTrait;
use \Exception;

class SearchPackageController extends Controller
{
    public function call(Request $request)
    {
        $search = $request->search;
        $city = $request->city;
        $date = $request->date;
        $show = $request->show;
        if (is_null($search)) {
            return HeaderPackage::join('packages as p', 'header_packages.id', 'p.idHeaderPackage')
                ->join('bags as b', 'b.id', 'p.idBag')
                ->join('shipment_u_s_a_s as s', 's.id', 'b.idShipment')
                ->select('header_packages.id', 'header_packages.status', 'header_packages.observationCall', 'header_packages.callStatus', 'header_packages.receive', 'header_packages.telephone', 'header_packages.alternativeTelephone')
                ->groupBy('header_packages.id')
                ->where('s.city', $city)
                ->where('s.date', $date)
                ->with('packages')
                ->orderBy('callStatus', 'ASC')
                ->paginate($show);
        } else {
            $search = explode(" ", $request->search);
            return HeaderPackage::join('packages as p', 'header_packages.id', 'p.idHeaderPackage')
                ->join('bags as b', 'b.id', 'p.idBag')
                ->join('shipment_u_s_a_s as s', 's.id', 'b.idShipment')
                ->select('header_packages.id', 'header_packages.status', 'header_packages.observationCall', 'header_packages.callStatus', 'header_packages.receive', 'header_packages.telephone', 'header_packages.alternativeTelephone')
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
                ->with('packages')
                ->orderBy('callStatus', 'ASC')
                ->paginate($show);
        }
    }
    public function statusCall(Request $request)
    {
        DB::beginTransaction();
        try {
            $packages = HeaderPackage::find($request->id);
            $packages->callStatus = $request->status;
            $packages->save();
            DB::commit();
            return Response::json(['message' => 'Estado de la llamada actualizada'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }
    public function observationCall(Request $request)
    {
        DB::beginTransaction();
        try {
            $package = HeaderPackage::find($request->id);
            $package->observationCall = $request->observation;
            $package->save();
            DB::commit();
            return Response::json(['message' => 'Observación agregada'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }
    public function whatsapp(Request $request)
    {
        $search = $request->search;
        $city = $request->city;
        $date = $request->date;
        $show = $request->show;
        if (is_null($search)) {
            return HeaderPackage::join('packages as p', 'header_packages.id', 'p.idHeaderPackage')
                ->join('bags as b', 'b.id', 'p.idBag')
                ->join('shipment_u_s_a_s as s', 's.id', 'b.idShipment')
                ->select('header_packages.id', 'header_packages.status', 'header_packages.observationWhatsapp', 'header_packages.whatsapp', 'header_packages.receive', 'header_packages.telephone', 'header_packages.alternativeTelephone')
                ->groupBy('header_packages.id')
                ->where('s.city', $city)
                ->where('s.date', $date)
                ->with('packages')
                ->orderBy('whatsapp', 'ASC')
                ->paginate($show);
        } else {
            $search = explode(" ", $request->search);
            return HeaderPackage::join('packages as p', 'header_packages.id', 'p.idHeaderPackage')
                ->join('bags as b', 'b.id', 'p.idBag')
                ->join('shipment_u_s_a_s as s', 's.id', 'b.idShipment')
                ->select('header_packages.id', 'header_packages.status', 'header_packages.observationWhatsapp', 'header_packages.whatsapp', 'header_packages.receive', 'header_packages.telephone', 'header_packages.alternativeTelephone')
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
                ->with('packages')
                ->orderBy('whatsapp', 'ASC')
                ->paginate($show);
        }
    }
    public function statusWhatApp(Request $request)
    {
        DB::beginTransaction();
        try {
            $packages = HeaderPackage::find($request->id);
            $packages->whatsapp = $request->status;
            $packages->save();
            DB::commit();
            return Response::json(['message' => 'Estado de WhatsApp actualizado'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }
    public function observationWhatApp(Request $request)
    {
        DB::beginTransaction();
        try {
            $package = HeaderPackage::find($request->id);
            $package->observationWhatsapp = $request->observation;
            $package->save();
            DB::commit();
            return Response::json(['message' => 'Observación agregada'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }
    public function csvWhatsApp(Request $request)
    {
        $telephones = json_decode($request->telephones);
        $city = $request->city;
        $date = $request->date;
        DB::beginTransaction();
        try {
            $packages = HeaderPackage::join('packages as p', 'header_packages.id', 'p.idHeaderPackage')
                ->join('bags as b', 'b.id', 'p.idBag')
                ->join('shipment_u_s_a_s as s', 's.id', 'b.idShipment')
                ->select('header_packages.id')
                ->groupBy('header_packages.id')
                ->where('s.city', $city)
                ->where('s.date', $date)
                ->whereIn('header_packages.telephone', $telephones)
                ->get();
            foreach ($packages as $package) {
                $header = HeaderPackage::find($package->id);
                $header->whatsapp = 1;
                $header->observationWhatsapp = "MENSAJE ENVIADO";
                $header->save();
            }
            DB::commit();
            return Response::json(['message' => 'Estado de la llamada actualizada'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }
    public function packagesWithOutBag(Request $request)
    {
        $search = $request->search;
        $city = $request->city;
        $date = $request->date;
        $show = $request->show;
        if (is_null($search)) {
            return HeaderPackage::join('packages as p', 'header_packages.id', 'p.idHeaderPackage')
                ->select('header_packages.id', 'header_packages.status', 'header_packages.receive', 'header_packages.telephone', 'header_packages.alternativeTelephone', DB::raw('count(p.id) as total'))
                ->groupBy('header_packages.id')
                ->where('header_packages.city', $city)
                ->whereDate('header_packages.created_at', '<=', $date)
                ->whereNull('idBag')
                ->where('InBag', 1)
                ->paginate($show);
        } else {
            $search = explode(" ", $request->search);
            return HeaderPackage::join('packages as p', 'header_packages.id', 'p.idHeaderPackage')
                ->join('bags as b', 'b.id', 'p.idBag')
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
                ->whereDate('header_packages.created_at', '<=', $date)
                ->whereNull('idBag')
                ->where('InBag', 1)
                ->paginate($show);
        }
    }
    public function packageChangeStatus(Request $request)
    {
        DB::beginTransaction();
        try {
            $headerPackage = HeaderPackage::find($request->id);
            $headerPackage->status = $request->status;
            $headerPackage->save();

            $packages = Package::where('idHeaderPackage', $request->id)->get();
            foreach ($packages as $package) {
                $package->status = $request->status;
                $package->save();
            }
            DB::commit();
            return Response::json(['message' => 'Paquete actualizado exitosamente'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }
    public function slope(Request $request)
    {
        $search = $request->search;
        $city = $request->city;
        $date = $request->date;
        $show = $request->show;
        if (is_null($search)) {
            return HeaderPackage::join('packages as p', 'header_packages.id', 'p.idHeaderPackage')
                ->join('bags as b', 'b.id', 'p.idBag')
                ->join('shipment_u_s_a_s as s', 's.id', 'b.idShipment')
                ->select('header_packages.id', 'header_packages.status', 'header_packages.receive', 'header_packages.telephone', 'header_packages.alternativeTelephone')
                ->groupBy('header_packages.id')
                ->with('packages')
                ->where('s.city', $city)
                ->where('s.date', $date)
                ->where('b.status', 1)
                ->where('p.delivered', 1)
                ->paginate($show);
        } else {
            $search = explode(" ", $request->search);
            return HeaderPackage::join('packages as p', 'header_packages.id', 'p.idHeaderPackage')
                ->join('bags as b', 'b.id', 'p.idBag')
                ->join('shipment_u_s_a_s as s', 's.id', 'b.idShipment')
                ->select('header_packages.id', 'header_packages.status', 'header_packages.receive', 'header_packages.telephone', 'header_packages.alternativeTelephone')
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
                ->with('packages')
                ->where('b.status', 1)
                ->where('p.delivered', 1)
                ->paginate($show);
        }
    }
    public function deliver(Request $request)
    {
        $city = $request->city;
        $date = $request->date;
        $shipment = ShipmentUSA::where('city', $city)->where('date', $date)->get()->first();
        if (empty($shipment))
            return Response::json(['message' => 'Este paquete no esta en un viaje a USA'], 400);
        $inCharge = User::find($shipment->inCharge);
        $cashRegister = Account::where('isCashRegister', 1)->where('inCharge', $inCharge->id)->get()->first();
        if (empty($cashRegister))
            return Response::json(['message' => "Para entregar y cobrar este paquete es necesario una caja para {$inCharge->name}"], 400);
        // COBRAR
        DB::beginTransaction();
        try {
            $header = HeaderPackage::find($request->id);
            $packages = Package::where('idHeaderPackage', $request->id)->get();
            foreach ($packages as $package) {
                if ($package->payment == '0') {
                    TransactionTrait::storeTransaction($package->subtotal, "SE COBRÓ EN USA EL PAQUETE PARA {$header->receive}, EN {$header->city} CON CÓDIGO RASTREO {$request->id}", "+", $cashRegister->id, $request->user);
                }
                $package->delivered = 0;
                $package->save();
            }
            DB::commit();
            return Response::json(['message' => 'Paquete cobrado y entregado'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
        // END COBRAR
    }
    public function delivered(Request $request)
    {
        $search = $request->search;
        $city = $request->city;
        $date = $request->date;
        $show = $request->show;
        if (is_null($search)) {
            return HeaderPackage::join('packages as p', 'header_packages.id', 'p.idHeaderPackage')
                ->join('bags as b', 'b.id', 'p.idBag')
                ->join('shipment_u_s_a_s as s', 's.id', 'b.idShipment')
                ->select('header_packages.id', 'header_packages.status', 'header_packages.receive', 'header_packages.telephone', 'header_packages.alternativeTelephone')
                ->groupBy('header_packages.id')
                ->with('packages')
                ->where('s.city', $city)
                ->where('s.date', $date)
                ->where('b.status', 1)
                ->where('p.delivered', 0)
                ->paginate($show);
        } else {
            $search = explode(" ", $request->search);
            return HeaderPackage::join('packages as p', 'header_packages.id', 'p.idHeaderPackage')
                ->join('bags as b', 'b.id', 'p.idBag')
                ->join('shipment_u_s_a_s as s', 's.id', 'b.idShipment')
                ->select('header_packages.id', 'header_packages.status', 'header_packages.receive', 'header_packages.telephone', 'header_packages.alternativeTelephone')
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
                ->with('packages')
                ->where('b.status', 1)
                ->where('p.delivered', 0)
                ->paginate($show);
        }
    }
    public function all(Request $request)
    {
        $search = $request->search;
        $city = $request->city;
        $show = $request->show;
        if (is_null($search)) {
            return HeaderPackage::join('packages as p', 'header_packages.id', 'p.idHeaderPackage')
                ->select('header_packages.id', 'header_packages.status', 'header_packages.receive', 'header_packages.telephone', 'header_packages.alternativeTelephone')
                ->groupBy('header_packages.id')
                ->where('header_packages.city', $city)
                ->with('packages')
                ->paginate($show);
        } else {
            $search = explode(" ", $request->search);
            return HeaderPackage::join('packages as p', 'header_packages.id', 'p.idHeaderPackage')
                ->join('bags as b', 'b.id', 'p.idBag')
                ->select('header_packages.id', 'header_packages.status', 'header_packages.receive', 'header_packages.telephone', 'header_packages.alternativeTelephone')
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
                ->with('packages')
                ->paginate($show);
        }
    }
}
