<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Image;

class ProductController extends Controller
{
    public function index()
    {
        $getProduct = Product::all();
        return response($getProduct);
    }

    public function getAuthenticatedProduct()
    {
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
        $getProduct = Product::all();
        return response($getProduct);
    }
    public function addProduct(Request $request)
    {
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
        
        $this->validate($request, [
            'product_name' => 'required|string|max:191',
            'product_price' => 'required|integer',
            'product_description' => 'required|string|max:191',
        ]);

        $data = $request->all();
        $strpos = strpos($request->product_image, ';');
        $sub = substr($request->product_image, 0, $strpos);
        $ex = explode('/', $sub)[1];
        $name = time() . "." . $ex;
        $img = Image::make($request->product_image)->resize(870, 350);
        $upload_path = public_path() . "/uploadimage/";
        $img->save($upload_path . $name);
        $data['upload_by'] = $user->id;
        $data['product_image'] = $name;
        Product::create($data);
        return response()->json(['success' => "Data added successfully"]);
    }
    public function editData($id)
    {
        
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        $product = Product::findOrfail($id);

        return response(["product"=>$product]);
    }
    public function updateData(Request $request)
    {
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
        $this->validate($request, [
            'product_name' => 'required|string|max:191',
            'product_price' => 'required|integer',
            'product_description' => 'required|string|max:191',
        ]);
        $data = $request->all();
        $id = $data['id'];
        $product = Product::findOrfail($id);
        if ($request->product_image != $product->product_image) {
            $strpos = strpos($request->product_image, ';');
            $sub = substr($request->product_image, 0, $strpos);
            $ex = explode('/', $sub)[1];
            $name = time() . "." . $ex;
            $img = Image::make($request->product_image)->resize(870, 350);
            $upload_path = public_path() . "/uploadimage/";
            $image = $upload_path . $product->product_image;
            $img->save($upload_path . $name);

            if (file_exists($image)) {
                @unlink($image);
            }
        } else {
            $name = $product->product_image;
        }

        $data['product_image'] = $name; 
        Product::where('id', $id)->update($data);

        return response()->json(['success' => "Data updated successfully"]);
    }
    public function deleteData($id)
    {
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
        $product = Product::findOrfail($id);
        $product->delete();
        return response()->json(['success' => "Data Deleted"]);
    }
}
