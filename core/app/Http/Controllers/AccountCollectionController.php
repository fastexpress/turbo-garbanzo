<?php

namespace App\Http\Controllers;

use App\Models\AccountCollection;
use App\Models\Account;
use App\Models\PackageUPS;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use App\Traits\TransactionTrait;
use \Exception;
use Jimmyjs\ReportGenerator\Facades\PdfReportFacade as PDFReport;
use Carbon\Carbon;

class AccountCollectionController extends Controller
{
    public function get(Request $request)
    {
        $search = $request->search;
        $show = $request->show;
        if ($search == '') {
            return AccountCollection::orderBy('id', 'desc')
                ->with('account')
                ->with('ups')
                ->orderBy('collect', 'DESC')
                ->paginate($show);
        } else {
            $search = explode(" ", $request->search);
            return AccountCollection::with('account')
                ->with('ups')
                ->whereHas('ups', function ($query) use ($search) {
                    foreach ($search as $item) {
                        $query->where('package_u_p_s.id', 'like', '%' . $item . '%')
                            ->orWhere('package_u_p_s.code', 'like', '%' . $item . '%')
                            ->orWhere('package_u_p_s.serie', 'like', '%' . $item . '%')
                            ->orWhere('package_u_p_s.address', 'like', '%' . $item . '%')
                            ->orWhere('package_u_p_s.receive', 'like', '%' . $item . '%')
                            ->orWhere('package_u_p_s.sender', 'like', '%' . $item . '%')
                            ->orWhere('package_u_p_s.telephone', 'like', '%' . $item . '%')
                            ->orWhere('package_u_p_s.weight', 'like', '%' . $item . '%');
                    }
                })
                ->orderBy('collect', 'DESC')
                ->paginate($show);
        }
    }
    public function changeToConsignment(Request $request)
    {
        DB::beginTransaction();
        try {
            $account = AccountCollection::find($request->id);
            $account->keyNumber = $request->keyNumber;
            $account->nameSend = $request->nameSend;
            $account->type = "REMESA";
            $account->userUpdated = $request->user;
            $account->save();
            DB::commit();
            return Response::json(['message' => 'Cobro actualizado exitosamente'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }
    public function changeToDeposit(Request $request)
    {
        DB::beginTransaction();
        try {
            $account = AccountCollection::find($request->id);
            $account->type = "DEPÓSITO";
            $account->keyNumber = "";
            $account->nameSend = "";
            $account->userUpdated = $request->user;
            $account->save();
            DB::commit();
            return Response::json(['message' => 'Cobro actualizado exitosamente'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }
    public function charged(Request $request)
    {
        DB::beginTransaction();
        try {
            $account = AccountCollection::find($request->id);
            $account->collect = 'COBRADO';
            $account->inCollect = date('Y-m-d H:i:s');
            TransactionTrait::storeTransaction($account->amount, $account->type . ' COBRADA EN LA CUENTA ' . Account::find($account->idAccount)->name . 'DEL PAQUETE ' . $account->idPackageUPS, '+', $account->idAccount, $request->user);
            $account->save();
            DB::commit();
            return Response::json(['message' => 'Cobrado exitosamente'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }
    public function report(Request $request)
    {
        $type = $request->type;
        $account = $request->account;


        $charges = null;
        if ($type == 'TODOS') {
            // ALL REGISTRES
            $charges = AccountCollection::orderBy('id', 'desc')->where('idAccount', $account);
        } else {
            // SPECIFIC TOWN
            $charges = AccountCollection::orderBy('id', 'desc')->where('collect', $type)->where('idAccount', $account);
        }

        $title = "Reporte de cobros";
        $meta = [
            'CUENTA' => Account::find($account)->name,
            'BANCO' => Account::find($account)->bank,
            'TIPO DE CUENTA' => Account::find($account)->typeAccount,
            'MONTO ACTUAL' => "Q" . Account::find($account)->amount,
            'MONTO ALMACENADO' => "Q " . Account::find($account)->amountBank,
            'TIPO' => $type,
        ];
        $columns = [
            'Tipo' => 'type',
            'Número clave' => 'keyNumber',
            'Banco' => 'bank',
            'Envía' => 'nameSend',
            'Tipo cambio' => 'exchangeRate',
            'Monto en dólares' => 'amountDollar',
            'Remesa' => function ($result) {
                return ($result->type == "REMESA") ? $result->amount : 0;
            },
            'Depósito' => function ($result) {
                return ($result->type == "DEPÓSITO") ? $result->amount : 0;
            },
            'Cliente' => function ($result) {
                $package = PackageUPS::find($result->idPackageUPS);
                return "{$package->receive} / {$package->code} / {$package->id}";
            },
            'Estado' => 'collect',
            'Observación' => 'created_at',
            'Fecha creación' => 'created_at',
        ];

        return PDFReport::of($title, $meta, $charges, $columns)
            ->editColumn('Tipo cambio', [
                'displayAs' => function ($result) {
                    return "Q " . $result->exchangeRate;
                },
                'class' => 'left'
            ])
            ->editColumn('Monto en dólares', [
                'displayAs' => function ($result) {
                    return "$ " . $result->amountDollar;
                },
                'class' => 'left'
            ])
            ->editColumn('Remesa', [
                'displayAs' => function ($result) {
                    return ($result->type == "REMESA") ? $this->money($result->amount, 'Q') : "";
                },
                'class' => 'right'
            ])
            ->editColumn('Depósito', [
                'displayAs' => function ($result) {
                    return ($result->type == "DEPÓSITO") ? $this->money($result->amount, 'Q') : "";
                },
                'class' => 'right'
            ])
            ->editColumn('Observación', [
                'displayAs' => function ($result) {
                    return PackageUPS::find($result->idPackageUPS)->observation;
                },
                'class' => 'left'
            ])
            ->editColumn('Fecha creación', [
                'displayAs' => function ($result) {
                    return Carbon::parse($result->created_at)->format('d/m/Y');
                },
                'class' => 'left'
            ])
            ->editColumn('Cliente', [
                'class' => 'center'
            ])
            ->showTotal([ // Used to sum all value on specified column on the last table (except using groupBy method). 'point' is a type for displaying total with a thousand separator
                'Remesa' => 'Q',
                'Depósito' => 'Q' // if you want to show dollar sign ($) then use 'Total Balance' => '$'
            ])
            ->setOrientation('landscape')
            ->stream();
    }
    public function money($amount, $sign)
    {
        return $sign . " " . number_format($amount, 2);
    }
}
