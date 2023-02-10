<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Session;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use SebastianBergmann\LinesOfCode\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;

class WelcomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return View|JsonResponse
     */
    public function index(Request $request): View|JsonResponse
    {
        $filters = $request->query(key: 'filter');
        $paginate = $request->query(key: 'paginate') ?? 5;

        $query = Product::query();

        if (!is_null($filters))
        {
            if (array_key_exists(key: 'categories', array: $filters)) {
                $query = $query->whereIn(column: 'category_id', values: $filters['categories']);
            }
            if (!is_null($filters['price_min'])) {
                $query = $query->where(column: 'price', operator: '>=', value: $filters['price_min']);
            }
            if (!is_null($filters['price_max'])) {
                $query = $query->where(column: 'price', operator: '<=', value: $filters['price_max']);
            }
            return response()->json($query->paginate($paginate));
        }
        return view("welcome", [
            'products' =>$query->get(),
            'categories' =>ProductCategory::orderBy('name', 'ASC')->get(),
            'defaultImage' => 'https://via.placeholder.com/240x240/5fa9f8/efefef'
        ]);
    }
}
