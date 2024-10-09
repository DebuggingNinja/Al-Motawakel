<?php

namespace App\Http\Controllers;

use App\Models\Repository;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class RepositoriesController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
    $this->middleware('can:view repositories')->only('index');
    $this->middleware('can:add repositories')->only('create', 'store');
    $this->middleware('can:update repositories')->only('update', 'edit');
    $this->middleware('can:delete repositories')->only('destroy');
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
    $repos = Repository::where([
      ['name', '!=', Null],
      [function ($query) use ($request) {
        if (($term = $request->search)) {
          $query->orWhere('name', 'LIKE', '%' . $term . '%')
            ->get();
        }
      }]
    ])->paginate($per_page);
    $title = "Repositories";
    $description = "Show Repositories";
    return view('repositories.index', compact('title', 'description', 'repos'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $title = "Create new Repository";
    $description = "Create New Repository Page";
    return view('repositories.create', compact('title', 'description'));
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
      'name' => 'required|unique:repositories',
      'address' => 'nullable',
      'code' => 'required|unique:repositories,code'
    ]);
    Repository::create([
      'name' => $request->name,
      'address' => $request->address,
      'code' => $request->code,
    ]);
    if (app()->getLocale() == 'en') {
 //     toastr()->success('Repository Created Successfully');
    } else {
 //     toastr()->success('تم إنشاء المستودع بنجاح');
    }
    return redirect()->route('repositories.index',)->with('success', 'Repository Created Successfully');
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
    $title         = 'Edit Repository';
    $description   = 'Edit Repository Page';
    $repo = Repository::findOrFail($id);
    return view('repositories.edit', compact('title', 'description', 'repo'));
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
      'name' => 'required|unique:repositories,name,' . $id,
      'address' => 'nullable',
      'code' => 'required|unique:repositories,code,' .  $id,
    ]);
    $repo = Repository::findOrFail($id);
    $repo->update([
      'name' => $request->name,
      'address' => $request->address,
    ]);
    if (app()->getLocale() == 'en') {
 //     toastr()->success('Repository Updated Successfully');
    } else {
 //     toastr()->success('تم تحديث المستودع بنجاح');
    }
    return redirect()->route('repositories.index')->with('success', 'Repository Updated Successfully');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $find_company = Repository::findOrFail($id);
    $find_company->delete();
    if (app()->getLocale() == 'en') {
 //     toastr()->success('Repository Deleted Successfully');
    } else {
 //     toastr()->success('تم حذف المستودع بنجاح');
    }
    return redirect()->route('repositories.index')->with('success', 'Repository Deleted Successfully');
  }
}
