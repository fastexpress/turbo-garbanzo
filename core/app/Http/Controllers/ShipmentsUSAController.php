<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShipmentUSA;
use App\Models\Bag;
use App\Models\Package;
use App\Models\HeaderPackage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use \Exception;
use Carbon\Carbon;

class ShipmentsUSAController extends Controller
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
            return ShipmentUSA::select('id', 'status', 'city', 'date')
                ->orderBy('id', 'desc')
                ->where('status', true)
                ->with('bags')
                ->paginate($show);
        } else {
            $search = explode(" ", $request->search);
            return ShipmentUSA::where(function ($query) use ($search) {
                foreach ($search as $item) {
                    $query->where('id', 'like', '%' . $item . '%')
                        ->orWhere('city', 'like', '%' . $item . '%');
                }
            })
                ->orderBy('id', 'desc')
                ->where('status', true)
                ->with('bags')
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
        $exist = ShipmentUSA::where('city', $request->city)
            ->where('date', $request->date)
            ->where('status', 1)
            ->get();
        if (count($exist) > 0) {
            return Response::json(['message' => 'Este viaje ya ha sido creado'], 400);
        }
        DB::beginTransaction();
        try {
            $shipment = new ShipmentUSA();
            $shipment->city = $request->city;
            $shipment->date = $request->date;
            $shipment->inCharge = $request->charge;
            $shipment->save();
            // $shipment->
            $bags = json_decode($request->bags);
            foreach ($bags as $bag) {
                $newBag = new Bag();
                $newBag->bag = $bag->bag;
                $newBag->capacity = $bag->capacity;
                $newBag->userTraveler = $bag->traveler->id;
                $newBag->idShipment = $shipment->id;
                $newBag->save();
            }
            DB::commit();
            return Response::json(['message' => 'Envio a USA guardado exitosamente'], 200);
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
        $shipments  = ShipmentUSA::with('inCharge')->where('id', $id)->get()->first();
        $bags = Bag::with('userTraveler')->where('idShipment', $id)->get();
        return Response::json(['shipments' => $shipments, 'bags' => $bags], 200);
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
        $exist = ShipmentUSA::where('city', $request->city)
            ->where('date', $request->date)
            ->where('id', '!=', $id)
            ->where('status', 1)
            ->get();
        if (count($exist) > 0) {
            return Response::json(['message' => 'Este viaje ya ha sido creado'], 400);
        }
        DB::beginTransaction();
        try {
            $shipment = ShipmentUSA::find($id);
            $shipment->city = $request->city;
            $shipment->date = $request->date;
            $shipment->inCharge = $request->charge;
            $shipment->save();

            $bags = Bag::where('idShipment', $id)->get();

            $newBags = json_decode($request->bags);
            $newBags = array_column($newBags, 'id');

            $newBagsTemporal = json_decode($request->bags);

            foreach ($bags as $bag) {
                $found = array_search($bag->id, $newBags);
                if ($found === false) {
                    // NO ESTA
                    $packages = Package::where('idBag', $bag->id)->get();
                    foreach ($packages as $package) {
                        $package->idBag = null;
                        $package->inBag = 1;
                        $package->save();
                    }
                    $bag->delete();
                } else {
                    // SI ESTA
                    $newBagTemporal = $newBagsTemporal[$found];
                    $bag->bag = $newBagTemporal->bag;
                    $bag->capacity = $newBagTemporal->capacity;
                    $bag->userTraveler = $newBagTemporal->traveler->id;
                    $bag->idShipment = $id;
                    $bag->save();
                    array_splice($newBagsTemporal, $found, 1);
                    array_splice($newBags, $found, 1);
                }
            }

            foreach ($newBagsTemporal as $bagTemp) {
                $newBag = new Bag();
                $newBag->bag = $bagTemp->bag;
                $newBag->capacity = $bagTemp->capacity;
                $newBag->userTraveler = $bagTemp->traveler->id;
                $newBag->idShipment = $shipment->id;
                $newBag->save();
            }
            DB::commit();
            return Response::json(['message' => 'Envio a USA actualizado exitosamente'], 200);
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
            $shipment = ShipmentUSA::find($id);
            $shipment->status = false;
            $shipment->save();

            $bags = Bag::where('idShipment', $id)->get();
            foreach ($bags as $bag) {
                $packages = Package::where('idBag', $bag->id)->get();
                foreach ($packages as $package) {
                    $package->idBag = null;
                    $package->inBag = 1;
                    $package->delivered = 1;
                    $package->status = 2;
                    $package->save();

                    $header = HeaderPackage::find($package->idHeaderPackage);
                    $header->status = 2;
                    $header->observationCall = '';
                    $header->callStatus = 0;
                    $header->whatsapp = 0;
                    $header->observationWhatsapp = '';
                    $header->save();
                }
                $bag->delete();
            }

            DB::commit();
            return Response::json(['message' => 'Envio a USA eliminado exitosamente'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }
    public function getShipments(Request $request)
    {
        $today = ShipmentUSA::where('shipment', '=', Carbon::now()->format('Y-m-d'))->orderBy('shipment', 'ASC')->get()->first();
        if (empty($today)) {
            $today = ShipmentUSA::where('shipment', '>', Carbon::now()->format('Y-m-d'))->orderBy('shipment', 'ASC')->get()->first();
        }
        if (empty($today)) {
            $today = ShipmentUSA::where('shipment', '<', Carbon::now()->format('Y-m-d'))->orderBy('shipment', 'ASC')->get()->first();
        }
        $search = explode(" ", $request->filter);
        if (count($search) == 0) {
            $shipments = ShipmentUSA::where('id', $request->filter)->limit(10)->get();
            return Response::json(['today' => $today, 'shipments' => $shipments], 200);
        } else {
            $shipments = ShipmentUSA::where(function ($query) use ($search) {
                foreach ($search as $item) {
                    $query->where('id', 'like', '%' . $item . '%')
                        ->orWhere('shipment', 'like', '%' . $item . '%');
                }
            })
                ->orderBy('created_at', 'desc')->limit(10)->get();
            return Response::json(['today' => $today, 'shipments' => $shipments], 200);
        }
    }
}
