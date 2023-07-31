<?php

namespace App\Http\Controllers;

use App\Models\CollectGT;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;

use \Exception;

class ToolsController extends Controller
{
    public function getNumbers()
    {
        $telephones = CollectGT::select('telephone')
            ->groupBy('telephone')
            ->get()
            ->pluck('telephone');
        $telephoneAlternatives = CollectGT::select('alternativeTelephone')
            ->whereNotNull('alternativeTelephone')
            ->groupBy('alternativeTelephone')
            ->get()
            ->pluck('alternativeTelephone');
        $fileText = "";
        foreach ($telephones as $telephone) {
            if (strlen($telephone) === 9)
                $fileText = $fileText . "+502" . str_replace("-", "", $telephone) . "\n";
            else
                $fileText = $fileText .  "+1" . str_replace("-", "", $telephone) . "\n";
        }
        foreach ($telephoneAlternatives as $telephone) {
            if (strlen($telephone) === 9)
                $fileText = $fileText . "+502" . str_replace("-", "", $telephone) . "\n";
            else
                $fileText = $fileText .  "+1" . str_replace("-", "", $telephone) . "\n";
        }
        $nameFile = "Telefonos.txt";
        $headers = ['Content-type' => 'text/plain', 'Content-Disposition' => sprintf('attachment; filename="%s"', $nameFile), 'X-BooYAH' => 'Work'];
        return Response::make($fileText, 200, $headers);
    }
}
