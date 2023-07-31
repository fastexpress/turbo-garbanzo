<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\PriceUSAController;
use App\Http\Controllers\ShipmentsUSAController;
use App\Http\Controllers\CollectUSAController;
use App\Http\Controllers\PermitsController;
use App\Http\Controllers\ToolsController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\BalerController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\AccountPersonalController;
use App\Http\Controllers\CashRegisterController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PackageUPSController;
use App\Http\Controllers\OfficeUSAController;
use App\Http\Controllers\OfficeGTController;
use App\Http\Controllers\DateCollectController;
use App\Http\Controllers\BagController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\SearchPackageUPSController;
use App\Http\Controllers\SearchPackageController;
use App\Http\Controllers\AccountCollectionController;
use App\Http\Controllers\ExcelReportController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
});

Route::resource('/categories', CategoryController::class);
Route::resource('/users', UserController::class);
Route::resource('/prices', PriceUSAController::class);
Route::resource('/shipments/usa', ShipmentsUSAController::class);
Route::resource('/collect', CollectUSAController::class);
Route::resource('/rols', RolController::class);
Route::resource('/customers', CustomerController::class);
Route::resource('/balers', BalerController::class);
Route::resource('/accounts', AccountController::class);
Route::resource('/accountspersonal', AccountPersonalController::class);
Route::resource('/cashregister', CashRegisterController::class);
Route::resource('/package', PackageController::class);
Route::resource('/package-ups', PackageUPSController::class);
Route::resource('/office', OfficeUSAController::class);
Route::resource('/officeGT', OfficeGTController::class);
Route::resource('/dates/collects', DateCollectController::class);

Route::get('/package/bag', [PackageController::class, 'bag']);
Route::get('/bag/getList', [BagController::class, 'getList']);
Route::get('/getCategory', [PriceUSAController::class, 'getCategory']);
Route::get('/getAllCategories', [CategoryController::class, 'getAllCategories']);
Route::get('/getDepartment', [PriceUSAController::class, 'getDepartment']);
Route::get('/getTowns', [PriceUSAController::class, 'getTowns']);
Route::get('/getShipments', [ShipmentsUSAController::class, 'getShipments']);
Route::get('/getRols', [RolController::class, 'getRols']);
Route::get('/getPermits', [PermitsController::class, 'getPermits']);
Route::get('/getPermit/{idRol}/{idPermiso}', [PermitsController::class, 'getPermit']);
Route::get('/getNumbers', [ToolsController::class, 'getNumbers']);
Route::get('/getBaler', [BalerController::class, 'getBaler']);
Route::get('/getPrice', [PriceUSAController::class, 'getPrice']);
Route::get('/getAccount', [AccountController::class, 'getAccount']);
Route::get('/getCustomer', [CustomerController::class, 'getCustomer']);
Route::get('/getAccountPersonal', [AccountPersonalController::class, 'getAccountPersonal']);
Route::get('/getAllAccount', [AccountController::class, 'getAllAccount']);
Route::get('/getCashRegister', [CashRegisterController::class, 'getCashRegister']);
Route::get('/getUser', [UserController::class, 'getUser']);
Route::get('/getTravelers', [UserController::class, 'getTravelers']);
Route::get('/setting/{setting}', [SettingController::class, 'get']);
Route::get('/report/collect', [CollectUSAController::class, 'report']);
Route::get('/report/packages', [PackageController::class, 'report']);
Route::get('/getTotalWeight', [PackageController::class, 'getTotalWeight']);
Route::get('/getPackages', [PackageController::class, 'getPackages']);
Route::get('/getManifestBag', [BagController::class, 'getManifestBag']);
Route::get('/getNumberBag', [BagController::class, 'getNumberBag']);
Route::get('/getBags', [BagController::class, 'getBags']);
Route::get('/transaction/index', [TransactionController::class, 'index']);
Route::get('/charge/get', [AccountCollectionController::class, 'get']);
Route::get('/charge/report', [AccountCollectionController::class, 'report']);
Route::get('/report/typical', [ExcelReportController::class, 'reportTypical']);
Route::get('/report/not-typical', [ExcelReportController::class, 'reportNotTypical']);
Route::get('/report/ups', [ExcelReportController::class, 'reportUPS']);
Route::get('/report/account', [AccountController::class, 'report']);



