<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(){
        // don't let them go if not admin
        if(!auth()->user()->admin){
            return redirect(route('landing'));
        }

        $products = Product::where('is_master', true)
                        ->orWhere(function ($query) {
                            $query->whereNull('parent_product_id')
                                  ->where('is_master', false);
                        })
                        ->get();

        return view('products.index', ['products' => $products]);
    }

    public function create(){
        // don't let them go if not admin
        if(!auth()->user()->admin){
            return redirect(route('landing'));
        }

        return view('products.create');
    }

    public function store(Request $request){
        // don't let them go if not admin
        if(!auth()->user()->admin){
            return redirect(route('landing'));
        }

        $data = $request->validate([
            'name' => 'required',
            'qty' => 'required|numeric',
            'price' => 'required|decimal:0,2',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'description' => 'required',
            'longdescription' => 'required',
            'has_variants' => 'nullable',
            'price_small' => 'nullable|decimal:0,2',
            'price_large' => 'nullable|decimal:0,2',
            'colors' => 'nullable|string',
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

        if ($request->has('has_variants')) {
            // Create the master product
            $masterProduct = Product::create([
                'name' => $data['name'],
                'qty' => $data['qty'],
                'price' => $data['price'], // Price for Normal
                'image' => $data['image'] ?? null,
                'images' => $data['images'] ?? [],
                'description' => $data['description'],
                'longdescription' => $data['longdescription'],
                'is_master' => true,
            ]);

            $sizes = [];
            if (isset($data['price'])) {
                $sizes['Normal'] = $data['price'];
            }
            if (isset($data['price_small'])) {
                $sizes['Small'] = $data['price_small'];
            }
            if (isset($data['price_large'])) {
                $sizes['Large'] = $data['price_large'];
            }

            $colors = [];
            if (!empty($data['colors'])) {
                $colors = array_map('trim', explode(',', $data['colors']));
            } else {
                $colors = [null]; // Default case if no colors are provided
            }

            foreach ($sizes as $sizeName => $sizePrice) {
                foreach ($colors as $colorName) {
                    Product::create([
                        'parent_product_id' => $masterProduct->id,
                        'name' => $masterProduct->name,
                        'qty' => $data['qty'], // Assuming qty is the same for all variants
                        'price' => $sizePrice,
                        'image' => $masterProduct->image,
                        'images' => $masterProduct->images,
                        'description' => $masterProduct->description,
                        'longdescription' => $masterProduct->longdescription,
                        'is_master' => false,
                        'size' => $sizeName,
                        'color' => $colorName,
                    ]);
                }
            }

        } else {
            // Create a simple product (as before)
            $newProduct = Product::create($data);
        }
        
        return redirect(route('product.index'));
    }

    public function edit(Product $product) {
        // don't let them go if not admin
        if(!auth()->user()->admin){
            return redirect(route('landing'));
        }

        return view('products.edit', ['product' => $product]);

    }

    public function update(Product $product, Request $request){
        // don't let them go if not admin
        if(!auth()->user()->admin){
            return redirect(route('landing'));
        }

        $data = $request->validate([
            'name' => 'required',
            'qty' => 'required|numeric',
            'price' => 'required|decimal:0,2',
            'description' => 'required',
            'longdescription' => 'required'
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
        // don't let them go if not admin
        if(!auth()->user()->admin){
            return redirect(route('landing'));
        }

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
