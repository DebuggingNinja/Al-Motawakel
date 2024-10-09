<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class UsersController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
    $this->middleware('can:view users')->only('index');
    $this->middleware('can:add users')->only('create', 'store');
    $this->middleware('can:update users')->only('update', 'edit');
    $this->middleware('can:delete users')->only('destroy');
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
    $users = User::where([
      ['name', '!=', Null],
      [function ($query) use ($request) {
        if (($term = $request->search)) {
          $query->orWhere('name', 'LIKE', '%' . $term . '%')
            ->orWhere('username', 'LIKE', '%' . $term . '%')
            ->orWhere('email', 'LIKE', '%' . $term . '%')
            ->get();
        }
      }]
    ])->paginate($per_page);
    $title = "Users";
    $description = "Show Users";
    return view('users.index', compact('title', 'description', 'users'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $title = "Create new User";
    $description = "Create New User Page";
    $permissions = Permission::all();
    return view('users.create', compact('title', 'description', 'permissions'));
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
      'username' => 'required|unique:users',
      'email' => 'email|unique:users|nullable',
      'password' => 'required|confirmed'
    ]);
    $user = User::create([
      'name' => $request->name,
      'username' => $request->username,
      'email' => $request->email,
      'password' => Hash::make($request->password)
    ]);
    $user->givePermissionTo($request->permissions);
    if (app()->getLocale() == 'en') {
// //     toastr()->success('User Created Successfully');
    } else {
// //     toastr()->success('تم إنشاء المستخدم بنجاح');
    }
    return redirect()->route('users.index',)->with('success', 'User Created Successfully');
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
    $title         = 'Edit User';
    $description   = 'Edit User Page';
    $user = User::findOrFail($id);
    $permissions = Permission::all();
    return view('users.edit', compact('title', 'description', 'user', 'permissions'));
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
    $request->validate(['name' => 'required',
      'username' => 'required|unique:users,username,' . $id,
      'email' => 'nullable|email|unique:users,email,' . $id,
      'password' => 'nullable|confirmed',
      'status' => 'required|in:active,deactivated'
    ]);
    $user = User::findOrFail($id);
    $user->update([
      'name' => $request->name,
      'username' => $request->username,
      'email' => $request->email,
      'password' => $request->password == null ? $user->password : Hash::make($request->password),
      'status' => $request->status
    ]);
    $user->syncPermissions($request->permissions);
    if (app()->getLocale() == 'en') {
// //     toastr()->success('User Updated Successfully');
    } else {
// //     toastr()->success('تم تحديث المستخدم بنجاح');
    }
    return redirect()->route('users.index')->with('success', 'User Updated Successfully');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $find_user = User::findOrFail($id);
    $find_user->delete();
    if (app()->getLocale() == 'en') {
// //     toastr()->success('User Deleted Successfully');
    } else {
// //     toastr()->success('تم حذف المستخدم بنجاح');
    }
    return redirect()->route('users.index')->with('success', 'User Deleted Successfully');
  }
}
