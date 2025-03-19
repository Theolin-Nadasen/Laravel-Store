<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class NavigationController extends Controller
{
    public function catalogue(){
        $products = Product::all();

        return view('catelog', ['products' => $products]);
    }

    public function viewProduct(Product $product) {
        return view('viewProduct', ['product' => $product]);
    }

    public function addtocart(Product $product){
        $user = auth()->user();
        $cart = $user->cart ? json_decode($user->cart) : [];

        $cart[] = $product->id;
        $user->cart = json_encode($cart);

        $user->save();

        return redirect(route('landing'));

    }
}
