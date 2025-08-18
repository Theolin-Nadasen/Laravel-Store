<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class NavigationController extends Controller
{
    public function home(){

        $products = Product::latest()->take(3)->get();

        return view('welcome', ['products' => $products]);
    }

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

    public function viewcart(){
        $products = [];
        

        foreach (json_decode(auth()->user()->cart) as $id){
            $products[] = Product::find($id);
        };

        // totaling up the pices
        $total = 0.00;

        foreach($products as $item){
            $total = $total + $item->price;
        }

        return view('cart', ['products' => $products, 'total' => $total]);
    }

    public function removefromcart(Product $product){
        $user = auth()->user();
        $cart = json_decode(auth()->user()->cart);

        if(($key = array_search($product->id, $cart)) !== false){
            unset($cart[$key]);
            $cart = array_values($cart);
        }

        $user->cart = json_encode($cart);
        $user->save();

        $products = Product::all();

        return redirect(route('cart', ['products' => $products]));

    }

    public function contact()
    {
        return view('contact');
    }
}
