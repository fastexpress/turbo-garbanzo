<?php

namespace App\Http\Controllers;

use App\Models\PackageUPS;
use App\Models\BoxUPS;
use App\Models\PackageUPSCustomer;
use App\Models\ContentUPS;
use App\Models\AccountCollection;
use App\Models\Account;
use App\Models\Customer;
use App\Models\Setting;
use App\Traits\TransactionTrait;
use App\Traits\ToolTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use \Exception;

class PackageUPSController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return 'HOLA';
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
            $max = PackageUPS::where('serie', $serie->value)->orderBy('id', 'desc')->get()->first();
            $id = "";
            if (isset($max)) {
                // SET VALUE
                $newID =  explode("-", $max->id);
                $newID = intval($newID[2]);
                $newID++;
                $number =  str_pad($newID, 10, "0", STR_PAD_LEFT);
                $id = "FE-{$serie->value}-{$number}-U";
            } else {
                // NOT SET VALUE
                $number =  str_pad(1, 10, "0", STR_PAD_LEFT);
                $id = "FE-{$serie->value}-{$number}-U";
            }
            $package = new PackageUPS;
            $package->id = $id;
            $package->serie = $serie->value;
            $package->receive = $request->receive;
            $package->sender = $request->sender;
            $package->typical = $request->typical;
            $package->telephone = $request->telephone;
            $package->telephoneAlternative = $request->telephoneAlternative;
            $package->address = $request->address;
            $package->postalCode = $request->postalCode;
            $package->guide = $request->guide;
            $package->observation = $request->observation;
            $package->state = $request->state;
            $package->city = $request->city;
            $package->inOffice = $request->inOffice;
            $package->idBaler = $request->baler;
            $package->balance = 0;
            $package->totalKg = $request->totalKg;
            $package->totalContent = $request->totalContent;
            $package->totalPayment = $request->totalPayment;

            $package->inCharge = $request->user;
            $package->userCreated = $request->user;
            $package->userUpdated = $request->user;
            if ($request->typical == false) {
                $package->office = $request->office;
                $package->category = $request->category;
                $package->departament = $request->departament;
                $package->type = $request->type;
                $package->typePayment = $request->typePayment;
                $package->cost = $request->cost;
                $package->multiplicater = $request->multiplicater;
                $package->updatedMultiplier = $request->updatedMultiplier;
                $package->weight = $request->weight;
                if ($request->typePayment == true) {
                    // CAJA TOMASA
                    $account = json_decode(Setting::find(1));
                    $package->idAccountPersonal = $account->id;
                    TransactionTrait::storeTransaction(($package->totalPayment * (ToolTrait::maskToNumber(Setting::find(2)->value))), "SE PAGO EN GUATEMALA EL PAQUETE PARA {$package->receive}, EN {$package->city} CON CÓDIGO RASTREO {$id}", "+", $account->id, $request->user);
                    $package->balance = 0;
                    $package->inPayment = date('Y-m-d H:i:s');
                } else {
                    // CAJA A PAGAR
                    $account = json_decode($request->idAccountPersonal, true);
                    $package->idAccountPersonal = $account['id'];
                    $package->balance = 0;
                    if ($account['sign'] == "Q")
                        TransactionTrait::storeTransaction(($package->totalPayment * (ToolTrait::maskToNumber(Setting::find(2)->value))), "SE COBRARÁ EL PAQUETE PARA {$package->receive}, EN {$package->city} CON CÓDIGO RASTREO {$id}", "+", $account['id'], $request->user);
                    else
                        TransactionTrait::storeTransaction($package->totalPayment, "SE COBRARÁ EL PAQUETE PARA {$package->receive}, EN {$package->city} CON CÓDIGO RASTREO {$id}", "+", $account['id'], $request->user);
                    $package->inPayment = date('Y-m-d H:i:s');
                }
            }
            $package->save();

            // PACKAGES
            if ($request->typical == true) {
                // PackageUPSCustomer
                $customers = json_decode($request->customers, true);

                foreach ($customers as $customer) {
                    $UPSCustomer = new PackageUPSCustomer();
                    $UPSCustomer->idPackageUPS = $id;
                    $UPSCustomer->idCustomer = $customer['id'];
                    $UPSCustomer->idAccountPersonal = Account::where('idCustomer', $customer['id'])->get()->first()->id;
                    $UPSCustomer->weight = $customer['weight'];
                    $UPSCustomer->subtotal = $customer['subtotal'];
                    $UPSCustomer->cost = $customer['cost'];
                    $UPSCustomer->updatedMultiplier = $customer['updatedMultiplier'];
                    $UPSCustomer->multiplicater = $customer['multiplicater'];

                    if ($customer['checkCredit'] == true) {
                        $package->inPayment = date('Y-m-d H:i:s');
                        $package->save();
                        $UPSCustomer->balance = 0;
                        TransactionTrait::storeTransaction(($request->totalPayment * $customer['exchangeRate']), "ENVÍO CANCELADO POR {$customer['name']} CON CÓDIGO RASTREO {$id}", "+", Account::where('idCustomer', $customer['id'])->get()->first()->id, $request->user);
                    } else {
                        $UPSCustomer->idAccountPersonal = $customer['accountPersonal']['id'];
                        $UPSCustomer->balance = $customer['subtotal'];
                        $package->balance = $package->balance + $customer['subtotal'];
                        $package->save();
                        $account = Account::find($customer['accountPersonal']['id']);
                        $account->amountBank = $account->amountBank + ($customer['subtotal'] * $customer['exchangeRate']);
                        $account->save();
                    }
                    $UPSCustomer->typePaymentTypical = $customer['checkCredit'];
                    $UPSCustomer->save();
                }
            }
            // END PACKAGES

            $boxes = json_decode($request->boxes, true);
            foreach ($boxes as $box) {
                $newBox = new BoxUPS;
                $newBox->weightKg = $box['weightKg'];
                $newBox->idPackage = $id;
                $newBox->save();
            }

            $contents = json_decode($request->contents, true);
            foreach ($contents as $content) {
                $newContent = new ContentUPS;
                $newContent->quantity = $content['quantity'];
                $newContent->content = $content['content'];
                $newContent->content_en = $content['content_en'];
                $newContent->price = $content['price'];
                $newContent->subtotal = $content['subtotal'];
                $newContent->idPackage = $id;
                $newContent->save();
            }
            if (count($contents) > 0 && count($boxes) > 0) {
                $package->inContent = date('Y-m-d H:i:s');
                $package->save();
            }
            DB::commit();
            return Response::json(['message' => 'Envío guardado exitosamente'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PackageUPS  $packageUPS
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $packageUPS = PackageUPS::with('baler')->where('id', $id)->get()->first();
        $boxes = BoxUPS::where('idPackage', $id)->get();
        $content = ContentUPS::where('idPackage', $id)->get();
        $customers = PackageUPSCustomer::where('idPackageUPS', $id)->with('customers')->with('accounts')->get();
        return Response::json(['packageUPS' => $packageUPS, 'boxes' => $boxes, 'content' => $content, 'customers' => $customers], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PackageUPS  $packageUPS
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $package = PackageUPS::find($id);
            $package->receive = $request->receive;
            $package->sender = $request->sender;
            $package->typical = $request->typical;
            $package->telephone = $request->telephone;
            $package->telephoneAlternative = $request->telephoneAlternative;
            $package->address = $request->address;
            $package->postalCode = $request->postalCode;
            $package->guide = $request->guide;
            $package->observation = $request->observation;
            $package->state = $request->state;
            $package->city = $request->city;
            $package->inOffice = $request->inOffice;
            $package->idBaler = $request->baler;
            $package->totalKg = $request->totalKg;
            $package->totalContent = $request->totalContent;
            $package->totalPayment = $request->totalPayment;
            $package->balance = 0;
            $package->inPayment = NULL;

            $package->inCharge = $request->user;
            $package->userUpdated = $request->user;

            $packageUPS = PackageUPS::find($package->id);

            // UPDATE
            if ($packageUPS->typical == false) {
                if ($packageUPS->typical == true) {
                    $account = json_decode(Setting::find(1));
                    TransactionTrait::storeTransaction(($packageUPS->totalPayment * (ToolTrait::maskToNumber(Setting::find(2)->value))), "SE ANULO EL COBRO PARA EL PAQUETE PARA {$packageUPS->receive}, EN {$packageUPS->city} CON CÓDIGO RASTREO {$packageUPS->id}", "-", $account->id, $request->user);
                } else {
                    $account = Account::find($packageUPS->idAccountPersonal);
                    if ($account['sign'] == "Q")
                        TransactionTrait::storeTransaction(($packageUPS->totalPayment * (ToolTrait::maskToNumber(Setting::find(2)->value))), "SE ANULO EL COBRO GUATEMALA EL PAQUETE PARA {$packageUPS->receive}, EN {$packageUPS->city} CON CÓDIGO RASTREO {$packageUPS->id}", "-", $account['id'], $request->user);
                    else
                        TransactionTrait::storeTransaction($packageUPS->totalPayment, "SE ANULO EL COBRO DEL PAQUETE PARA {$packageUPS->receive}, EN {$packageUPS->city} CON CÓDIGO RASTREO {$packageUPS->id}", "-", $account['id'], $packageUPS->user);
                }
            } else {
                $customers = PackageUPSCustomer::where('idPackageUPS', $packageUPS->id)->get();

                foreach ($customers as $pkc) {
                    $customer = Customer::find($pkc->idCustomer);
                    if ($pkc->typePaymentTypical == 1) {
                        TransactionTrait::storeTransaction(($pkc->subtotal * $customer->exchangeRate), "ENVÍO ANULADO DE {$customer->name} CON CÓDIGO RASTREO {$packageUPS->id}", "-", Account::where('idCustomer', $customer->id)->get()->first()->id, $request->user);
                    } else {
                        $account = Account::find($pkc->idAccountPersonal);
                        $account->amountBank = $account->amountBank - ($pkc->subtotal * $customer->exchangeRate);
                        $account->save();

                        $paymentAccounts = AccountCollection::where('idPackageUPS', $packageUPS->id)->get();
                        foreach ($paymentAccounts as $paymentAccount) {
                            if ($paymentAccount->collect != "PENDIENTE") {
                                $accountOut = Account::find($paymentAccount->idAccount);
                                TransactionTrait::storeTransaction($paymentAccount->amount, "PAGO DE ENVÍO ANULADO CON CÓDIGO RASTREO {$packageUPS->id}", "-", $accountOut->id, $request->user);
                            }
                            $paymentAccount->delete();
                        }
                    }
                    $pkc->delete();
                }
            }
            $boxes = BoxUPS::where('idPackage', $packageUPS->id)->get();
            foreach ($boxes as $box) {
                $box->delete();
            }
            $contents = ContentUPS::where('idPackage', $packageUPS->id)->get();
            foreach ($contents as $content) {
                $content->delete();
            }
            // STORE
            if ($request->typical == false) {
                $package->office = $request->office;
                $package->category = $request->category;
                $package->departament = $request->departament;
                $package->type = $request->type;
                $package->typePayment = $request->typePayment;
                $package->cost = $request->cost;
                $package->multiplicater = $request->multiplicater;
                $package->updatedMultiplier = $request->updatedMultiplier;
                $package->weight = $request->weight;
                if ($request->typePayment == true) {
                    // CAJA TOMASA
                    $account = json_decode(Setting::find(1));
                    $package->idAccountPersonal = $account->id;
                    TransactionTrait::storeTransaction(($package->totalPayment * (ToolTrait::maskToNumber(Setting::find(2)->value))), "SE PAGO EN GUATEMALA EL PAQUETE PARA {$package->receive}, EN {$package->city} CON CÓDIGO RASTREO {$package->id}", "+", $account->id, $request->user);
                    $package->balance = 0;
                    $package->inPayment = date('Y-m-d H:i:s');
                } else {
                    // CAJA A PAGAR
                    $account = json_decode($request->idAccountPersonal, true);
                    $package->idAccountPersonal = $account['id'];
                    $package->balance = 0;
                    if ($account['sign'] == "Q")
                        TransactionTrait::storeTransaction(($package->totalPayment * (ToolTrait::maskToNumber(Setting::find(2)->value))), "SE PAGO EN GUATEMALA EL PAQUETE PARA {$package->receive}, EN {$package->city} CON CÓDIGO RASTREO {$package->id}", "+", $account['id'], $request->user);
                    else
                        TransactionTrait::storeTransaction($package->totalPayment, "SE COBRARÁ EL PAQUETE PARA {$package->receive}, EN {$package->city} CON CÓDIGO RASTREO {$package->id}", "+", $account['id'], $request->user);
                    $package->inPayment = date('Y-m-d H:i:s');
                }
            }
            $package->save();

            // PACKAGES
            if ($request->typical == true) {
                // PackageUPSCustomer
                $customers = json_decode($request->customers, true);

                foreach ($customers as $customer) {
                    $UPSCustomer = new PackageUPSCustomer();
                    $UPSCustomer->idPackageUPS = $package->id;
                    $UPSCustomer->idCustomer = $customer['id'];
                    $UPSCustomer->idAccountPersonal = Account::where('idCustomer', $customer['id'])->get()->first()->id;
                    $UPSCustomer->weight = $customer['weight'];
                    $UPSCustomer->subtotal = $customer['subtotal'];
                    $UPSCustomer->cost = $customer['cost'];
                    $UPSCustomer->updatedMultiplier = $customer['updatedMultiplier'];
                    $UPSCustomer->multiplicater = $customer['multiplicater'];

                    if ($customer['checkCredit'] == true) {
                        $package->inPayment = date('Y-m-d H:i:s');
                        $package->save();
                        $UPSCustomer->balance = 0;
                        TransactionTrait::storeTransaction(($request->totalPayment * $customer['exchangeRate']), "ENVÍO CANCELADO POR {$customer['name']} CON CÓDIGO RASTREO {$package->id}", "+", Account::where('idCustomer', $customer['id'])->get()->first()->id, $request->user);
                    } else {
                        $UPSCustomer->idAccountPersonal = $customer['accountPersonal']['id'];
                        $UPSCustomer->balance = $customer['subtotal'];
                        $package->balance = $package->balance + $customer['subtotal'];
                        $package->save();
                        $account = Account::find($customer['accountPersonal']['id']);
                        $account->amountBank = $account->amountBank + ($customer['subtotal'] * $customer['exchangeRate']);
                        $account->save();
                    }
                    $UPSCustomer->typePaymentTypical = $customer['checkCredit'];
                    $UPSCustomer->save();
                }
            }
            // END PACKAGES

            $boxes = json_decode($request->boxes, true);
            foreach ($boxes as $box) {
                $newBox = new BoxUPS;
                $newBox->weightKg = $box['weightKg'];
                $newBox->idPackage = $package->id;
                $newBox->save();
            }

            $contents = json_decode($request->contents, true);
            foreach ($contents as $content) {
                $newContent = new ContentUPS;
                $newContent->quantity = $content['quantity'];
                $newContent->content = $content['content'];
                $newContent->content_en = $content['content_en'];
                $newContent->price = $content['price'];
                $newContent->subtotal = $content['subtotal'];
                $newContent->idPackage = $package->id;
                $newContent->save();
            }
            if (count($contents) > 0 && count($boxes) > 0) {
                $package->inContent = date('Y-m-d H:i:s');
                $package->save();
            }
            $this->validateToCode($id);
            DB::commit();
            return Response::json(['message' => 'Envío guardado exitosamente'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }
    public function control(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $package = PackageUPS::find($id);
            $package->receive = $request->receive;
            $package->sender = $request->sender;
            $package->telephone = $request->telephone;
            $package->telephoneAlternative = $request->telephoneAlternative;
            $package->observation = $request->observation;
            $package->idBaler = $request->baler;
            $package->totalKg = $request->totalKg;
            $package->totalContent = $request->totalContent;
            $package->inCharge = $request->user;
            $package->inOffice = $request->inOffice;
            $package->userUpdated = $request->user;
            $package->save();

            $boxes = BoxUPS::where('idPackage', $id)->get();
            foreach ($boxes as $box) {
                $box->delete();
            }
            $contents = ContentUPS::where('idPackage', $id)->get();
            foreach ($contents as $content) {
                $content->delete();
            }

            $boxes = json_decode($request->boxes, true);
            foreach ($boxes as $box) {
                $newBox = new BoxUPS;
                $newBox->weightKg = $box['weightKg'];
                $newBox->idPackage = $id;
                $newBox->save();
            }

            $contents = json_decode($request->contents, true);
            foreach ($contents as $content) {
                $newContent = new ContentUPS;
                $newContent->quantity = $content['quantity'];
                $newContent->content = $content['content'];
                $newContent->content_en = $content['content_en'];
                $newContent->price = $content['price'];
                $newContent->subtotal = $content['subtotal'];
                $newContent->idPackage = $id;
                $newContent->save();
            }
            if (count($contents) > 0 && count($boxes) > 0) {
                $package->inContent = date('Y-m-d H:i:s');
                $package->save();
            }
            DB::commit();
            return Response::json(['message' => 'Envío guardado exitosamente'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PackageUPS  $packageUPS
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $packageUPS = PackageUPS::find($id);

            // UPDATE
            if ($packageUPS->typical == false) {
                if ($packageUPS->typePayment == true) {
                    $account = json_decode(Setting::find(1));
                    TransactionTrait::storeTransaction(($packageUPS->totalPayment * (ToolTrait::maskToNumber(Setting::find(2)->value))), "SE ANULO EL COBRO PARA EL PAQUETE PARA {$packageUPS->receive}, EN {$packageUPS->city} CON CÓDIGO RASTREO {$packageUPS->id}", "-", $account->id, $request->user);
                } else {
                    $account = json_decode($packageUPS->idAccountPersonal, true);
                    if ($account['sign'] == "Q")
                        TransactionTrait::storeTransaction(($packageUPS->totalPayment * (ToolTrait::maskToNumber(Setting::find(2)->value))), "SE ANULO EL COBRO GUATEMALA EL PAQUETE PARA {$packageUPS->receive}, EN {$packageUPS->city} CON CÓDIGO RASTREO {$packageUPS->id}", "-", $account['id'], $request->user);
                    else
                        TransactionTrait::storeTransaction($packageUPS->totalPayment, "SE ANULO EL COBRO DEL PAQUETE PARA {$packageUPS->receive}, EN {$packageUPS->city} CON CÓDIGO RASTREO {$packageUPS->id}", "-", $account['id'], $packageUPS->user);
                }
            } else {
                $customers = PackageUPSCustomer::where('idPackageUPS', $packageUPS->id)->get();
                foreach ($customers as $pkc) {
                    $customer = Customer::find($pkc->idCustomer);
                    if ($pkc->typePaymentTypical) {
                        TransactionTrait::storeTransaction(($pkc->subtotal * $customer->exchangeRate), "ENVÍO ANULADO DE {$customer->name} CON CÓDIGO RASTREO {$packageUPS->id}", "+", Account::where('idCustomer', $customer->id)->get()->first()->id, $request->user);
                    } else {
                        $account = Account::find($pkc->idAccountPersonal);
                        $account->amountBank = $account->amountBank - ($pkc->subtotal * $customer->exchangeRate);
                        $account->save();

                        $paymentAccounts = AccountCollection::where('idPackageUPS')->get();
                        foreach ($paymentAccounts as $paymentAccount) {
                            if ($paymentAccount->collect != "PENDIENTE") {
                                $accountOut = Account::find($paymentAccount->idAccount);
                                TransactionTrait::storeTransaction($paymentAccount->amount, "PAGO DE ENVÍO ANULADO CON CÓDIGO RASTREO {$packageUPS->id}", "-", $accountOut->id, $request->user);
                            }
                            $paymentAccount->delete();
                        }
                    }
                    $pkc->delete();
                }
            }
            $boxes = BoxUPS::where('idPackage', $packageUPS->id)->get();
            foreach ($boxes as $box) {
                $box->delete();
            }
            $contents = ContentUPS::where('idPackage', $packageUPS->id)->get();
            foreach ($contents as $content) {
                $content->delete();
            }
            $packageUPS->delete();
            DB::commit();
            return Response::json(['message' => 'Envío eliminado exitosamente'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }
    public function find($telephone)
    {
        return PackageUPS::where('telephone', $telephone)
            ->where('status', '!=', 'STOPPED')
            ->where('status', '!=', 'DELIVERED')
            ->where('status', '!=', 'ROUTE')
            ->orderBy('id', 'DESC')
            ->get();
    }
    public function returnPackage(Request $request)
    {
        DB::beginTransaction();
        try {
            $packageUPS = PackageUPS::find($request->id);
            $packageUPS->inAddress = NULL;
            $packageUPS->status = "OFICINA";
            $packageUPS->save();
            DB::commit();
            return Response::json(['message' => 'Paquete regresado exitosamente'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }
    private function validateToCode($id)
    {
        $package = PackageUPS::find($id);
        if ($package->status != "OFICINA")
            return;
        if ($package->typical == true) {
            $address = false;
            $content = false;
            $payment = false;
            if (!is_null($package->inAddress))
                $address = true;
            if (count(BoxUPS::where('idPackage', $id)->get()) > 0 && count(ContentUPS::where('idPackage', $id)->get()) > 0)
                $content = true;
            $customers = PackageUPSCustomer::where('idPackageUPS', $id)->get();
            foreach ($customers as $customer) {
                if ($customer->balance > 0) {
                    $payment = false;
                    break;
                } else {
                    $payment = true;
                }
            }
            if ($address == true && $content == true && $payment == true) {
                $max = PackageUPS::max('code');
                $code = 0;
                if (isset($max)) {
                    $code = $max + 1;
                } else {
                    $code = 1;
                }
                $package->status = 'CODE';
                if (is_null($package->code))
                    $package->code = $code;
                $package->save();
            }
        } else {
            $address = false;
            $content = false;
            if (!is_null($package->inAddress))
                $address = true;
            if (count(BoxUPS::where('idPackage', $id)->get()) > 0 && count(ContentUPS::where('idPackage', $id)->get()) > 0)
                $content = true;
            if ($address == true && $content == true) {
                $max = PackageUPS::max('code');
                $code = 0;
                if (isset($max)) {
                    $code = $max + 1;
                } else {
                    $code = 1;
                }
                $package->status = 'CODE';
                if (is_null($package->code))
                    $package->code = $code;
                $package->save();
            }
        }
    }
}
