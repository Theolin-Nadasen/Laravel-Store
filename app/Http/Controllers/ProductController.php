<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(){
        $products = Product::all();

        return view('products.index', ['products' => $products]);
    }

    public function create(){
        return view('products.create');
    }

    public function store(Request $request){
        $data = $request->validate([
            'name' => 'required',
            'qty' => 'required|numeric',
            'price' => 'required|decimal:2',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'description' => 'required'
        ]);

        // store image
        if($request->hasFile('image')){
            $imgPath = $request->file('image')->store('images', 'public');
            $data['image'] = $imgPath;
        }

        // store images
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('images', 'public');
            }
        }

        $data['images'] = $imagePaths;

        $newProduct = Product::create($data);
        
        return redirect(route('product.index'));
    }

    public function edit(Product $product) {

        return view('products.edit', ['product' => $product]);

    }

    public function update(Product $product, Request $request){
        $data = $request->validate([
            'name' => 'required',
            'qty' => 'required|numeric',
            'price' => 'required|decimal:2',
            'description' => 'required'
        ]);

        // store the thumbnail
        if($request->hasFile('image')){
            $imgPath = $request->file('image')->store('images', 'public');
            $data['image'] = $imgPath;
        }

        //store the other images
        
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('images', 'public');
            }
            $data['images'] = $imagePaths;
        }

        // clear all carts
        if(User::all()){

            foreach(User::all() as $user){
                $user->cart = null;
                $user->save();
            }

        }

        

        $product->update($data);

        return redirect(route('product.index'));
    }

    public function destroy(Product $product, Request $request){
        $product->delete();

        // clear all carts
        if(User::all()){

            foreach(User::all() as $user){
                $user->cart = null;
                $user->save();
            }

        }
        
        return redirect(route('product.index'));
    }
}
