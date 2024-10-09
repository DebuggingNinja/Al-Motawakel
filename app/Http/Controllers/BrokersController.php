<?php

namespace App\Http\Controllers;

use App\Models\Broker;
use Illuminate\Http\Request;

class BrokersController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
    $this->middleware('can:view brokers')->only('index');
    $this->middleware('can:add brokers')->only('create', 'store');
    $this->middleware('can:update brokers')->only('update', 'edit');
    $this->middleware('can:delete brokers')->only('destroy');
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
    $brokers = Broker::where([
      ['name', '!=', Null],
      [function ($query) use ($request) {
        if (($term = $request->search)) {
          $query->orWhere('name', 'LIKE', '%' . $term . '%')
            ->orWhere('email', 'LIKE', '%' . $term . '%')
            ->orWhere('phone', 'LIKE', '%' . $term . '%')
            ->orWhere('phone2', 'LIKE', '%' . $term . '%')
            ->orWhere('code', 'LIKE', '%' . $term . '%')
            ->get();
        }
      }]
    ])->paginate($per_page);
    $title = "Brokers";
    $description = "Show Brokers";
    return view('brokers.index', compact('title', 'description', 'brokers'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $title = "Create new Broker";
    $description = "Create New Broker Page";
    return view('brokers.create', compact('title', 'description'));
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
      'name' => 'required',
      'code' => 'required|unique:brokers,code',
      'email' => 'email|unique:brokers|nullable',
      'phone' => 'nullable|unique:brokers',
      'phone2' => 'nullable|unique:brokers',
      'address' => 'nullable',
    ]);
    Broker::create([
      'name' => $request->name,
      'code' => $request->code,
      'email' => $request->email,
      'phone' => $request->phone,
      'phone2' => $request->phone2,
      'address' => $request->address,
    ]);
    if (app()->getLocale() == 'en') {
// //     toastr()->success('Broker Created Successfully');
    } else {
// //     toastr()->success('تم إنشاء المخلص بنجاح');
    }
    return redirect()->route('brokers.index',)->with('success', 'Broker Created Successfully');
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $title         = 'Edit Broker';
    $description   = 'Edit Broker Page';
    $broker = Broker::findOrFail($id);
    return view('brokers.edit', compact('title', 'description', 'broker'));
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
      'name' => 'required',
      'code' => 'required|unique:brokers,code,' . $id,
      'email' => 'nullable|email|unique:brokers,email,' . $id,
      'phone' => 'nullable|unique:brokers,phone,' . $id,
      'phone2' => 'nullable|unique:brokers,phone2,' . $id,
      'address' => 'nullable',
    ]);
    $broker = Broker::findOrFail($id);
    $broker->update([
      'name' => $request->name,
      'code' => $request->code,
      'email' => $request->email,
      'phone' => $request->phone,
      'phone2' => $request->phone2,
      'address' => $request->address,
    ]);
    if (app()->getLocale() == 'en') {
// //     toastr()->success('Broker Updated Successfully');
    } else {
// //     toastr()->success('تم تحديث المخلص بنجاح');
    }
    return redirect()->route('brokers.index')->with('success', 'Broker Updated Successfully');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $find_broker = Broker::findOrFail($id);
    $find_broker->delete();
    if (app()->getLocale() == 'en') {
// //     toastr()->success('Broker Deleted Successfully');
    } else {
// //     toastr()->success('تم حذف المخلص بنجاح');
    }
    return redirect()->route('brokers.index')->with('success', 'Broker Deleted Successfully');
  }
}
