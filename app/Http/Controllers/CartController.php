<?php

namespace App\Http\Controllers;

use App\Dtos\Cart\CartItemDto;
use App\Models\Product;
use App\ValueObjects\Cart;
use Exception;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\JsonResponse;

class CartController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return View
     */
    public function index(): View
    {
        return view('cart.index', [
            'cart' => Session::get('cart', new Cart())
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Product $product
     * @return JsonResponse
     */
    public function store(Product $product): JsonResponse
    {
        $cart = Session::get('cart', new Cart());
        $items = $cart->getItems();

        if(Arr::exists($items,$product->id)){
            $items[$product->id]->incrementQuantity();
        } else{
            $cartItemDto = $this->getCartItemDto($product);
            $items[$product->id] = $cartItemDto;
        }
        $cart->setItems($items);
        $cart->incrementTotalQuantity();
        $cart->incrementTotalSum($product->price);


        Session::put('cart', $cart);
        return response()->json([
            'status' => 'success'
        ]);
    }

    private function getCartItemDto(Product $product): CartItemDto
    {
        $cartItemDto = new CartItemDto();
        $cartItemDto->setProductId($product->id);
        $cartItemDto->setName($product->name);
        $cartItemDto->setPrice($product->price);
        $cartItemDto->setQuantity(quantity: 1);
        $cartItemDto->setImagePath($product->image_path);
        return $cartItemDto;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Product  $product
     * @return JsonResponse
     */
    public function destroy(Product $product): JsonResponse
    {
        try {
            $cart = Session::get('cart', new Cart());
            Session::put('cart', $cart->removeItem($product));
            Session::flash('status', __('shop.product.status.delete.success'));
            return response()->json([
                'status' => 'success'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Wystąpił błąd!'
            ])->setStatusCode(500);
        }
    }
}
