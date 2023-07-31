<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\AccountController;
use App\Models\Account;
use \Exception;

class CustomerController extends Controller
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
            return Customer::orderBy('id', 'desc')
                ->where('status', true)
                ->paginate($show);
        } else {
            $search = explode(" ", $request->search);
            return Customer::where(function ($query) use ($search) {
                foreach ($search as $item) {
                    $query->where('id', 'like', '%' . $item . '%')
                        ->orWhere('name', 'like', '%' . $item . '%')
                        ->orWhere('telephone', 'like', '%' . $item . '%')
                        ->orWhere('alternativeTelephone', 'like', '%' . $item . '%')
                        ->orWhere('nameCharge', 'like', '%' . $item . '%')
                        ->orWhere('telephoneCharge', 'like', '%' . $item . '%')
                        ->orWhere('alternativeTelephoneCharge', 'like', '%' . $item . '%');
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
            $customer = new Customer;
            $customer->name = $request->name;
            $customer->telephone = $request->telephone;
            $customer->prices = $request->prices;
            $customer->type = $request->type;
            $customer->credit = $request->credit;
            $customer->exchangeRate = $request->exchangeRate;
            if (isset($request->alternativeTelephone)) {
                $customer->alternativeTelephone = $request->alternativeTelephone;
            }
            if (isset($request->nameCharge)) {
                $customer->nameCharge = $request->nameCharge;
            }
            if (isset($request->telephoneCharge)) {
                $customer->telephoneCharge = $request->telephoneCharge;
            }
            if (isset($request->alternativeTelephoneCharge)) {
                $customer->alternativeTelephoneCharge = $request->alternativeTelephoneCharge;
            }
            $customer->basePrice = $request->price;
            $customer->multiplicater = $request->multiplier;
            $customer->save();
            AccountController::newAccount($request->name, 'Cuenta de ' . $request->name, 0, $request->user, $customer->id);
            DB::commit();
            return Response::json(['message' => 'Cliente guardado exitosamente'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        return $customer;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        DB::beginTransaction();
        try {
            $account = Account::where('idCustomer', $customer->id)->get()->first();

            $customer->name = $request->name;
            $customer->telephone = $request->telephone;
            $customer->prices = $request->prices;
            $customer->type = $request->type;
            $customer->credit = $request->credit;
            $customer->exchangeRate = $request->exchangeRate;
            if (isset($request->alternativeTelephone)) {
                $customer->alternativeTelephone = $request->alternativeTelephone;
            }
            if (isset($request->nameCharge)) {
                $customer->nameCharge = $request->nameCharge;
            }
            if (isset($request->telephoneCharge)) {
                $customer->telephoneCharge = $request->telephoneCharge;
            }
            if (isset($request->alternativeTelephoneCharge)) {
                $customer->alternativeTelephoneCharge = $request->alternativeTelephoneCharge;
            }
            $customer->basePrice = $request->price;
            $customer->multiplicater = $request->multiplier;
            $customer->save();
            AccountController::updateAccount($account->id, $request->name, 'Cuenta de ' . $request->name, 0, $request->user);
            DB::commit();
            return Response::json(['message' => 'Cliente actualizado exitosamente'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        DB::beginTransaction();
        try {
            $customer->status = false;
            $customer->save();
            DB::commit();
            return Response::json(['message' => 'Cliente eliminado exitosamente'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }
    public function getCustomer(Request $request)
    {
        $search = $request->filter;
        return Customer::select('id', 'name', 'type', 'prices', 'basePrice', 'multiplicater', 'credit', 'exchangeRate')->where('name', 'like', '%' . $search . '%')
            ->where('status', true)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }
}
