<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpsertProductRequest;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use SebastianBergmann\LinesOfCode\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        return view("products.index", [
            'products' => Product::paginate(10)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        return view("products.create", [
            'categories' => ProductCategory::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UpsertProductRequest $request
     * @return RedirectResponse
     */
    public function store(UpsertProductRequest $request): RedirectResponse
    {
        $product = new Product($request->validated());
        if ($request->hasFile('image')) {
            $product->image_path = $request->file('image')->store('products');
        }
        $product->save();
        return redirect(route('products.index'))->with('status', __(key: 'shop.product.status.store.success'));
    }

    /**
     * Display the specified resource.
     *
     * @param Product $product
     * @return View
     */
    public function show(Product $product): View
    {
        return view("products.show", [
            'product' => $product
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Product $product
     * @return View
     */
    public function edit(Product $product): View
    {
        return view("products.edit", [
            'product' => $product
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpsertProductRequest $request
     * @param Product $product
     * @return RedirectResponse
     */
    public function update(UpsertProductRequest $request, Product $product): RedirectResponse
    {
        $oldImagePath = $product->image_path;
        $product->fill($request->validated());
        if ($request->hasFile('image')) {
            if (Store::exists($oldImagePath)) {
                Storage::delete($oldImagePath);
            }
            $product->image_path = $request->file('image')->store('products');
        }
        $product->save();
        return redirect(route('products.index'))->with('status', __(key: 'shop.product.status.update.success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Product $product
     * @return JsonResponse
     */
    public function destroy(Product $product): JsonResponse
    {
        try {
            $product->delete();
            Session::flash('status', __('shop.product.status.delete.success'));
            return response()->json([
                'status' => 'success'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Wyst??pi?? b????d!'
            ])->setStatusCode(500);
        }
    }

    /**
     * Download image of the specified resource in storage.
     *
     * @param Product $product
     * @return RedirectResponse|StreamedResponse
     */
    public function downloadImage(Product $product): RedirectResponse|StreamedResponse
    {
        if (Store::exists($product->image_path)) {
            return Storage::download($product->image_path);
        }

        return Redirect::back();
    }
}
