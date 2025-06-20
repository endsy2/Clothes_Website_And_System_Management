<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Models\Product;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function PageHome(Request $request)
    {
        $countCustomer = (new CustomerController())->count()->getData(true);
        $countOrder = (new OrderController())->count()->getData(true);
        $totalRevenue = (new OrderController())->totalRevenues()->getData(true);
        $trendProduct = (new ProductController())->trendProduct()->getData(true);
        $AreaChart = (new GraphController())->DashBoardGraphArea()->getData(true);
        // $BarChart = (new GraphController())->DashBoardGraphBar()->getData(true);


        return view('admin.dashboard', [
            'countCustomer' => $countCustomer,
            'countOrder' => $countOrder,
            'totalRevenue' => $totalRevenue,
            'trendProduct' => $trendProduct,
            'areaChart' => $AreaChart['data']
        ]);
    }
    public function PageProduct(Request $request)
    {
        $products = Product::with(['brand', 'category', 'productType', 'productVariant.discount'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.product', ['products' => $products->toArray()]);
    }
    public function PageUser(Request $request)
    {
        $customers = (new CustomerController())->index()->getData(true);
        return view('admin.user', ['customers' => $customers]);
    }
    public function PageUserDetail(Request $request)
    {
        $customer = (new CustomerController())->show($request['id'])->getData(true);
        return view('admin.customer-detail', ['customers' => $customer]);
    }
    public function PageOrder(Request $request)
    {
        $orders = new OrderController()->index()->getData(true);
        $areaChartCustomer = new GraphController()->OrderGraphAreaCustomer()->getData(true);
        $areaChartSales = new GraphController()->OrderGraphAreaSales()->getData(true);


        // dd($areaChartSales);
        return view('admin.order', ['orders' => $orders, 'areaChartCustomer' => $areaChartCustomer, 'areaChartSales' => $areaChartSales]);
    }
    public function PageOrderDetail(Request $request)
    {
        $order = (new OrderController())->show($request['id'])->getData(true);
        return view('admin.orderDetail', ['orders' => $order]);
    }
    public function PageDiscount(Request $request)
    {
        $discounts_data = (new DiscountController())->discountName()->getData(true);
        $discounts = Discount::paginate(20);

        return view('admin.discount', compact('discounts', 'discounts_data'));
    }
}