// SEARCH FOR UPS PACKAGES
Route::get('/search-package-ups-office', [SearchPackageUPSController::class, 'office']);
Route::get('/search-package-ups-code', [SearchPackageUPSController::class, 'code']);
Route::get('/search-package-ups-pendent', [SearchPackageUPSController::class, 'ups']);
Route::get('/search-package-ups-ready', [SearchPackageUPSController::class, 'ready']);
Route::get('/search-package-ups-route', [SearchPackageUPSController::class, 'route']);
Route::get('/search-package-ups-delivered', [SearchPackageUPSController::class, 'delivered']);
Route::get('/search-package-ups-stopped', [SearchPackageUPSController::class, 'stopped']);
Route::get('/search-package-ups-download', [SearchPackageUPSController::class, 'download']);

Route::get('/package-ups-download', [SearchPackageUPSController::class, 'getPackages']);
Route::get('/package-ups-find/{telephone}', [PackageUPSController::class, 'find']);
Route::get('/search-package-ups-balance', [SearchPackageUPSController::class, 'balance']);


Route::post('/package-ups-validate-address', [SearchPackageUPSController::class, 'address']);
Route::post('/package-ups-payment', [SearchPackageUPSController::class, 'payment']);
Route::post('/package-ups-content', [SearchPackageUPSController::class, 'content']);
Route::post('/package-ups-code', [SearchPackageUPSController::class, 'upsCode']);
Route::post('/package-ups-route', [SearchPackageUPSController::class, 'routePackage']);
Route::post('/package-ups-route-comment', [SearchPackageUPSController::class, 'routeComment']);
Route::post('/package-ups-stopped', [SearchPackageUPSController::class, 'stoppedPackage']);
Route::post('/package-ups-continue', [SearchPackageUPSController::class, 'continuePackage']);
Route::post('/package-ups-delivered', [SearchPackageUPSController::class, 'deliveredPackage']);
Route::post('/package-ups-force-payment', [SearchPackageUPSController::class, 'forcePayment']);
Route::post('/package-ups-return', [PackageUPSController::class, 'returnPackage']);
// END SEARCH UPS PACKAGES

// SEARCH FOR PACKES
//* BEGIN CALL
Route::get('/search-package-call', [SearchPackageController::class, 'call']);
Route::post('/package-status-call', [SearchPackageController::class, 'statusCall']);
Route::post('/package-observartion-call', [SearchPackageController::class, 'observationCall']);
// *END CALL

//* BEGIN WHATSAPP
Route::get('/search-package-whatsapp', [SearchPackageController::class, 'whatsapp']);
Route::post('/package-status-whatsapp', [SearchPackageController::class, 'statusWhatApp']);
Route::post('/package-observartion-whatsapp', [SearchPackageController::class, 'observationWhatApp']);
Route::post('/package-csv-whatsapp', [SearchPackageController::class, 'csvWhatsApp']);
// *END WHATSAPP

Route::get('/search-package-without-bag', [SearchPackageController::class, 'packagesWithOutBag']);
Route::post('/package-change-status', [SearchPackageController::class, 'packageChangeStatus']);
Route::get('/search-package-slope', [SearchPackageController::class, 'slope']);
Route::post('/package-delivered', [SearchPackageController::class, 'deliver']);
Route::get('/search-package-delivered', [SearchPackageController::class, 'delivered']);
Route::get('/search-package-all', [SearchPackageController::class, 'all']);
// END SEARCH PACKAGES

Route::post('/updatePermit/{idPermiso}', [PermitsController::class, 'updatePermit']);
Route::post('/collect/accept/{id}', [CollectUSAController::class, 'accept']);
Route::post('/collect/deny/{id}', [CollectUSAController::class, 'deny']);
Route::post('/transaction/movement', [TransactionController::class, 'movement']);
Route::post('/transaction/account', [TransactionController::class, 'account']);
Route::post('/bags/changeStatus', [BagController::class, 'changeStatus']);
Route::post('/bags/moveRestPackages', [PackageController::class, 'moveRestPackages']);
Route::post('/charge/changeToConsignment', [AccountCollectionController::class, 'changeToConsignment']);
Route::post('/charge/changeToDeposit', [AccountCollectionController::class, 'changeToDeposit']);
Route::post('/charge/charged', [AccountCollectionController::class, 'charged']);

Route::put('/moveListToBag/{id}', [PackageController::class, 'moveListToBag']);
Route::put('/moveBagToList/{id}', [PackageController::class, 'moveBagToList']);
Route::put('/setting/{setting}', [SettingController::class, 'update']);
Route::put('/package-ups/control/{id}', [PackageUPSController::class, 'control']);
Route::put('/updateAmountBank/{id}', [AccountPersonalController::class, 'updateAmount']);
