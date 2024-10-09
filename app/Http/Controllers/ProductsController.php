<?php

namespace App\Http\Controllers;

use App\Models\Broker;
use App\Models\Container;
use App\Models\ContainerClient;
use App\Models\ContainerItem;
use App\Models\Item;
use App\Models\Product;
use App\Models\Repository;
use App\Models\ShippingCompany;
use App\Services\Financials\BalanceCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
    $this->middleware('can:view products')->only('index');
    $this->middleware('can:add products')->only('create', 'store');
    $this->middleware('can:update products')->only('update', 'edit');
    $this->middleware('can:delete products')->only('destroy');
  }
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $per_page = session('pagination_per_page');
    $per_page = (!empty($per_page)) ? $per_page : 20;
    $page     = (!empty($_GET['page'])) ? $_GET['page'] : 1;
    $offset   = ($page * $per_page) - $per_page;
    $containers = Product::with([])->where([
      [function ($query) use ($request) {
        if (($term = $request->search)) {
          $query->orWhere('code', 'LIKE', '%' . $term . '%')
            ->orWhere('name', 'LIKE', '%' . $term . '%')
            ->get();
        }
      }]
    ])->paginate($per_page);
    $title = "Products";
    $description = "Show Products";
    return view('products.index', compact('title', 'description', 'containers'));
  }
  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $title = "Create new Product";
    $description = "Create New Product Page";
    return view('products.create', compact('title', 'description'));
  }
  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $request->validate([
      'code' => 'required|unique:products',
      'name' => 'required',
      'pieces_number' => 'required|numeric',
      'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024',
      'cbm' => 'required|numeric',
      'weight' => 'required|numeric',
      'measuring' => 'nullable|sometimes',
    ]);
    $imageName = null;
    if ($request->hasFile('image')) {
      $image = $request->file('image');
      $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
      $image->storeAs('public/product', $imageName);
    }
    $container = Product::create([
      'code' => $request->code,
      'name' => $request->name,
      'image' => $imageName,
      'pieces_number' => $request->pieces_number,
      'cbm' => $request->cbm,
      'weight' => $request->weight,
      'measuring' => $request->measuring,
    ]);
    if (app()->getLocale() == 'en') {
 //     toastr()->success('Product Added Successfully');
    } else {
 //     toastr()->success('تم إضافة الصنف');
    }
    return redirect()->route('products.index')->with('success', 'Product Created Successfully');
  }
  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $title         = 'Edit Product';
    $description   = 'Edit Product Page';
    $container = Product::findOrFail($id);
    return view('products.edit', compact('title', 'description', 'container'));
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
    $request->validate([
      'code' => 'required|unique:products,id,' . $id,
      'name' => 'required',
      'pieces_number' => 'required|numeric',
      'cbm' => 'required|numeric',
      'weight' => 'required|numeric',
      'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024',
      'measuring' => 'nullable|sometimes',
    ]);
    $container = Product::findOrFail($id);
    $imageName = $container->image;
    if ($request->hasFile('image')) {
      $image = $request->file('image');
      $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
      $image->storeAs('public/product', $imageName);
    }
    $container->update([
      'code' => $request->code,
      'name' => $request->name,
      'image' => $imageName,
      'pieces_number' => $request->pieces_number,
      'cbm' => $request->cbm,
      'weight' => $request->weight,
      'measuring' => $request->measuring,
    ]);
    if (app()->getLocale() == 'en') {
 //     toastr()->success('Product Updated Successfully');
    } else {
 //     toastr()->success('تم تحديث الصنف بنجاح');
    }
    return redirect()->route('products.index')->with('success', 'Product Updated Successfully');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $container = Product::findOrFail($id);
    $container->delete();
    if (app()->getLocale() == 'en') {
 //     toastr()->success('Product Deleted Successfully');
    } else {
 //     toastr()->success('تم حذف الصنف بنجاح');
    }
    return redirect()->route('products.index')->with('success', 'Product Deleted Successfully');
  }

}
