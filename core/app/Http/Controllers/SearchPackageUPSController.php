<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PackageUPS;
use App\Models\PackageUPSCustomer;
use App\Models\BoxUPS;
use App\Notifications\TelegramNotification;
use App\Models\ContentUPS;
use App\Models\AccountCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use App\Models\User;
use Carbon\Carbon;
use \Exception;
use PhpOffice\PhpWord\TemplateProcessor;
use Jimmyjs\ReportGenerator\Facades\ExcelReportFacade as ExcelReport;
use PhpOffice\PhpWord\Element\Table;

class SearchPackageUPSController extends Controller
{
    public function office(Request $request)
    {
        $search = $request->search;
        $customer = $request->customer;
        $show = $request->show;
        // **CALL ALL REGISTRES
        if ($customer == -1) {
            if (is_null($search)) {
                return PackageUPS::with('boxes')
                    ->with('customer')
                    ->with('contents')
                    ->with('account')
                    ->where('status', 'OFICINA')
                    ->orderBy('id', 'DESC')
                    ->paginate($show);
            } else {
                $search = explode(" ", $request->search);
                return PackageUPS::with('boxes')
                    ->with('customer')
                    ->with('contents')
                    ->with('account')
                    ->where('status', 'OFICINA')
                    ->where(function ($query) use ($search) {
                        foreach ($search as $item) {
                            $query->where('id', 'like', '%' . $item . '%')
                                ->orWhere('serie', 'like', '%' . $item . '%')
                                ->orWhere('address', 'like', '%' . $item . '%')
                                ->orWhere('receive', 'like', '%' . $item . '%')
                                ->orWhere('sender', 'like', '%' . $item . '%')
                                ->orWhere('telephone', 'like', '%' . $item . '%')
                                ->orWhere('weight', 'like', '%' . $item . '%');
                        }
                    })
                    ->orderBy('id', 'DESC')
                    ->paginate($show);
            }
        }
        // **CALL OFFICES
        else if ($customer == 0) {
            if (is_null($search)) {
                return PackageUPS::with('boxes')
                    ->with('customer')
                    ->with('contents')
                    ->with('account')
                    ->where('status', 'OFICINA')
                    ->where('typical', 0)
                    ->orderBy('code', 'DESC')
                    ->paginate($show);
            } else {
                $search = explode(" ", $request->search);
                return PackageUPS::with('boxes')
                    ->with('customer')
                    ->with('contents')
                    ->with('account')
                    ->where('typical', 0)
                    ->where('status', 'OFICINA')
                    ->where(function ($query) use ($search) {
                        foreach ($search as $item) {
                            $query->where('id', 'like', '%' . $item . '%')
                                ->orWhere('serie', 'like', '%' . $item . '%')
                                ->orWhere('address', 'like', '%' . $item . '%')
                                ->orWhere('receive', 'like', '%' . $item . '%')
                                ->orWhere('sender', 'like', '%' . $item . '%')
                                ->orWhere('telephone', 'like', '%' . $item . '%')
                                ->orWhere('weight', 'like', '%' . $item . '%');
                        }
                    })
                    ->orderBy('code', 'DESC')
                    ->paginate($show);
            }
        } else {
            if (is_null($search)) {
                return PackageUPS::with('boxes')
                    ->with('customer')
                    ->with('contents')
                    ->with('account')
                    ->where('status', 'OFICINA')
                    ->orderBy('code', 'DESC')
                    ->whereHas('customer', function ($query) use ($customer) {
                        $query->where('customers.id', $customer);
                    })
                    ->paginate($show);
            } else {
                $search = explode(" ", $request->search);
                return PackageUPS::with('boxes')
                    ->with('customer')
                    ->with('contents')
                    ->with('account')
                    ->where('status', 'OFICINA')
                    ->whereHas('customer', function ($query) use ($customer) {
                        $query->where('customers.id', $customer);
                    })
                    ->where(function ($query) use ($search) {
                        foreach ($search as $item) {
                            $query->where('id', 'like', '%' . $item . '%')
                                ->orWhere('serie', 'like', '%' . $item . '%')
                                ->orWhere('address', 'like', '%' . $item . '%')
                                ->orWhere('receive', 'like', '%' . $item . '%')
                                ->orWhere('sender', 'like', '%' . $item . '%')
                                ->orWhere('telephone', 'like', '%' . $item . '%')
                                ->orWhere('weight', 'like', '%' . $item . '%');
                        }
                    })
                    ->orderBy('code', 'DESC')
                    ->paginate($show);
            }
        }
    }
    public function address(Request $request)
    {
        DB::beginTransaction();
        try {
            $package = PackageUPS::find($request->id);
            $package->address = $request->address;
            $package->postalCode = $request->postalCode;
            $package->state = $request->state;
            $package->city = $request->city;
            $package->inAddress = date('Y-m-d H:i:s');
            $package->save();
            $this->validateToCode($request->id);
            DB::commit();
            return Response::json(['message' => 'Dirección válidada exitosamente'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }
    public function content(Request $request)
    {
        DB::beginTransaction();
        try {
            $package = PackageUPS::find($request->id);
            $package->totalKg = $request->totalKg;
            $package->totalContent = $request->totalContent;
            $boxeDelete = BoxUPS::where('idPackage', $request->id)->get();
            foreach ($boxeDelete as $boxe) {
                $boxe->delete();
            }
            $boxes = json_decode($request->boxes, true);
            foreach ($boxes as $box) {
                $newBox = new BoxUPS;
                $newBox->weightKg = $box['weightKg'];
                $newBox->idPackage = $request->id;
                $newBox->save();
            }
            $contentDelete = ContentUPS::where('idPackage', $request->id)->get();
            foreach ($contentDelete as $content) {
                $content->delete();
            }
            $contents = json_decode($request->contents, true);
            foreach ($contents as $content) {
                $newContent = new ContentUPS;
                $newContent->quantity = $content['quantity'];
                $newContent->content = $content['content'];
                $newContent->content_en = $content['content_en'];
                $newContent->price = $content['price'];
                $newContent->subtotal = $content['subtotal'];
                $newContent->idPackage = $request->id;
                $newContent->save();
            }
            $package->save();
            $this->validateToCode($request->id);
            DB::commit();
            return Response::json(['message' => 'Paquete actualizado'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }
    public function payment(Request $request)
    {
        DB::beginTransaction();
        try {
            $accounts = json_decode($request->accounts, true);
            $UPSCustomers = PackageUPSCustomer::where('idPackageUPS', $request->id)->get();

            foreach ($accounts as $account) {
                $newAccount = new AccountCollection;
                $newAccount->exchangeRate = $account['exchangeRate'];
                $newAccount->amountDollar = $account['amountDollar'];
                $newAccount->bank = $account['bank'];
                $newAccount->amount = $account['amount'];
                $newAccount->keyNumber = $account['keyNumber'];
                $newAccount->nameSend = $account['nameSend'];
                $newAccount->type = $account['type'] == 1 ? "DEPÓSITO" : "REMESA";
                $newAccount->idPackageUPS = $request->id;
                $newAccount->idAccount = $account['id'];
                $newAccount->userCreated = $request->user;
                $newAccount->userUpdated = $request->user;
                $newAccount->save();

                foreach ($UPSCustomers as $UPSCustomer) {
                    foreach ($account['customer'] as $customer) {
                        if ($customer['idCustomer'] == $UPSCustomer->idCustomer) {
                            $UPSCustomer->balance = $UPSCustomer->balance - $customer['amountDollar'];
                            $UPSCustomer->save();

                            $UPSPackage = PackageUPS::find($request->id);
                            $UPSPackage->balance = $UPSPackage->balance - $customer['amountDollar'];
                            $UPSPackage->save();
                            break;
                        }
                    }
                }
            }
            $this->validateToCode($request->id);
            DB::commit();

            return Response::json(['message' => 'Pago guardado exitosamente'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
        // $request->address = $request->address;
    }
    public function code(Request $request)
    {
        $search = $request->search;
        $customer = $request->customer;
        $show = $request->show;
        // **CALL ALL REGISTRES
        if ($customer == -1) {
            if (is_null($search)) {
                return PackageUPS::with('boxes')
                    ->with('customer')
                    ->with('account')
                    ->where('status', 'CODE')
                    ->orderBy('code', 'DESC')
                    ->paginate($show);
            } else {
                $search = explode(" ", $request->search);
                return PackageUPS::with('boxes')
                    ->with('customer')
                    ->with('account')
                    ->where('status', 'CODE')
                    ->where(function ($query) use ($search) {
                        foreach ($search as $item) {
                            $query->where('id', 'like', '%' . $item . '%')
                                ->orWhere('serie', 'like', '%' . $item . '%')
                                ->orWhere('code', 'like', '%' . $item . '%')
                                ->orWhere('address', 'like', '%' . $item . '%')
                                ->orWhere('receive', 'like', '%' . $item . '%')
                                ->orWhere('sender', 'like', '%' . $item . '%')
                                ->orWhere('telephone', 'like', '%' . $item . '%')
                                ->orWhere('weight', 'like', '%' . $item . '%');
                        }
                    })
                    ->orderBy('code', 'DESC')
                    ->paginate($show);
            }
        }
        // **CALL OFFICES
        else if ($customer == 0) {
            if (is_null($search)) {
                return PackageUPS::with('boxes')
                    ->with('customer')
                    ->with('account')
                    ->where('status', 'CODE')
                    ->orderBy('code', 'DESC')
                    ->where('typical', false)
                    ->paginate($show);
            } else {
                $search = explode(" ", $request->search);
                return PackageUPS::with('boxes')
                    ->with('customer')
                    ->with('account')
                    ->where('status', 'CODE')
                    ->where('typical', false)
                    ->where(function ($query) use ($search) {
                        foreach ($search as $item) {
                            $query->where('id', 'like', '%' . $item . '%')
                                ->orWhere('code', 'like', '%' . $item . '%')
                                ->orWhere('serie', 'like', '%' . $item . '%')
                                ->orWhere('address', 'like', '%' . $item . '%')
                                ->orWhere('receive', 'like', '%' . $item . '%')
                                ->orWhere('sender', 'like', '%' . $item . '%')
                                ->orWhere('telephone', 'like', '%' . $item . '%')
                                ->orWhere('weight', 'like', '%' . $item . '%');
                        }
                    })
                    ->orderBy('code', 'DESC')
                    ->paginate($show);
            }
        } else {
            if (is_null($search)) {
                return PackageUPS::with('boxes')
                    ->with('customer')
                    ->with('account')
                    ->where('status', 'CODE')
                    ->whereHas('customer', function ($query) use ($customer) {
                        $query->where('customers.id', $customer);
                    })
                    ->orderBy('code', 'DESC')
                    ->paginate($show);
            } else {
                $search = explode(" ", $request->search);
                return PackageUPS::with('boxes')
                    ->with('customer')
                    ->with('account')
                    ->where('status', 'CODE')
                    ->whereHas('customer', function ($query) use ($customer) {
                        $query->where('customers.id', $customer);
                    })
                    ->where(function ($query) use ($search) {
                        foreach ($search as $item) {
                            $query->where('id', 'like', '%' . $item . '%')
                                ->orWhere('code', 'like', '%' . $item . '%')
                                ->orWhere('serie', 'like', '%' . $item . '%')
                                ->orWhere('address', 'like', '%' . $item . '%')
                                ->orWhere('receive', 'like', '%' . $item . '%')
                                ->orWhere('sender', 'like', '%' . $item . '%')
                                ->orWhere('telephone', 'like', '%' . $item . '%')
                                ->orWhere('weight', 'like', '%' . $item . '%');
                        }
                    })
                    ->orderBy('code', 'DESC')
                    ->paginate($show);
            }
        }
    }
    public function ups(Request $request)
    {
        $search = $request->search;
        $customer = $request->customer;
        $show = $request->show;
        // **CALL ALL REGISTRES
        if ($customer == -1) {
            if (is_null($search)) {
                return PackageUPS::with('boxes')
                    ->with('customer')
                    ->with('account')
                    ->where('status', 'GENERATED')
                    ->orderBy('code', 'DESC')
                    ->paginate($show);
            } else {
                $search = explode(" ", $request->search);
                return PackageUPS::with('boxes')
                    ->with('customer')
                    ->with('account')
                    ->where('status', 'GENERATED')
                    ->where(function ($query) use ($search) {
                        foreach ($search as $item) {
                            $query->where('id', 'like', '%' . $item . '%')
                                ->orWhere('serie', 'like', '%' . $item . '%')
                                ->orWhere('code', 'like', '%' . $item . '%')
                                ->orWhere('address', 'like', '%' . $item . '%')
                                ->orWhere('receive', 'like', '%' . $item . '%')
                                ->orWhere('sender', 'like', '%' . $item . '%')
                                ->orWhere('telephone', 'like', '%' . $item . '%')
                                ->orWhere('weight', 'like', '%' . $item . '%');
                        }
                    })
                    ->orderBy('code', 'DESC')
                    ->paginate($show);
            }
        }
        // **CALL OFFICES
        else if ($customer == 0) {
            if (is_null($search)) {
                return PackageUPS::with('boxes')
                    ->with('customer')
                    ->with('account')
                    ->where('status', 'GENERATED')
                    ->orderBy('code', 'DESC')
                    ->where('typical', false)
                    ->paginate($show);
            } else {
                $search = explode(" ", $request->search);
                return PackageUPS::with('boxes')
                    ->with('customer')
                    ->with('account')
                    ->where('status', 'GENERATED')
                    ->where('typical', false)
                    ->where(function ($query) use ($search) {
                        foreach ($search as $item) {
                            $query->where('id', 'like', '%' . $item . '%')
                                ->orWhere('code', 'like', '%' . $item . '%')
                                ->orWhere('serie', 'like', '%' . $item . '%')
                                ->orWhere('address', 'like', '%' . $item . '%')
                                ->orWhere('receive', 'like', '%' . $item . '%')
                                ->orWhere('sender', 'like', '%' . $item . '%')
                                ->orWhere('telephone', 'like', '%' . $item . '%')
                                ->orWhere('weight', 'like', '%' . $item . '%');
                        }
                    })
                    ->orderBy('code', 'DESC')
                    ->paginate($show);
            }
        } else {
            if (is_null($search)) {
                return PackageUPS::with('boxes')
                    ->with('customer')
                    ->with('account')
                    ->where('status', 'GENERATED')
                    ->whereHas('customer', function ($query) use ($customer) {
                        $query->where('customers.id', $customer);
                    })
                    ->orderBy('code', 'DESC')
                    ->paginate($show);
            } else {
                $search = explode(" ", $request->search);
                return PackageUPS::with('boxes')
                    ->with('customer')
                    ->with('account')
                    ->where('status', 'GENERATED')
                    ->whereHas('customer', function ($query) use ($customer) {
                        $query->where('customers.id', $customer);
                    })
                    ->where(function ($query) use ($search) {
                        foreach ($search as $item) {
                            $query->where('id', 'like', '%' . $item . '%')
                                ->orWhere('code', 'like', '%' . $item . '%')
                                ->orWhere('serie', 'like', '%' . $item . '%')
                                ->orWhere('address', 'like', '%' . $item . '%')
                                ->orWhere('receive', 'like', '%' . $item . '%')
                                ->orWhere('sender', 'like', '%' . $item . '%')
                                ->orWhere('telephone', 'like', '%' . $item . '%')
                                ->orWhere('weight', 'like', '%' . $item . '%');
                        }
                    })
                    ->orderBy('code', 'DESC')
                    ->paginate($show);
            }
        }
    }
    public function getPackages(Request $request)
    {
        DB::beginTransaction();
        try {
            $document = public_path() . "/document/Package.docx";
            $templateProcessor = new TemplateProcessor($document);
            if (empty($request->customer))
                $packages = PackageUPS::with('boxes')
                    ->with('customer')
                    ->with('contents')
                    ->where('status', 'CODE')
                    ->orderBy('code', 'DESC')
                    ->get();
            else
            if ($request->customer == -1) {
                $packages = PackageUPS::with('boxes')
                    ->with('customer')
                    ->with('contents')
                    ->where('status', 'GENERATED')
                    ->orderBy('code', 'DESC')
                    ->get();
            } else if ($request->customer == 0) {
                $packages = PackageUPS::with('boxes')
                    ->with('customer')
                    ->with('contents')
                    ->where('status', 'GENERATED')
                    ->where('typical', false)
                    ->orderBy('code', 'DESC')
                    ->get();
            } else {
                $packages = PackageUPS::with('boxes')
                    ->with('customer')
                    ->with('contents')
                    ->where('status', 'GENERATED')
                    ->where('idCustomer', $request->customer)
                    ->orderBy('code', 'DESC')
                    ->get();
            }

            $templateProcessor->cloneBlock('packages', count($packages), true, true);
            foreach ($packages as $key => $package) {
                if (empty($request->customer)) {
                    $package->status = 'GENERATED';
                    $package->save();
                }
                $newKey = $key + 1;
                if (count($package->boxes) > 1) {
                    $textBox = "";
                    foreach ($package->boxes as $i => $box) {
                        $newI = $i + 1;
                        $textBox .= "CAJA {$package->code}.{$newI} = {$box->weightKg}KG. ";
                    }
                    $templateProcessor->setValue("tittle#{$newKey}", "CAJA {$package->code}" . '<w:br/>' . "GUIA MADRE" . '<w:br/>' . $textBox);
                } else {
                    $templateProcessor->setValue("tittle#{$newKey}", "CAJA {$package->code}");
                }
                $fancyTableStyle = [
                    'borderSize'  => 6,
                    'borderColor' => '000000',
                    'cellMargin'  => 80,
                    'alignment'   => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER,
                    'layout'      => \PhpOffice\PhpWord\Style\Table::LAYOUT_FIXED,
                ];
                $fancyTableStyle2 = [
                    'borderSize'  => 6,
                    'borderColor' => 'ffffff',
                    'cellMargin'  => 80,
                    'alignment'   => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER,
                    'layout'      => \PhpOffice\PhpWord\Style\Table::LAYOUT_FIXED,
                ];
                $table = new Table($fancyTableStyle);
                // HEADER TABLE
                $table->addRow();
                $table->addCell(1000)->addText("NO.", array(
                    'size' => 12,
                    'bold' => true,
                ));
                $table->addCell(1500)->addText("ENVÍA", array(
                    'size' => 12,
                    'bold' => true,
                ));
                $table->addCell(1500)->addText("NOMBRE", array(
                    'size' => 12,
                    'bold' => true,
                ));
                $table->addCell(1500)->addText("CEL", array(
                    'size' => 12,
                    'bold' => true,
                ));
                $table->addCell(3500)->addText("DIRECCIÓN", array(
                    'size' => 12,
                    'bold' => true,
                ));
                $table->addCell(3500)->addText("CONTENIDO", array(
                    'size' => 12,
                    'bold' => true,
                ));
                $table->addCell(1000)->addText("PESO", array(
                    'size' => 12,
                    'bold' => true,
                ));
                // // END TABLE
                $table->addRow();
                $table->addCell(1000)->addText("1");
                $table->addCell(1500)->addText($package->sender);
                $table->addCell(1500)->addText($package->receive);
                $table->addCell(1500)->addText($package->telephone);
                $table->addCell(3500)->addText($package->address);
                // $textContent = "";
                $textValue = $table->addCell(3500)->addTable($fancyTableStyle);
                foreach ($package->contents as $content) {
                    $textValue->addRow();
                    $textValue->addCell(900)->addText($content->quantity);
                    $textValue->addCell(1400)->addText($content->content_en);
                    $textValue->addCell(900)->addText("$ " . $content->price);
                    // $textContent .= "{$content->quantity} {$content->content_en} = $ {$content->price}" . '<w:br/>';
                }
                // $table->addCell(3500)->addText($textValue);
                $table->addCell(1000)->addText("KG." . $package->totalKg);

                $templateProcessor->setComplexBlock("table#{$newKey}", $table);
            }
            $pathToSave =  public_path() . "/document/generated";
            $fileName = "CAJAS_UPS_" . strtotime('now') . ".docx";
            $templateProcessor->saveAs($pathToSave . "/" . $fileName);

            $headers = array(
                'Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            );
            DB::commit();
            return Response::download($pathToSave . "/" . $fileName, $fileName, $headers);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }
    public function upsCode(Request $request)
    {
        DB::beginTransaction();
        try {

            $package = PackageUPS::find($request->id);
            $package->codeUPS = $request->codeUPS;
            if ($package->status == 'GENERATED') {
                $package->status = 'READY';
                $package->inCode = date('Y-m-d H:i:s');
            }
            $package->save();
            DB::commit();

            return Response::json(['message' => 'Código UPS guardado exitosamente'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }
    public function ready(Request $request)
    {
        $search = $request->search;
        $customer = $request->customer;
        $show = $request->show;
        // **CALL ALL REGISTRES
        if ($customer == -1) {
            if (is_null($search)) {
                return PackageUPS::with('boxes')
                    ->with('customer')
                    ->with('account')
                    ->where('status', 'READY')
                    ->orderBy('code', 'DESC')
                    ->paginate($show);
            } else {
                return PackageUPS::with('boxes')
                    ->with('customer')
                    ->with('account')
                    ->where('status', 'READY')
                    ->where(function ($query) use ($search) {
                        foreach ($search as $item) {
                            $query->where('id', 'like', '%' . $item . '%')
                                ->orWhere('serie', 'like', '%' . $item . '%')
                                ->orWhere('code', 'like', '%' . $item . '%')
                                ->orWhere('address', 'like', '%' . $item . '%')
                                ->orWhere('receive', 'like', '%' . $item . '%')
                                ->orWhere('sender', 'like', '%' . $item . '%')
                                ->orWhere('telephone', 'like', '%' . $item . '%')
                                ->orWhere('weight', 'like', '%' . $item . '%');
                        }
                    })
                    ->orderBy('code', 'DESC')
                    ->paginate($show);
            }
        }
        // **CALL OFFICES
        else if ($customer == 0) {
            if (is_null($search)) {
                return PackageUPS::with('boxes')
                    ->with('customer')
                    ->with('account')
                    ->where('status', 'READY')
                    ->orderBy('code', 'DESC')
                    ->where('typical', false)
                    ->paginate($show);
            } else {
                return PackageUPS::with('boxes')
                    ->with('customer')
                    ->with('account')
                    ->where('status', 'READY')
                    ->where('typical', false)
                    ->where(function ($query) use ($search) {
                        foreach ($search as $item) {
                            $query->where('id', 'like', '%' . $item . '%')
                                ->orWhere('code', 'like', '%' . $item . '%')
                                ->orWhere('serie', 'like', '%' . $item . '%')
                                ->orWhere('address', 'like', '%' . $item . '%')
                                ->orWhere('receive', 'like', '%' . $item . '%')
                                ->orWhere('sender', 'like', '%' . $item . '%')
                                ->orWhere('telephone', 'like', '%' . $item . '%')
                                ->orWhere('weight', 'like', '%' . $item . '%');
                        }
                    })
                    ->orderBy('code', 'DESC')
                    ->paginate($show);
            }
        } else {
            if (is_null($search)) {
                return PackageUPS::with('boxes')
                    ->with('customer')
                    ->with('account')
                    ->where('status', 'READY')
                    ->whereHas('customer', function ($query) use ($customer) {
                        $query->where('customers.id', $customer);
                    })
                    ->orderBy('code', 'DESC')
                    ->paginate($show);
            } else {
                return PackageUPS::with('boxes')
                    ->with('customer')
                    ->with('account')
                    ->where('status', 'READY')
                    ->whereHas('customer', function ($query) use ($customer) {
                        $query->where('customers.id', $customer);
                    })
                    ->where(function ($query) use ($search) {
                        foreach ($search as $item) {
                            $query->where('id', 'like', '%' . $item . '%')
                                ->orWhere('code', 'like', '%' . $item . '%')
                                ->orWhere('serie', 'like', '%' . $item . '%')
                                ->orWhere('address', 'like', '%' . $item . '%')
                                ->orWhere('receive', 'like', '%' . $item . '%')
                                ->orWhere('sender', 'like', '%' . $item . '%')
                                ->orWhere('telephone', 'like', '%' . $item . '%')
                                ->orWhere('weight', 'like', '%' . $item . '%');
                        }
                    })
                    ->orderBy('code', 'DESC')
                    ->paginate($show);
            }
        }
    }
    public function routePackage(Request $request)
    {
        DB::beginTransaction();
        try {

            $package = PackageUPS::find($request->id);
            $package->status = 'ROUTE';
            $package->inRoute = date('Y-m-d H:i:s');
            $package->save();
            DB::commit();
            return Response::json(['message' => 'Paquete en ruta'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }
    public function routeComment(Request $request)
    {
        DB::beginTransaction();
        try {

            $package = PackageUPS::find($request->id);
            $package->inRouteComment = $request->inRouteComment;
            $package->save();
            DB::commit();
            return Response::json(['message' => 'Paquete en ruta'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }
    public function route(Request $request)
    {
        $search = $request->search;
        $customer = $request->customer;
        $show = $request->show;
        // **CALL ALL REGISTRES
        if ($customer == -1) {
            if (is_null($search)) {
                return PackageUPS::with('boxes')
                    ->with('customer')
                    ->with('account')
                    ->where('status', 'ROUTE')
                    ->orderBy('code', 'DESC')
                    ->paginate($show);
            } else {
                $search = explode(" ", $request->search);
                return PackageUPS::with('boxes')
                    ->with('customer')
                    ->with('account')
                    ->where('status', 'ROUTE')
                    ->where(function ($query) use ($search) {
                        foreach ($search as $item) {
                            $query->where('id', 'like', '%' . $item . '%')
                                ->orWhere('serie', 'like', '%' . $item . '%')
                                ->orWhere('code', 'like', '%' . $item . '%')
                                ->orWhere('address', 'like', '%' . $item . '%')
                                ->orWhere('receive', 'like', '%' . $item . '%')
                                ->orWhere('sender', 'like', '%' . $item . '%')
                                ->orWhere('telephone', 'like', '%' . $item . '%')
                                ->orWhere('weight', 'like', '%' . $item . '%');
                        }
                    })
                    ->orderBy('code', 'DESC')
                    ->paginate($show);
            }
        }
        // **CALL OFFICES
        else if ($customer == 0) {
            if (is_null($search)) {
                return PackageUPS::with('boxes')
                    ->with('customer')
                    ->with('account')
                    ->where('status', 'ROUTE')
                    ->orderBy('code', 'DESC')
                    ->where('typical', false)
                    ->paginate($show);
            } else {
                $search = explode(" ", $request->search);
                return PackageUPS::with('boxes')
                    ->with('customer')
                    ->with('account')
                    ->where('status', 'ROUTE')
                    ->where('typical', false)
                    ->where(function ($query) use ($search) {
                        foreach ($search as $item) {
                            $query->where('id', 'like', '%' . $item . '%')
                                ->orWhere('code', 'like', '%' . $item . '%')
                                ->orWhere('serie', 'like', '%' . $item . '%')
                                ->orWhere('address', 'like', '%' . $item . '%')
                                ->orWhere('receive', 'like', '%' . $item . '%')
                                ->orWhere('sender', 'like', '%' . $item . '%')
                                ->orWhere('telephone', 'like', '%' . $item . '%')
                                ->orWhere('weight', 'like', '%' . $item . '%');
                        }
                    })
                    ->orderBy('code', 'DESC')
                    ->paginate($show);
            }
        } else {
            if (is_null($search)) {
                return PackageUPS::with('boxes')
                    ->with('customer')
                    ->with('account')
                    ->where('status', 'ROUTE')
                    ->whereHas('customer', function ($query) use ($customer) {
                        $query->where('customers.id', $customer);
                    })
                    ->orderBy('code', 'DESC')
                    ->paginate($show);
            } else {
                $search = explode(" ", $request->search);
                return PackageUPS::with('boxes')
                    ->with('customer')
                    ->with('account')
                    ->where('status', 'ROUTE')
                    ->whereHas('customer', function ($query) use ($customer) {
                        $query->where('customers.id', $customer);
                    })
                    ->where(function ($query) use ($search) {
                        foreach ($search as $item) {
                            $query->where('id', 'like', '%' . $item . '%')
                                ->orWhere('code', 'like', '%' . $item . '%')
                                ->orWhere('serie', 'like', '%' . $item . '%')
                                ->orWhere('address', 'like', '%' . $item . '%')
                                ->orWhere('receive', 'like', '%' . $item . '%')
                                ->orWhere('sender', 'like', '%' . $item . '%')
                                ->orWhere('telephone', 'like', '%' . $item . '%')
                                ->orWhere('weight', 'like', '%' . $item . '%');
                        }
                    })
                    ->orderBy('code', 'DESC')
                    ->paginate($show);
            }
        }
    }
    public function deliveredPackage(Request $request)
    {
        DB::beginTransaction();
        try {
            $package = PackageUPS::find($request->id);
            $package->status = 'DELIVERED';
            $package->inDeliveredDate = $request->inDeliveredDate;
            $package->inDelivered = date('Y-m-d H:i:s');
            $package->save();
            DB::commit();
            return Response::json(['message' => 'Paquete en entregado'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }
    public function delivered(Request $request)
    {
        $search = $request->search;
        $customer = $request->customer;
        $show = $request->show;
        // **CALL ALL REGISTRES
        if ($customer == -1) {
            if (is_null($search)) {
                return PackageUPS::with('boxes')
                    ->with('customer')
                    ->with('account')
                    ->where('status', 'DELIVERED')
                    ->orderBy('code', 'DESC')
                    ->paginate($show);
            } else {
                $search = explode(" ", $request->search);
                return PackageUPS::with('boxes')
                    ->with('customer')
                    ->with('account')
                    ->where('status', 'DELIVERED')
                    ->where(function ($query) use ($search) {
                        foreach ($search as $item) {
                            $query->where('id', 'like', '%' . $item . '%')
                                ->orWhere('serie', 'like', '%' . $item . '%')
                                ->orWhere('code', 'like', '%' . $item . '%')
                                ->orWhere('address', 'like', '%' . $item . '%')
                                ->orWhere('receive', 'like', '%' . $item . '%')
                                ->orWhere('sender', 'like', '%' . $item . '%')
                                ->orWhere('telephone', 'like', '%' . $item . '%')
                                ->orWhere('weight', 'like', '%' . $item . '%');
                        }
                    })
                    ->orderBy('code', 'DESC')
                    ->paginate($show);
            }
        }
        // **CALL OFFICES
        else if ($customer == 0) {
            if (is_null($search)) {
                return PackageUPS::with('boxes')
                    ->with('customer')
                    ->with('account')
                    ->where('status', 'DELIVERED')
                    ->orderBy('code', 'DESC')
                    ->where('typical', false)
                    ->paginate($show);
            } else {
                $search = explode(" ", $request->search);
                return PackageUPS::with('boxes')
                    ->with('customer')
                    ->with('account')
                    ->where('status', 'DELIVERED')
                    ->where('typical', false)
                    ->where(function ($query) use ($search) {
                        foreach ($search as $item) {
                            $query->where('id', 'like', '%' . $item . '%')
                                ->orWhere('code', 'like', '%' . $item . '%')
                                ->orWhere('serie', 'like', '%' . $item . '%')
                                ->orWhere('address', 'like', '%' . $item . '%')
                                ->orWhere('receive', 'like', '%' . $item . '%')
                                ->orWhere('sender', 'like', '%' . $item . '%')
                                ->orWhere('telephone', 'like', '%' . $item . '%')
                                ->orWhere('weight', 'like', '%' . $item . '%');
                        }
                    })
                    ->orderBy('code', 'DESC')
                    ->paginate($show);
            }
        } else {
            if (is_null($search)) {
                return PackageUPS::with('boxes')
                    ->with('customer')
                    ->with('account')
                    ->where('status', 'DELIVERED')
                    ->whereHas('customer', function ($query) use ($customer) {
                        $query->where('customers.id', $customer);
                    })
                    ->orderBy('code', 'DESC')
                    ->paginate($show);
            } else {
                $search = explode(" ", $request->search);
                return PackageUPS::with('boxes')
                    ->with('customer')
                    ->with('account')
                    ->where('status', 'DELIVERED')
                    ->whereHas('customer', function ($query) use ($customer) {
                        $query->where('customers.id', $customer);
                    })
                    ->where(function ($query) use ($search) {
                        foreach ($search as $item) {
                            $query->where('id', 'like', '%' . $item . '%')
                                ->orWhere('code', 'like', '%' . $item . '%')
                                ->orWhere('serie', 'like', '%' . $item . '%')
                                ->orWhere('address', 'like', '%' . $item . '%')
                                ->orWhere('receive', 'like', '%' . $item . '%')
                                ->orWhere('sender', 'like', '%' . $item . '%')
                                ->orWhere('telephone', 'like', '%' . $item . '%')
                                ->orWhere('weight', 'like', '%' . $item . '%');
                        }
                    })
                    ->orderBy('code', 'DESC')
                    ->paginate($show);
            }
        }
    }
    public function stopped(Request $request)
    {
        $search = $request->search;
        $customer = $request->customer;
        $show = $request->show;
        // **CALL ALL REGISTRES
        if ($customer == -1) {
            if (is_null($search)) {
                return PackageUPS::with('boxes')
                    ->with('customer')
                    ->with('account')
                    ->where('status', 'STOPPED')
                    ->orderBy('code', 'DESC')
                    ->paginate($show);
            } else {
                $search = explode(" ", $request->search);
                return PackageUPS::with('boxes')
                    ->with('customer')
                    ->with('account')
                    ->where('status', 'STOPPED')
                    ->where(function ($query) use ($search) {
                        foreach ($search as $item) {
                            $query->where('id', 'like', '%' . $item . '%')
                                ->orWhere('serie', 'like', '%' . $item . '%')
                                ->orWhere('code', 'like', '%' . $item . '%')
                                ->orWhere('address', 'like', '%' . $item . '%')
                                ->orWhere('receive', 'like', '%' . $item . '%')
                                ->orWhere('sender', 'like', '%' . $item . '%')
                                ->orWhere('telephone', 'like', '%' . $item . '%')
                                ->orWhere('weight', 'like', '%' . $item . '%');
                        }
                    })
                    ->orderBy('code', 'DESC')
                    ->paginate($show);
            }
        }
        // **CALL OFFICES
        else if ($customer == 0) {
            if (is_null($search)) {
                return PackageUPS::with('boxes')
                    ->with('customer')
                    ->with('account')
                    ->where('status', 'STOPPED')
                    ->orderBy('code', 'DESC')
                    ->where('typical', false)
                    ->paginate($show);
            } else {
                $search = explode(" ", $request->search);
                return PackageUPS::with('boxes')
                    ->with('customer')
                    ->with('account')
                    ->where('status', 'STOPPED')
                    ->where('typical', false)
                    ->where(function ($query) use ($search) {
                        foreach ($search as $item) {
                            $query->where('id', 'like', '%' . $item . '%')
                                ->orWhere('code', 'like', '%' . $item . '%')
                                ->orWhere('serie', 'like', '%' . $item . '%')
                                ->orWhere('address', 'like', '%' . $item . '%')
                                ->orWhere('receive', 'like', '%' . $item . '%')
                                ->orWhere('sender', 'like', '%' . $item . '%')
                                ->orWhere('telephone', 'like', '%' . $item . '%')
                                ->orWhere('weight', 'like', '%' . $item . '%');
                        }
                    })
                    ->orderBy('code', 'DESC')
                    ->paginate($show);
            }
        } else {
            if (is_null($search)) {
                return PackageUPS::with('boxes')
                    ->with('customer')
                    ->with('account')
                    ->where('status', 'STOPPED')
                    ->whereHas('customer', function ($query) use ($customer) {
                        $query->where('customers.id', $customer);
                    })
                    ->orderBy('code', 'DESC')
                    ->paginate($show);
            } else {
                $search = explode(" ", $request->search);
                return PackageUPS::with('boxes')
                    ->with('customer')
                    ->with('account')
                    ->whereHas('customer', function ($query) use ($customer) {
                        $query->where('customers.id', $customer);
                    })
                    ->where('status', 'STOPPED')
                    ->where(function ($query) use ($search) {
                        foreach ($search as $item) {
                            $query->where('id', 'like', '%' . $item . '%')
                                ->orWhere('code', 'like', '%' . $item . '%')
                                ->orWhere('serie', 'like', '%' . $item . '%')
                                ->orWhere('address', 'like', '%' . $item . '%')
                                ->orWhere('receive', 'like', '%' . $item . '%')
                                ->orWhere('sender', 'like', '%' . $item . '%')
                                ->orWhere('telephone', 'like', '%' . $item . '%')
                                ->orWhere('weight', 'like', '%' . $item . '%');
                        }
                    })
                    ->orderBy('code', 'DESC')
                    ->paginate($show);
            }
        }
    }
    public function stoppedPackage(Request $request)
    {
        DB::beginTransaction();
        try {

            $package = PackageUPS::find($request->id);
            $package->status = 'STOPPED';
            $package->inStoppedComment = $request->inStoppedComment;
            $package->inStopped = date('Y-m-d H:i:s');
            $package->save();
            DB::commit();
            return Response::json(['message' => 'Paquete en reclamo'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }
    public function continuePackage(Request $request)
    {
        DB::beginTransaction();
        try {
            $package = PackageUPS::find($request->id);
            $package->status = 'ROUTE';
            $package->inRouteComment = $request->inRouteComment;
            $package->save();
            DB::commit();
            return Response::json(['message' => 'Paquete en ruta'], 200);
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
    public function forcePayment(Request $request)
    {
        DB::beginTransaction();
        try {
            $max = PackageUPS::max('code');
            $code = 0;
            if (isset($max)) {
                $code = $max + 1;
            } else {
                $code = 1;
            }
            $package = PackageUPS::find($request->id);
            $package->status = 'CODE';
            if (is_null($package->code))
                $package->code = $code;
            $package->save();
            $balance = 0;
            if ($package->typical == true) {
                $balance = PackageUPSCustomer::where('idPackageUPS', $request->id)->sum('balance');
            } else {
                $balance = $package->balance;
            }
            $users = User::where('idRol', 1)->whereNotNull('telegramId')->get();
            foreach ($users as $user) {
                $body['message'] = "EL PAQUETE PARA {$package->receive} CON NÚMERO DE CAJA {$package->code} TIENE UN SALDO PENDIENTE DE $ {$balance}";
                $user->notify(new TelegramNotification($body));
            }
            DB::commit();
            return Response::json(['message' => 'Se realizo un pago forzado, este paquete aun tiene un pago pendiente'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }
    public function balance(Request $request)
    {
        $search = $request->search;
        $customer = $request->customer;
        $show = $request->show;
        // **CALL ALL REGISTRES
        if ($customer == -1) {
            if (is_null($search)) {
                return PackageUPS::with('boxes')
                    ->with('customer')
                    ->with('contents')
                    ->with('account')
                    ->whereHas('customer', function ($query) use ($customer) {
                        $query->where('balance', '>', 0);
                    })
                    ->where('status', '!=', 'OFICINA')
                    ->orderBy('id', 'DESC')
                    ->paginate($show);
            } else {
                $search = explode(" ", $request->search);
                return PackageUPS::with('boxes')
                    ->with('customer')
                    ->with('contents')
                    ->with('account')
                    ->where('status', '!=', 'OFICINA')
                    ->whereHas('customer', function ($query) use ($customer) {
                        $query->where('balance', '>', 0);
                    })
                    ->where(function ($query) use ($search) {
                        foreach ($search as $item) {
                            $query->where('id', 'like', '%' . $item . '%')
                                ->orWhere('serie', 'like', '%' . $item . '%')
                                ->orWhere('address', 'like', '%' . $item . '%')
                                ->orWhere('receive', 'like', '%' . $item . '%')
                                ->orWhere('sender', 'like', '%' . $item . '%')
                                ->orWhere('telephone', 'like', '%' . $item . '%')
                                ->orWhere('weight', 'like', '%' . $item . '%');
                        }
                    })
                    ->orderBy('id', 'DESC')
                    ->paginate($show);
            }
        }
        // **CALL OFFICES
        else if ($customer == 0) {
            if (is_null($search)) {
                return PackageUPS::with('boxes')
                    ->with('customer')
                    ->with('contents')
                    ->with('account')
                    ->where('status', '!=', 'OFICINA')
                    ->whereHas('customer', function ($query) use ($customer) {
                        $query->where('balance', '>', 0);
                    })
                    ->where('typical', 0)
                    ->orderBy('code', 'DESC')
                    ->paginate($show);
            } else {
                $search = explode(" ", $request->search);
                return PackageUPS::with('boxes')
                    ->with('customer')
                    ->with('contents')
                    ->with('account')
                    ->where('typical', 0)
                    ->whereHas('customer', function ($query) use ($customer) {
                        $query->where('balance', '>', 0);
                    })
                    ->where('status', '!=', 'OFICINA')
                    ->where(function ($query) use ($search) {
                        foreach ($search as $item) {
                            $query->where('id', 'like', '%' . $item . '%')
                                ->orWhere('serie', 'like', '%' . $item . '%')
                                ->orWhere('address', 'like', '%' . $item . '%')
                                ->orWhere('receive', 'like', '%' . $item . '%')
                                ->orWhere('sender', 'like', '%' . $item . '%')
                                ->orWhere('telephone', 'like', '%' . $item . '%')
                                ->orWhere('weight', 'like', '%' . $item . '%');
                        }
                    })
                    ->orderBy('code', 'DESC')
                    ->paginate($show);
            }
        } else {
            if (is_null($search)) {
                return PackageUPS::with('boxes')
                    ->with('customer')
                    ->with('contents')
                    ->with('account')
                    ->where('status', '!=', 'OFICINA')
                    ->orderBy('code', 'DESC')
                    ->whereHas('customer', function ($query) use ($customer) {
                        $query->where('customers.id', $customer)
                            ->where('balance', '>', 0);
                    })
                    ->paginate($show);
            } else {
                $search = explode(" ", $request->search);
                return PackageUPS::with('boxes')
                    ->with('customer')
                    ->with('contents')
                    ->with('account')
                    ->where('status', '!=', 'OFICINA')
                    ->whereHas('customer', function ($query) use ($customer) {
                        $query->where('customers.id', $customer)
                            ->where('balance', '>', 0);
                    })
                    ->where(function ($query) use ($search) {
                        foreach ($search as $item) {
                            $query->where('id', 'like', '%' . $item . '%')
                                ->orWhere('serie', 'like', '%' . $item . '%')
                                ->orWhere('address', 'like', '%' . $item . '%')
                                ->orWhere('receive', 'like', '%' . $item . '%')
                                ->orWhere('sender', 'like', '%' . $item . '%')
                                ->orWhere('telephone', 'like', '%' . $item . '%')
                                ->orWhere('weight', 'like', '%' . $item . '%');
                        }
                    })
                    ->orderBy('code', 'DESC')
                    ->paginate($show);
            }
        }
    }
    public function download(Request $request)
    {
        $customer = $request->customer;
        $type = $request->type;
        $name = $request->name;
        $meta = [];
        if ($customer == -1) {
            $packages =  PackageUPS::with('boxes')
                ->with('customer')
                ->with('contents')
                ->with('account')
                ->where('status', $type)
                ->orderBy('id', 'DESC');
            $columns = [
                'ID' => 'id',
                'Código' => 'code',
                'UPS' => 'codeUPS',
                'Destinario' => 'receive',
                'Teléfono' => 'telephone',
                'Dirección' => 'address',
                'Cajas' => 'boxes',
                'Peso' => 'weight',
                'PesoKg' => 'totalKg',
                'Pago' => 'totalPayment',
                'Observacion en camino' => 'inRouteComment',
                'Observacion entrega' => 'inDeliveredComment',
                'Observacion reclamo' => 'inStoppedComment',
            ];
            return ExcelReport::of("Paquetes ", $meta, $packages, $columns)
                ->editColumn('Cajas', [
                    'displayAs' => function ($result) {
                        return count($result->boxes);
                    },
                    'class' => 'left',
                ])
                ->editColumn('Pago', [
                    'displayAs' => function ($result) {
                        return "$ " . $result->totalPayment;
                    },
                    'class' => 'left',
                ])
                ->editColumn('Peso', [
                    'displayAs' => function ($result) {
                        if ($result->typical == "0") {
                            return $result->weight;
                        } else {
                            $total = 0;
                            foreach ($result->customer as $c) {
                                $total = $total + $c->weight;
                            }
                            return $total;
                        }
                    },
                    'class' => 'left',
                ])
                ->download("REPORTE_{$name}");
        } else if ($customer == 0) {
            $packages = PackageUPS::with('boxes')
                ->with('customer')
                ->with('contents')
                ->with('account')
                ->where('status', $type)
                ->where('typical', 0)
                ->orderBy('code', 'DESC');
            $columns = [
                'ID' => 'id',
                'Código' => 'code',
                'UPS' => 'codeUPS',
                'Destinario' => 'receive',
                'Teléfono' => 'telephone',
                'Dirección' => 'address',
                'Cajas' => 'boxes',
                'Peso' => 'weight',
                'PesoKg' => 'totalKg',
                'Pago' => 'totalPayment',
                'Observacion en camino' => 'inRouteComment',
                'Observacion entrega' => 'inDeliveredComment',
                'Observacion reclamo' => 'inStoppedComment',
            ];
            return ExcelReport::of("Paquetes ", $meta, $packages, $columns)
                ->editColumn('Cajas', [
                    'displayAs' => function ($result) {
                        return count($result->boxes);
                    },
                    'class' => 'left',
                ])
                ->editColumn('Pago', [
                    'displayAs' => function ($result) {
                        return "$ " . $result->totalPayment;
                    },
                    'class' => 'left',
                ])
                ->editColumn('Peso', [
                    'displayAs' => function ($result) {
                        if ($result->typical == "0") {
                            return $result->weight;
                        } else {
                            $total = 0;
                            foreach ($result->customer as $c) {
                                $total = $total + $c->weight;
                            }
                            return $total;
                        }
                    },
                    'class' => 'left',
                ])
                ->download("REPORTE_{$name}");
        } else {
            $packages = PackageUPS::with('boxes')
                ->with('customer')
                ->with('contents')
                ->with('account')
                ->where('status', $type)
                ->orderBy('code', 'DESC')
                ->whereHas('customer', function ($query) use ($customer) {
                    $query->where('customers.id', $customer);
                });
            $columns = [
                'ID' => 'id',
                'Código' => 'code',
                'UPS' => 'codeUPS',
                'Destinario' => 'receive',
                'Teléfono' => 'telephone',
                'Dirección' => 'address',
                'Cajas' => 'boxes',
                'Peso' => 'weight',
                'PesoKg' => 'totalKg',
                'Pago' => 'totalPayment',
                'Observacion en camino' => 'inRouteComment',
                'Observacion entrega' => 'inDeliveredComment',
                'Observacion reclamo' => 'inStoppedComment',
            ];
            return ExcelReport::of("Paquetes ", $meta, $packages, $columns)
                ->editColumn('Cajas', [
                    'displayAs' => function ($result) {
                        return count($result->boxes);
                    },
                    'class' => 'left',
                ])
                ->editColumn('Pago', [
                    'displayAs' => function ($result) {
                        return "$ " . $result->totalPayment;
                    },
                    'class' => 'left',
                ])
                ->editColumn('Peso', [
                    'displayAs' => function ($result) {
                        if ($result->typical == "0") {
                            return $result->weight;
                        } else {
                            $total = 0;
                            foreach ($result->customer as $c) {
                                $total = $total + $c->weight;
                            }
                            return $total;
                        }
                    },
                    'class' => 'left',
                ])
                ->download("REPORTE_{$name}");
        }
    }
}
