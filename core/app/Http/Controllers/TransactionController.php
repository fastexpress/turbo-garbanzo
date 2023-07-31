<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\TransactionTrait;
use App\Models\Transaction;
// use App\Models\Account;
// use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use \Exception;
// use App\Notifications\TelegramNotification;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $show = $request->show;

        if ($search == '') {
            return Transaction::join('accounts', 'accounts.id', '=', 'transactions.idaccount')
                ->join('users', 'users.id', '=', 'transactions.userCreated')
                ->select(
                    'transactions.id',
                    'accounts.name',
                    'transactions.description',
                    'transactions.amount',
                    'accounts.sign',
                    'transactions.status',
                    'transactions.type',
                    'users.name as user_created',
                    'transactions.created_at',
                    'transactions.reference'
                )
                ->orderBy('transactions.created_at', 'desc')
                ->paginate($show);
        } else {
            $search = explode(" ", $request->search);
            return Transaction::select(
                'transactions.id',
                'accounts.name',
                'transactions.description',
                'transactions.amount',
                'accounts.sign',
                'transactions.status',
                'transactions.type',
                'users.name as user_created',
                'transactions.created_at',
                'transactions.reference'
            )
                ->where(function ($query) use ($search) {
                    foreach ($search as $item) {
                        $query->where('transactions.id', 'like', '%' . $item . '%')
                            ->orWhere('accounts.name', 'like', '%' . $item . '%')
                            ->orWhere('transactions.description', 'like', '%' . $item . '%')
                            ->orWhere('transactions.reference', 'like', '%' . $item . '%');
                    }
                })
                ->join('accounts', 'accounts.id', '=', 'transactions.idaccount')
                ->join('users', 'users.id', '=', 'transactions.userCreated')
                ->orderBy('transactions.created_at', 'desc')
                ->paginate($show);
        }
    }
    public function movement(Request $request)
    {
        DB::beginTransaction();
        try {
            // ACCOUNT OUT
            TransactionTrait::storeTransaction($request->amountOut, 'Se realizo un retiro de la cuenta: ' . $request->nameOut, '-', $request->idOut, $request->user, $request->reference);
            // ACCOUT IN
            TransactionTrait::storeTransaction($request->amountIn, 'Se realizo un ingreso a la cuenta: ' . $request->nameIn, '+', $request->idIn, $request->user, $request->reference);

            // NOTIFICATE ALL USERS
            // Notificate to User InCharge
            // $In = Account::find($request->idIn);
            // $Out = Account::find($request->idOut);

            // $usersIn = User::where('idRol', 1)->whereNotNull('telegramId')->get()->pluck('id');

            // if (isset($In->inCharge)) {
            //     $userIn = User::where('id', $In->inCharge)->whereNotNull('telegramId')->get();
            //     if (count($userIn) > 0) {
            //         array_push($usersIn, $userIn->first()->id);
            //     }
            // }
            // foreach ($usersIn as $user) {
            //     $body['message'] = "SE REALIZO UN INGRESO A LA CUENTA: " . strtoupper($request->nameIn)  . " POR: " . strtoupper($request->reference);
            //     $user->notify(new TelegramNotification($body));
            // }

            // $usersOut = User::where('idRol', 1)->whereNotNull('telegramId')->get();
            // if (isset($Out->inCharge)) {
            //     $userOut = User::where('id', $Out->inCharge)->whereNotNull('telegramId')->get();
            //     if (count($userOut) > 0)
            //         array_push($usersOut, $userOut[0]);
            // }
            // foreach ($usersOut as $user) {
            //     $body['message'] = "SE REALIZO UN RETIRO A LA CUENTA: " . strtoupper($request->nameOut)  . " POR: " . strtoupper($request->reference);
            //     $user->notify(new TelegramNotification($body));
            // }
            // END NOTIFICATE ALL USERS

            DB::commit();
            return Response::json(['message' => 'Movimiento realizado'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }
    public function account(Request $request)
    {
        DB::beginTransaction();
        try {
            $description = "";
            if ($request->type == "-")
                $description = "Se realizo un retiro de la cuenta: " . $request->name;
            else
                $description = "Se realizo un ingreso a la cuenta: " . $request->name;

            TransactionTrait::storeTransaction($request->amount, $description, $request->type, $request->id, $request->user, $request->reference);

            DB::commit();
            return Response::json(['message' => 'Movimiento realizado'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }
}
