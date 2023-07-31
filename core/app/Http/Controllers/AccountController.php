<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use App\Traits\TransactionTrait;
use \Exception;
use Jimmyjs\ReportGenerator\Facades\PdfReportFacade as PDFReport;
use Carbon\Carbon;

class AccountController extends Controller
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
                ->where('isCashRegister', false)
                ->with('userCreated:id,name', 'userUpdated:id,name')
                ->paginate($show);
        } else {
            $search = explode(" ", $request->search);
            return Account::where(function ($query) use ($search) {
                foreach ($search as $item) {
                    $query->where('id', 'like', '%' . $item . '%')
                        ->orWhere('name', 'like', '%' . $item . '%')
                        ->orWhere('bank', 'like', '%' . $item . '%');
                }
            })
                ->orderBy('id', 'desc')
                ->where('status', true)
                ->where('isCashRegister', false)
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
            if (!empty($request->bank))
                $account->bank = $request->bank;

            $account->isCashRegister = false;

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
    public function show(Account $account)
    {
        return $account;
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
            if (!empty($request->bank))
                $account->bank = $request->bank;

            $account->isCashRegister = false;

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
    static function newAccount($name, $description, $amount, $user, $customer)
    {
        if (count(Account::where('name', $name)->where('isCashRegister', false)->where('status', true)->get()) > 0)
            return Response::json(['message' => 'Nombre de cuenta ya registrado'], 400);
        DB::beginTransaction();
        try {
            $account = new Account;
            $account->name = $name;
            $account->description = $description;
            $account->amount = 0;
            $account->isCashRegister = false;
            $account->userCreated = $user;
            $account->userUpdated = $user;
            $account->idCustomer = $customer;
            $account->save();

            TransactionTrait::storeTransaction($amount, 'Se aperturo la cuenta: ' . $name, '+', $account->id, $user);

            DB::commit();
            return;
        } catch (Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }
    static function updateAccount($id, $name, $description, $user)
    {
        $account = Account::find($id);

        if (count(Account::where('name', $name)->where('id', '!=', $id)->where('isCashRegister', false)->where('status', true)->get()) > 0)
            return Response::json(['message' => 'Nombre de cuenta ya registrado'], 400);
        DB::beginTransaction();
        try {
            $account->name = $name;
            $account->description = $description;
            $account->userUpdated = $user;
            $account->save();
            DB::commit();
            return;
        } catch (Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }
    public function getAccount(Request $request)
    {
        $search = $request->filter;
        $type = $request->type;
        return Account::select('id', 'name')->where('name', 'like', '%' . $search . '%')->where('status', 1)->where('isCashRegister', $type)->orderBy('created_at', 'desc')->paginate(10);
    }
    public function getAllAccount(Request $request)
    {
        $search = $request->filter;
        return Account::select('id', 'name', 'sign', 'bank')->where('name', 'like', '%' . $search . '%')->where('status', 1)->orderBy('created_at', 'desc')->paginate(10);
    }
    public function report(Request $request)
    {
        $beginDate = $request->beginDate;
        $endDate = $request->endDate;
        $account = Account::find($request->account);
        $title = "Reporte de la cuenta: " . $account->name;
        $meta = [
            'Tipo de cuenta' => ($account->isCashRegister == 1 ? 'Caja' : ($account->isCashRegister == 2 ? 'Cuenta personal' : 'Normal')),
            'Monto' =>  $this->money($account->amount, $account->sign),
            'Registros del ' => Carbon::parse($beginDate)->format('d/m/Y') . ' al ' . Carbon::parse($endDate)->format('d/m/Y'),
        ];
        $queryBuilder = Transaction::select('id', 'description', 'reference', 'amount', 'type', 'created_at')
            ->where('idaccount', $account->id)
            ->whereDate('created_at', '>=', $beginDate)
            ->whereDate('created_at', '<=', $endDate)
            ->orderBy('type', 'ASC')
            ->orderBy('created_at', 'ASC');

        $columns = [
            'Descripción' => 'description',
            'Referencia' => 'reference',
            'Entrada' => function ($result) {
                return ($result->type == 1) ? 0 : $result->amount;
            },
            'Salida' => function ($result) {
                return ($result->type == 1) ? $result->amount : 0;
            },
            'Fecha de movimiento' => 'created_at',
        ];
        return PDFReport::of($title, $meta, $queryBuilder, $columns)
            ->editColumn('Entrada', [
                'displayAs' => function ($result) use ($account) {
                    return ($result->type == 1) ? "" : $this->money($result->amount, $account->sign);
                },
                'class' => 'right'
            ])
            ->editColumn('Salida', [
                'displayAs' => function ($result)  use ($account) {
                    return ($result->type == 1) ? $this->money($result->amount, $account->sign) : "";
                },
                'class' => 'right'
            ])
            ->editColumn('Fecha de movimiento', [
                'displayAs' => function ($result) {
                    return Carbon::parse($result->created_at)->format('d/m/Y');
                },
                'class' => 'right'
            ])
            ->showTotal([
                'Entrada' => 'Q',
                'Salida' => 'Q',
            ])
            ->setOrientation('landscape')
            ->stream();
    }
    public function money($amount, $sign)
    {
        return $sign . " " . number_format($amount, 2);
    }
}
