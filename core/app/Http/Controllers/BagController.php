<?php

namespace App\Http\Controllers;

use App\Models\Bag;
use App\Models\User;
use App\Models\Setting;
use App\Models\OfficeUSA;
use App\Models\Baler;
use App\Models\Package;
use App\Models\HeaderPackage;
use App\Models\ShipmentUSA;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\Element\Table;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use Jimmyjs\ReportGenerator\Facades\PdfReportFacade as PDFReport;
use Jimmyjs\ReportGenerator\Facades\ExcelReportFacade as ExcelReport;
use \Exception;

// \PhpOffice\PhpWord\TemplateProcessor
class BagController extends Controller
{
    public function getManifestBag(Request $request)
    {
        $date = $request->date;
        $user = $request->user;
        $city = $request->city;

        $document = public_path() . "/document/Bag.docx";
        $templateProcessor = new TemplateProcessor($document);

        $bags = Bag::join('shipment_u_s_a_s', 'shipment_u_s_a_s.id', 'bags.idShipment')
            ->select('bags.id', 'bags.idShipment')
            ->whereDate('shipment_u_s_a_s.date', $date)
            ->with('packages')
            ->where('userTraveler', $user)
            ->where('shipment_u_s_a_s.city', $city)
            ->get();
        $date = null;
        if (count($bags) > 0) {
            $date = Carbon::createFromDate(ShipmentUSA::find($bags[0]->idShipment)->date)->format('d/m/Y');
        } else {
            $date = Carbon::now()->format('d/m/Y');
        }
        $traveler = User::find($user)->name;
        $passport = User::find($user)->passport;
        $office = Setting::find(4)->value;
        $address = OfficeUSA::where('name', $city)->get()->first()->addressManifest;
        $templateProcessor->cloneBlock('bags', count($bags), true, true);
        foreach ($bags as $key => $bag) {
            $newKey = $key + 1;
            $templateProcessor->setValue("traveler#{$newKey}", mb_strtoupper($traveler));
            $templateProcessor->setValue("passport#{$newKey}", $passport);
            $templateProcessor->setValue("date#{$newKey}", $date);
            $templateProcessor->setValue("bag#{$newKey}", $newKey);
            $templateProcessor->setValue("number_bag#{$newKey}", $newKey);
            $templateProcessor->setValue("total#{$newKey}", count($bags));
            $templateProcessor->setValue("guatemala_offices#{$newKey}", $office);
            $templateProcessor->setValue("city#{$newKey}", mb_strtoupper($city));
            $templateProcessor->setValue("address_city#{$newKey}", mb_strtoupper($address));

            $fancyTableStyle = [
                'borderSize'  => 6,
                'borderColor' => '000000',
                'cellMargin'  => 80,
                'alignment'   => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER,
                'layout'      => \PhpOffice\PhpWord\Style\Table::LAYOUT_FIXED,
            ];
            $table = new Table($fancyTableStyle);
            // HEADER TABLE
            $table->addRow();
            $table->addCell(1000)->addText("CÓDIGO", array(
                'size' => 12,
                'bold' => true,
            ));
            $table->addCell(2000)->addText("REMITENTE", array(
                'size' => 12,
                'bold' => true,
            ));
            $table->addCell(2000)->addText("DESTINATARIO", array(
                'size' => 12,
                'bold' => true,
            ));
            $table->addCell(3600)->addText("DESCRIPCIÓN", array(
                'size' => 12,
                'bold' => true,
            ));
            $table->addCell(1500)->addText("PESO", array(
                'size' => 12,
                'bold' => true,
            ));
            // END TABLE
            foreach ($bag->packages as $package) {
                $table->addRow();
                $table->addCell(1000)->addText(Baler::find($package->idBaler)->code . "-" . $package->correlative);
                $table->addCell(2000)->addText($package->send);
                $table->addCell(2000)->addText(HeaderPackage::find($package->idHeaderPackage)->receive);
                $table->addCell(3600)->addText($package->content_en);
                $table->addCell(1500)->addText($package->weight . " lbs");
            }
            $templateProcessor->setComplexBlock("table#{$newKey}", $table);
        }
        $pathToSave =  public_path() . "/document/generated";
        $fileName = "MANIFIESTO_PARA_" . mb_strtoupper($city) . "_DEL_PASSAPORTE_" . $passport . ".docx";
        $templateProcessor->saveAs($pathToSave . "/" . $fileName);

        $headers = array(
            'Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        );

        return Response::download($pathToSave . "/" . $fileName, $fileName, $headers);
    }
    public function getNumberBag(Request $request)
    {
        $date = $request->date;
        $city = $request->city;

        $title = "Viaje a {$city}";

        $meta = [
            'Fecha del viaje' => Carbon::createFromDate($date)->format('d/m/Y'),
        ];

        $columns = [
            'Telefono' => 'telephone',
        ];
        $packages = HeaderPackage::join('packages as p', 'header_packages.id', 'p.idHeaderPackage')
            ->join('balers as bl', 'bl.id', 'p.idBaler')
            ->join('bags as b', 'b.id', 'p.idBag')
            ->join('shipment_u_s_a_s as s', 's.id', 'b.idShipment')
            ->select('header_packages.id', 'header_packages.telephone')
            ->where('s.city', $city)
            ->where('s.date', $date)
            ->groupBy('header_packages.id','header_packages.telephone')
            ->orderBy('header_packages.id', 'ASC');

        return ExcelReport::of($title, $meta, $packages, $columns)
            ->editColumn('Telefono', [
                'displayAs' => function ($result) {
                    if (strlen($result->telephone) === 9)
                        return "502" . str_replace("-", "", $result->telephone);
                    else
                        return "1" . str_replace("-", "", $result->telephone);
                },
                'class' => 'right',
            ])
            ->download($city . "_" . Carbon::now()->format('d_m_Y_h:mm'));
    }
    public function getBags(Request $request)
    {
        $date = $request->date;
        $city = $request->city;

        return Bag::join('shipment_u_s_a_s', 'shipment_u_s_a_s.id', 'bags.idShipment')
            ->select('bags.id', 'bags.bag', 'bags.status', 'bags.capacity', 'userTraveler')
            ->whereDate('shipment_u_s_a_s.date', $date)
            ->with('userTraveler')
            ->where('shipment_u_s_a_s.city', $city)
            ->get();
    }
    public function changeStatus(Request $request)
    {
        DB::beginTransaction();
        try {
            $bag = Bag::find($request->id);
            $bag->status = $request->status;
            $bag->save();
            $packages = Bag::where('id', $request->id)->get()->first()->packages;
            foreach ($packages as $package) {
                $findPackage = Package::find($package->id);
                $findPackage->status = $request->status;
                $findPackage->save();

                $headerPackage = HeaderPackage::find($findPackage->idHeaderPackage);
                $headerPackage->status = $request->status;
                $headerPackage->save();
            }
            DB::commit();
            return Response::json(['message' => $request->status == 1 ? 'Maleta en USA' : 'Maleta anulada'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }
    private function toMoney($val, $symbol = '$', $r = 2)
    {
        return $symbol . ($val < 0 ? '-' : '') . number_format(abs($val), $r);
    }
    public function getList(Request $request)
    {
        $date = $request->date;
        $city = $request->city;

        $title = "Viaje a {$city}";
        $total = HeaderPackage::join('packages as p', 'header_packages.id', 'p.idHeaderPackage')
            ->join('balers as bl', 'bl.id', 'p.idBaler')
            ->join('bags as b', 'b.id', 'p.idBag')
            ->join('shipment_u_s_a_s as s', 's.id', 'b.idShipment')
            ->select(DB::raw('sum(p.subtotal) as Total'), DB::raw('sum(p.weight) as TotalWeight'))
            ->where('s.city', $city)
            ->where('s.date', $date)
            ->get()
            ->first();
        $meta = [
            'Fecha del viaje' => Carbon::createFromDate($date)->format('d/m/Y'),
            'Total a cobrar' => $this->toMoney($total->Total),
            'Total de peso' => $total->TotalWeight . " lbs",
        ];
        $columns = [
            'Código' => 'code',
            'Código Rastreo' => 'id',
            'Remitente' => 'send',
            'Destinario' => 'receive',
            'Descripción' => 'content',
            'Telefono' => 'telephone',
            'Peso' => 'weight',
            'Costo' => 'cost',
            'Subtotal' => 'subtotal',
            'Observación' => 'observation',
        ];
        $packages = HeaderPackage::join('packages as p', 'header_packages.id', 'p.idHeaderPackage')
            ->join('balers as bl', 'bl.id', 'p.idBaler')
            ->join('bags as b', 'b.id', 'p.idBag')
            ->join('shipment_u_s_a_s as s', 's.id', 'b.idShipment')
            ->select('header_packages.id', 'header_packages.receive', 'p.send', 'p.content', 'p.weight', 'header_packages.telephone', 'header_packages.alternativeTelephone', 'p.cost', 'p.subtotal', 'p.observation', 'bl.code', 'p.correlative')
            ->where('s.city', $city)
            ->where('s.date', $date)
            ->orderBy('header_packages.id', 'ASC');

        return PDFReport::of($title, $meta, $packages, $columns)
            ->editColumn('Código', [
                'displayAs' => function ($result) {
                    return $result->code . $result->correlative;
                },
                'class' => 'left',
            ])
            ->editColumn('Subtotal', [
                'displayAs' => function ($result) {
                    return $result->subtotal;
                },
                'class' => 'right',
            ])
            ->editColumn('Peso', [
                'displayAs' => function ($result) {
                    return $result->weight;
                },
                'class' => 'right',
            ])
            ->editColumn('Costo', [
                'displayAs' => function ($result) {
                    return $result->cost;
                },
                'class' => 'right',
            ])
            ->groupBy('Código Rastreo') // Show total of value on specific group. Used with showTotal() enabled.
            ->showTotal([
                'Subtotal' => 'point',
                'Peso' => 'point',
                'Costo' => 'point'
            ])
            ->setOrientation('landscape')
            ->stream();
    }
}
