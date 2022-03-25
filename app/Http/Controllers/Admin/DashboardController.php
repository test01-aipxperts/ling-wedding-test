<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Product;
use App\UserProduct;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $activeusers = User::whereNotNull('email_verified_at')->where('is_admin',0)->count();
        $activeattachedproductsuserscount = User::whereNotNull('email_verified_at')
                                                ->where('is_admin',0)
                                                ->whereHas('pickupproduct',function($product){
                                                    $product->whereHas('product',function($query){
                                                        $query->where('status',1);
                                                    });
                                                })->count();

        $activeproducts = Product::where('status',1)->count();
        $activeproducthasnotpicks = Product::where('status',1)->whereDoesntHave('pickupproduct')->count();
        $totalactiveAttachedProducts = Product::where('status',1)
                                            ->join('user_products', 'user_products.product_id', '=', 'products.id')
                                            ->whereNull('user_products.qty')
                                            ->select('p.*','user_products.product_id','user_products.qty')
                                            ->sum('user_products.qty');

        $revenueactiveAttachedProducts =  UserProduct::whereHas('user',function($user){
                                                    $user->whereNotNull('email_verified_at')
                                                        ->where('is_admin',0);
                                                })
                                                ->whereHas('product',function($query){
                                                    $query->where('status',1);
                                                })->sum(\DB::raw('price * qty'));

        $activeattachedproductsusers = User::whereNotNull('email_verified_at')
                                                ->where('is_admin',0)
                                                ->whereHas('pickupproduct',function($product){
                                                    $product->whereHas('product',function($query){
                                                        $query->where('status',1);
                                                    });
                                                })->paginate(10);

        $activeattachedproductsusers->getCollection()->transform(function ($user, $key) {
                                                    $user->total_pickamount = $user->pickupproduct()
                                                                                ->whereHas('product',function($query){
                                                                                    $query->where('status',1);
                                                                                })
                                                                                ->sum(\DB::raw('price * qty'));
                                                    return $user;
                                                });

        $endpoint = 'latest';
        $access_key = '5284498bc18d50423eb4c695fc779342';

        // Initialize CURL:
        $ch = curl_init('http://api.exchangeratesapi.io/v1/'.$endpoint.'?access_key='.$access_key.'');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Store the data:
        $json = curl_exec($ch);
        curl_close($ch);

        // Decode JSON response:
        $exchangeRates = json_decode($json, true);

        // Access the exchange rate values, e.g. GBP:
        $ron_rate = $exchangeRates['rates']['RON'];
        $usd_rate = $exchangeRates['rates']['USD'];

        return view('admin.dashboard',compact('activeusers','activeattachedproductsuserscount','activeproducts','activeproducthasnotpicks','totalactiveAttachedProducts','revenueactiveAttachedProducts','activeattachedproductsusers','ron_rate','usd_rate'));
    }
}
