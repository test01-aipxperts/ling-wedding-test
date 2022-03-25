<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Product;
use Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::paginate(10);
        return view('admin.products.index',compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'max:255'],
            'description' => ['required'],
            'image' => ['required', 'mimes:jpg,bmp,png'],
        ]);

         if ($validator->fails()) {
            return redirect()
                        ->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $filename = '';
        if($request->hasFile('image')){
            $file = $request->file('image');
            $filename = time().'.'.$file->getClientOriginalExtension();
            $file->storeAs('public',$filename);
        }

        Product::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $filename
        ]);

        return redirect()->route('admin.products.index')->with('success','Product added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // die('test');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::find($id);
        return view('admin.products.edit',compact('product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'max:255'],
            'description' => ['required'],
            'image' => ['mimes:jpg,bmp,png'],
            'status' => ['required']
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)>withInput();
        }

        $product = Product::find($id);
        $product->title = $request->title;
        $product->description = $request->description;
        $product->status = $request->status;

        if($request->hasFile('image')){
            $file = $request->file('image');
            $filename = time().'.'.$file->getClientOriginalExtension();
            $file->storeAs('public',$filename);
            $product->image = $filename;
        }

        $product->save();

        return redirect()->route('admin.products.index')->with('success','Product updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        if(file_exists(public_path('storage/'.$product->getOriginal('image'))) && $product->getOriginal('image') != ''){
            unlink(public_path('storage/'.$product->getOriginal('image')));
        }
        $product->delete();
        return redirect()->route('admin.products.index')->with('success','Product deleted successfully');

    }
}
