<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use Validator;
use App\UserProduct;

class ProductController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $products = Product::where('status',1)->get()->map(function($item) use ($user){
                                        $count = $item->pickupproduct->where('user_id',$user->id)->count();
                                        $item->pickedup = ($count > 0) ? 1 : 0;
                                        return $item;
                                    });
        return view('home',compact('products'));
    }

    /* pick/ editpick product */
    public function pick(Request $request, $id){
        $user = auth()->user();
        $id = \Crypt::decryptString($id);
        $product = Product::find($id);
        if(!$product || !$product->status ){
            return redirect()->back()->with('error','Product not available');
        }
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'qty' => ['required', 'numeric'],
                'price' => ['required','numeric'],
            ]);

            if ($validator->fails()) {
                return redirect()->back()>withErrors($validator)->withInput();
            }
            UserProduct::updateOrCreate(['user_id' => $user->id, 'product_id' => $id],
                                    ['user_id' => $user->id, 'product_id' => $id,'qty' => $request->qty,'price' => $request->price]);
            return redirect()->route('home')->with('success','Product added');
        }

        $pickedup = $product->pickupproduct->where('user_id',$user->id)->first();
        $product->pickedup = $pickedup;

        return view('pick',compact('product'));
    }

    /* remove pick */
    public function removepick($id)
    {
        $id = \Crypt::decryptString($id);
        $user = auth()->user();
        $product = UserProduct::where(['user_id' => $user->id, 'product_id' => $id],)->delete();
        return redirect()->route('admin.products.index')->with('success','Product deleted successfully');

    }

}
