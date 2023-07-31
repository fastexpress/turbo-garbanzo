<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use App\Traits\TransactionTrait;
use \Exception;

class AccountPersonalController extends Controller
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
            return Account::orderBy('id', 'desc')
                ->where('status', true)
                ->where('isCashRegister', 2)
                ->with('userCreated:id,name', 'userUpdated:id,name')
                ->paginate($show);
        } else {
            $search = explode(" ", $request->search);
            return Account::where(function ($query) use ($search) {
                foreach ($search as $item) {
                    $query->where('id', 'like', '%' . $item . '%')
                        ->orWhere('name', 'like', '%' . $item . '%')
                        ->orWhere('bank', 'like', '%' . $item . '%')
                        ->orWhere('typeAccount', 'like', '%' . $item . '%');
                }
            })
                ->orderBy('id', 'desc')
                ->where('status', true)
                ->where('isCashRegister', 2)
                ->with('userCreated:id,name', 'userUpdated:id,name')
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
        if (count(Account::where('name', $request->name)->where('isCashRegister', false)->where('status', true)->get()) > 0)
            return Response::json(['message' => 'Nombre de cuenta ya registrado'], 400);
        DB::beginTransaction();
        try {
            $account = new Account;
            $account->name = $request->name;
            if (!empty($request->description))
                $account->description = $request->description;
            $account->amount = 0;
            $account->amountBank = 0;
            if (!empty($request->bank))
                $account->bank = $request->bank;
            $account->typeAccount = $request->typeAccount;
            $account->isCashRegister = 2;
            $account->numberAccount = $request->numberAccount;
            $account->userCreated = $request->user;
            $account->userUpdated = $request->user;
            $account->save();

            TransactionTrait::storeTransaction($request->amount, 'Se aperturo la cuenta: ' . $request->name, '+', $account->id, $request->user);

            DB::commit();
            return Response::json(['message' => 'Cuenta guardada exitosamente'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Account::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $account = Account::find($id);

        if (count(Account::where('name', $request->name)->where('id', '!=', $account->id)->where('isCashRegister', false)->where('status', true)->get()) > 0)
            return Response::json(['message' => 'Nombre de cuenta ya registrado'], 400);
        DB::beginTransaction();
        try {
            $account->name = $request->name;
            if (!empty($request->description))
                $account->description = $request->description;
            $account->bank = $request->bank;
            $account->typeAccount = $request->typeAccount;
            $account->isCashRegister = 2;
            $account->numberAccount = $request->numberAccount;
            $account->userUpdated = $request->user;
            $account->save();
            DB::commit();
            return Response::json(['message' => 'Cuenta actualizada exitosamente'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Account  $account
     * @return \Illuminate\Http\Response
     */
    // amountBank
    public function updateAmount(Request $request, $id)
    {
        $account = Account::find($id);

        DB::beginTransaction();
        try {
            $account->amountBank = $request->amountBank;
            $account->userUpdated = $request->user;
            $account->save();
            DB::commit();
            return Response::json(['message' => 'Cuenta actualizada exitosamente'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }
    public function destroy(Account $account)
    {
        if ($account->amount > 0)
            return Response::json(['message' => 'La cuenta debe estar en 0 para poder ser eliminada'], 400);
        DB::beginTransaction();
        try {
            $account->status = 0;
            $account->save();
            DB::commit();
            return Response::json(['message' => 'Cuenta eliminada exitosamente'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }
    public function getAccountPersonal(Request $request)
    {
        $search = $request->filter;
        return Account::select('id', 'name', 'amountBank', 'sign', 'bank')->where('name', 'like', '%' . $search . '%')->where('status', 1)->where('isCashRegister', 2)->orderBy('created_at', 'desc')->paginate(10);
    }
}
