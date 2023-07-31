<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\PackageUPS;
use App\Models\PackageUPSCustomer;
use App\Models\AccountCollection;
use alhimik1986\PhpExcelTemplator\PhpExcelTemplator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;


class ExcelReportController extends Controller
{
    public function reportTypical(Request $request)
    {
        $typical = Customer::find($request->customer);
        $dateInit = $request->dateInit;
        $dateEnd = $request->dateEnd;
        $packages = PackageUPS::with('account')
            ->withCount('boxes')
            ->with('customer')
            ->with('baler')
            ->with('userCreateds')
            ->with('userUpdateds')
            ->whereHas('customer', function ($query) use ($typical) {
                $query->where('customers.id', $typical->id);
            })
            ->whereDate('inOffice', '>=', $dateInit)
            ->whereDate('inOffice', '<=', $dateEnd)
            ->orderBy('inOffice', 'ASC')
            ->get();
        // ->whereDate('inOffice', $date)->get();
        $code = [];
        $receive = [];
        $telephone = [];
        $weight = [];
        $dateUPS = [];
        $dateEstimated = [];
        $totalPayment = [];
        $totalQuetzal = [];
        $type = [];


        foreach ($packages as $package) {
            array_push($code, $package->code);
            array_push($receive, $package->receive);
            array_push($telephone, $package->telephone);

            $customer = $package->customer->where('idCustomer', $typical->id)->first();

            array_push($weight, $customer->weight);
            array_push($dateUPS, Carbon::createFromTimeString($package->inOffice)->format('d-m-Y'));
            array_push($dateEstimated, Carbon::createFromTimeString($package->inOffice)->addDay()->format('d-m-Y'));
            array_push($totalPayment, $customer->subtotal);
            array_push($totalQuetzal, $customer->subtotal * $typical->exchangeRate);

            $customer = PackageUPSCustomer::with('accounts')->where('idPackageUPS', $package->id)->where('idCustomer', $typical->id)->get()->first();
            if ($customer->typePaymentTypical == 0) {
                array_push($type, "COBRO A CLIENTE");
            } else {
                array_push($type, "COBRO A {$typical->name}");
            }
        }
        // http://shipments.test/api/report/typical?customer=1&date=2022-06-11
        // return $packages;
        $document = public_path() . "/document/ReportTypical.xlsx";
        $params = [
            '{typical}' => mb_strtoupper($typical->name),
            '{dateInit}' => Carbon::createFromDate($dateInit)->format('d-m-Y'),
            '{dateEnd}' => Carbon::createFromDate($dateEnd)->format('d-m-Y'),
            '{exchangeRate}' => $typical->exchangeRate,
            '[code]' => $code,
            '[receive]' => $receive,
            '[telephone]' => $telephone,
            '[weight]' => $weight,
            '[dateUPS]' => $dateUPS,
            '[dateEstimated]' => $dateEstimated,
            '[totalPayment]' => $totalPayment,
            '[totalQuetzal]' => $totalQuetzal,
            '[type]' => $type,

        ];
        $pathToSave =  public_path() . "/document/generated";
        $fileName = "REPORTE_" . mb_strtoupper($typical->name) . "_" . strtotime('now') . ".xlsx";
        PhpExcelTemplator::saveToFile($document, $pathToSave . "/" . $fileName, $params);

        $headers = array(
            'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        );
        return Response::download($pathToSave . "/" . $fileName, $fileName, $headers);
    }
    public function reportNotTypical(Request $request)
    {
        $typical = Customer::find($request->customer);
        $dateInit = $request->dateInit;
        $dateEnd = $request->dateEnd;
        $packages = PackageUPS::with('account')
            ->withCount('boxes')
            ->with('customer')
            ->with('baler')
            ->with('userCreateds')
            ->with('userUpdateds')
            ->whereHas('customer', function ($query) use ($typical) {
                $query->where('customers.id', $typical->id);
            })
            ->whereDate('inOffice', '>=', $dateInit)
            ->whereDate('inOffice', '<=', $dateEnd)
            ->orderBy('inOffice', 'ASC')
            ->get();
        // ->whereDate('inOffice', $date)->get();
        $code = [];
        $receive = [];
        $telephone = [];
        $weight = [];
        $dateUPS = [];
        $dateEstimated = [];
        $totalPayment = [];
        $totalQuetzal = [];
        $type = [];
        $account = [];
        $box = [];
        $kg = [];
        $status = [];
        $baler = [];
        $userCreated = [];
        $userUpdated = [];

        foreach ($packages as $package) {
            array_push($code, $package->code);
            array_push($receive, $package->receive);
            array_push($telephone, $package->telephone);

            $customer = $package->customer->where('idCustomer', $typical->id)->first();

            array_push($weight, $customer->weight);
            array_push($dateUPS, Carbon::createFromTimeString($package->inOffice)->format('d-m-Y'));
            array_push($dateEstimated, Carbon::createFromTimeString($package->inOffice)->addDay()->format('d-m-Y'));
            array_push($totalPayment, $customer->subtotal);
            array_push($totalQuetzal, $customer->subtotal * $typical->exchangeRate);

            $customer = PackageUPSCustomer::with('accounts')->where('idPackageUPS', $package->id)->where('idCustomer', $typical->id)->get()->first();
            if ($customer->typePaymentTypical == 0) {
                $charge = AccountCollection::with('account')->where('idPackageUPS', $package->id)->where('idAccount', $customer->idAccountPersonal)->get()->first();
                array_push($type, "COBRO A CLIENTE");
                if (empty($charge))
                    array_push($account, "PENDIENTE");
                else
                    array_push($account, "{$charge->account->name}/{$charge->account->bank}/{$charge->type}/{$charge->collect}");
            } else {
                array_push($type, "COBRO A {$typical->name}");
                array_push($account, "{$typical->name}");
            }
            array_push($box, $package->boxes_count);
            array_push($kg, $package->totalKg);

            array_push($status, $package->status == 'DELIVERED' ? 'ENTREGADO' : 'PENDIENTE');
            array_push($baler, $package->baler->first()->name);
            array_push($userCreated, $package->userCreateds->first()->name);
            array_push($userUpdated, $package->userUpdateds->first()->name);
            // AccountCollection
        }
        // http://shipments.test/api/report/typical?customer=1&date=2022-06-11
        // return $packages;
        $document = public_path() . "/document/ReportNotTypical.xlsx";
        $params = [
            '{typical}' => mb_strtoupper($typical->name),
            '{dateInit}' => Carbon::createFromDate($dateInit)->format('d-m-Y'),
            '{dateEnd}' => Carbon::createFromDate($dateEnd)->format('d-m-Y'),
            '{exchangeRate}' => $typical->exchangeRate,
            '[code]' => $code,
            '[receive]' => $receive,
            '[telephone]' => $telephone,
            '[weight]' => $weight,
            '[dateUPS]' => $dateUPS,
            '[dateEstimated]' => $dateEstimated,
            '[totalPayment]' => $totalPayment,
            '[totalQuetzal]' => $totalQuetzal,
            '[type]' => $type,
            '[account]' => $account,
            '[box]' => $box,
            '[kg]' => $kg,
            '[status]' => $status,
            '[balers]' => $baler,
            '[userCreated]' => $userCreated,
            '[userUpdated]' => $userUpdated,
        ];
        $pathToSave =  public_path() . "/document/generated";
        $fileName = "REPORTE_" . mb_strtoupper($typical->name) . "_" . strtotime('now') . ".xlsx";
        PhpExcelTemplator::saveToFile($document, $pathToSave . "/" . $fileName, $params);

        $headers = array(
            'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        );
        return Response::download($pathToSave . "/" . $fileName, $fileName, $headers);
    }
    public function reportUPS(Request $request)
    {
        $dateInit = $request->dateInit;
        $dateEnd = $request->dateEnd;
        $packages = PackageUPS::with('account')
            ->withCount('boxes')
            ->with('customer')
            ->with('baler')
            ->with('userCreateds')
            ->with('userUpdateds')
            ->whereDate('inCode', '>=', $dateInit)
            ->whereDate('inCode', '<=', $dateEnd)
            ->whereNotNull('codeUPS')
            ->orderBy('code', 'ASC')
            ->get();
        // ->whereDate('inOffice', $date)->get();
        $code = [];
        $ups = [];
        $receive = [];
        $telephone = [];
        $weight = [];
        $dateUPS = [];
        $dateEstimated = [];
        $totalPayment = [];
        $totalQuetzal = [];
        $customer = [];
        $box = [];
        $kg = [];
        $status = [];
        $baler = [];
        $userCreated = [];
        $userUpdated = [];

        foreach ($packages as $package) {
            array_push($code, $package->code);
            array_push($ups, $package->codeUPS);
            array_push($receive, $package->receive);
            array_push($telephone, $package->telephone);
            array_push($dateUPS, Carbon::createFromTimeString($package->inOffice)->format('d-m-Y'));
            array_push($dateEstimated, Carbon::createFromTimeString($package->inOffice)->addDay()->format('d-m-Y'));
            array_push($box, $package->boxes_count);
            array_push($kg, $package->totalKg);
            array_push($status, $package->status == 'DELIVERED' ? 'ENTREGADO' : 'PENDIENTE');
            array_push($baler, $package->baler->first()->name);
            array_push($userCreated, $package->userCreateds->first()->name);
            array_push($userUpdated, $package->userUpdateds->first()->name);

            $customers = PackageUPSCustomer::with('customers')->where('idPackageUPS', $package->id)->get();
            $weightC = 0;
            $totalPaymentC = 0;
            $totalQuetzalC = 0;
            $customerNameC = "";
            foreach ($customers as $c) {
                $weightC += $c->weight;
                $totalPaymentC += $c->subtotal;
                $customerNameC = $customerNameC . $c->customers->first()->name . ", ";
            }
            if (count($customers) < 1) {
                $weightC= $package->weight;
                $totalPaymentC = $package->totalPayment;
                $customerNameC = "OFICINA";
            }
            $totalQuetzalC = $totalPaymentC * 7.5;
            array_push($weight, $weightC);
            array_push($totalPayment, $totalPaymentC);
            array_push($totalQuetzal, $totalQuetzalC);
            array_push($customer, $customerNameC);

            // AccountCollection
        }
        // http://shipments.test/api/report/typical?customer=1&date=2022-06-11
        // return $packages;
        $document = public_path() . "/document/ReportAllTypical.xlsx";
        $params = [
            '{typical}' => "REPORTE DE PAQUETES UPS",
            '{dateInit}' => Carbon::createFromDate($dateInit)->format('d-m-Y'),
            '{dateEnd}' => Carbon::createFromDate($dateEnd)->format('d-m-Y'),
            '{exchangeRate}' => 7.5,
            '[code]' => $code,
            '[ups]' => $ups,
            '[receive]' => $receive,
            '[telephone]' => $telephone,
            '[weight]' => $weight,
            '[kg]' => $kg,
            '[dateUPS]' => $dateUPS,
            '[dateEstimated]' => $dateEstimated,
            '[totalPayment]' => $totalPayment,
            '[totalQuetzal]' => $totalQuetzal,
            '[customer]' => $customer,
            '[box]' => $box,
            '[kg]' => $kg,
            '[status]' => $status,
            '[balers]' => $baler,
            '[userCreated]' => $userCreated,
            '[userUpdated]' => $userUpdated,
        ];
        $pathToSave =  public_path() . "/document/generated";
        $fileName = "REPORTE_UPS_" . strtotime('now') . ".xlsx";
        PhpExcelTemplator::saveToFile($document, $pathToSave . "/" . $fileName, $params);

        $headers = array(
            'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        );
        return Response::download($pathToSave . "/" . $fileName, $fileName, $headers);
    }
}
