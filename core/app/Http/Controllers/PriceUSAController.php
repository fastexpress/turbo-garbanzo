<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Departament;
use App\Models\Town;
use App\Models\PriceUSA;
use App\Models\DepartamentPriceUSA;
use App\Models\CategoriesPriceUSA;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use \Exception;

class PriceUSAController extends Controller
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
            return PriceUSA::orderBy('id', 'desc')
                ->where('status', true)
                ->with('categories', 'departaments')
                ->paginate($show);
        } else {
            $search = explode(" ", $request->search);
            return PriceUSA::where(function ($query) use ($search) {
                foreach ($search as $item) {
                    $query->where('id', 'like', '%' . $item . '%')
                        ->orWhere('isDelivery', 'like', '%' . $item . '%')
                        ->orWhere('basePrice', 'like', '%' . $item . '%')
                        ->orWhere('multiplicater', 'like', '%' . $item . '%');
                }
            })
                ->orderBy('id', 'desc')
                ->where('status', true)
                ->with('categories', 'departaments')
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
            // BEGIN PRICE
            $priceUSA = new PriceUSA;
            $priceUSA->basePrice = $request->priceBase;
            $priceUSA->multiplicater = $request->multiplicater;
            $priceUSA->isDelivery = $request->isDelivery;
            $priceUSA->save();
            // END PRICE
            // BEGIN CATEGORIES
            $categories = json_decode($request->categories, true);
            foreach ($categories as $category) {
                $newCategory = new CategoriesPriceUSA;
                $newCategory->idPriceUSA = $priceUSA->id;
                $newCategory->idCategory = $category['id'];
                $newCategory->save();
            }
            // END CATEGORIES
            // BEGIN DEPARTAMENTS
            $departaments = json_decode($request->departaments, true);
            foreach ($departaments as $departament) {
                $newDepartament = new DepartamentPriceUSA;
                $newDepartament->idPriceUSA = $priceUSA->id;
                $newDepartament->idDepartament = $departament['id'];
                $newDepartament->save();
            }
            // END DEPARTAMENTS
            DB::commit();
            return Response::json(['message' => 'Precio guardado exitosamente'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return PriceUSA::where('id', $id)->with('categories', 'departaments')->get()->first();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            // BEGIN PRICE
            $priceUSA = PriceUSA::find($id);
            $priceUSA->basePrice = $request->priceBase;
            $priceUSA->multiplicater = $request->multiplicater;
            $priceUSA->isDelivery = $request->isDelivery;
            $priceUSA->save();
            // END PRICE
            // BEGIN CATEGORIES
            // DELETE
            $categories = CategoriesPriceUSA::where('idPriceUSA', $id)->delete();
            $departaments = DepartamentPriceUSA::where('idPriceUSA', $id)->delete();
            // END DELETE
            $categories = json_decode($request->categories, true);
            foreach ($categories as $category) {
                $newCategory = new CategoriesPriceUSA;
                $newCategory->idPriceUSA = $priceUSA->id;
                $newCategory->idCategory = $category['id'];
                $newCategory->save();
            }
            // END CATEGORIES
            // BEGIN DEPARTAMENTS
            $departaments = json_decode($request->departaments, true);
            foreach ($departaments as $departament) {
                $newDepartament = new DepartamentPriceUSA;
                $newDepartament->idPriceUSA = $priceUSA->id;
                $newDepartament->idDepartament = $departament['id'];
                $newDepartament->save();
            }
            // END DEPARTAMENTS
            DB::commit();
            return Response::json(['message' => 'Precio guardado exitosamente'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $priceUSA = PriceUSA::find($id);
            $priceUSA->status = false;
            $priceUSA->save();
            DB::commit();
            return Response::json(['message' => 'Precio eliminado exitosamente'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['message' => $e->getMessage()], 400);
        }
    }
    public function getCategory(Request $request)
    {
        $search = explode(" ", $request->filter);
        $categories = Category::where('id', $request->filter)
            ->where('status', true)
            ->get();
        if (count($categories) == 1) {
            return $categories;
        }
        return Category::where(function ($query) use ($search) {
            foreach ($search as $item) {
                $query->where('id', 'like', '%' . $item . '%')
                    ->orWhere('name', 'like', '%' . $item . '%');
            }
        })
            ->where('status', true)
            ->orderBy('created_at', 'desc')->get();
    }
    public function getDepartment(Request $request)
    {
        $search = explode(" ", $request->filter);
        $departament = Departament::where('id', $request->filter)
            ->get();
        if (count($departament) == 1) {
            return $departament;
        }
        return Departament::where(function ($query) use ($search) {
            foreach ($search as $item) {
                $query->where('id', 'like', '%' . $item . '%')
                    ->orWhere('name', 'like', '%' . $item . '%');
            }
        })
            ->orderBy('created_at', 'desc')->get();
    }
    public function getTowns(Request $request)
    {
        $idDepartament = $request->idDepartament;
        return Town::where('idDepartament', $idDepartament)
            ->get();
    }
    public function getPrice(Request $request)
    {
        $departament = $request->departament;
        $type = $request->type;
        $category = $request->category;

        $price = PriceUSA::join('categories_price_u_s_a_s', 'categories_price_u_s_a_s.idPriceUSA', 'price_u_s_a_s.id')
            ->join('departament_price_u_s_a_s', 'departament_price_u_s_a_s.idPriceUSA', 'price_u_s_a_s.id')
            ->where('categories_price_u_s_a_s.idCategory', $category)
            ->where('departament_price_u_s_a_s.idDepartament', $departament)
            ->where('price_u_s_a_s.isDelivery', $type)
            ->select('price_u_s_a_s.id', 'price_u_s_a_s.basePrice', 'price_u_s_a_s.multiplicater')
            ->get()
            ->first();
        if (isset($price)) {
            return Response::json(['price' => $price], 200);
        } else {
            return Response::json(['message' => 'Precio no encontrado'], 400);
        }
    }
}
