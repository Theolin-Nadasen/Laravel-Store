<?php

namespace App\Http\Controllers;

use App\Models\Product;
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
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg'
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
        ]);

        if($request->hasFile('image')){
            $imgPath = $request->file('image')->store('images', 'public');
            $data['image'] = $imgPath;
        }

        $product->update($data);

        return redirect(route('product.index'));
    }

    public function destroy(Product $product, Request $request){
        $product->delete();
        
        return redirect(route('product.index'));
    }
}
