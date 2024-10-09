<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
  /**
   * Display login of the resource.
   *
   * @return \Illuminate\View\View
   */
  public function login()
  {
    $title = "Login";
    $description = "Some description for the page";
    return view('auth.login', compact('title', 'description'));
  }

  /**
   * make the user able to login
   *
   * @return
   */
  public function authenticate(Request $request)
  {
    $validators = Validator::make($request->all(), [
      'username' => 'required',
      'password' => 'required'
    ]);
    if ($validators->fails()) {
      return redirect()->route('login')->withErrors($validators)->withInput();
    } else {
      if (Auth::attempt(['username' => $request->username, 'password' => $request->password], $request->has('remember'))) {
        return redirect()->intended(route('dashboard.index'))->with('message', 'Welcome back !');
      } else {
        return redirect()->route('login')->with('status', 'Login failed !Email/Password is incorrect !');
      }
    }
  }

  /**
   * make the user able to logout
   *
   * @return
   */
  public function logout()
  {
    Auth::logout();
    return redirect()->route('login')->with('message', 'Successfully Logged out !');
  }
}
