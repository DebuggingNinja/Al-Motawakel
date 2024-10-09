<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
    $this->middleware('can:update settings');
  }
  public function colorSettings()
  {
    $settings = Setting::pluck('value', 'key')->all();
    $title         = 'Edit Color Settings';
    $description   = 'Edit Color Settings Page';

    return view('settings.colorSettings', compact('title', 'description', 'settings'));
  }
  public function updateColors(Request $request)
  {

    $colorPrimary       = Setting::firstOrCreate(['key' => 'colorPrimary'])->update(['value' => $request->input('colorPrimary')]);
    $colorPrimaryRgba   = Setting::firstOrCreate(['key' => 'colorPrimaryRgba'])->update(['value' => $request->input('colorPrimaryRgba')]);
    $bgPrimaryHover     = Setting::firstOrCreate(['key' => 'bgPrimaryHover'])->update(['value' => $request->input('bgPrimaryHover')]);
    $headerBg           = Setting::firstOrCreate(['key' => 'headerBg'])->update(['value' =>  $request->input('headerBg')]);
    $logoWrapperBg      = Setting::firstOrCreate(['key' => 'logoWrapperBg'])->update(['value' => $request->input('logoWrapperBg')]);
    if (app()->getLocale() == 'en') {
 //     toastr()->success('updated Successfully');
    } else {
 //     toastr()->success('تم التحديث بنجاح');
    }
    return redirect()->back()->with('success', 'updated Successfully');
  }
  public function edit()
  {
    $settings = Setting::pluck('value', 'key')->all();
    $title         = 'Edit Settings';
    $description   = 'Edit Settings Page';

    return view('settings.edit', compact('title', 'description', 'settings'));
  }
  // Update the logo
  public function updateLogo(Request $request)
  {
    $request->validate([
      'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:1024',
    ]);

    $setting = Setting::firstOrCreate(['key' => 'logo']);
    $logoPath = $request->file('logo')->store('logos', 'public');

    $setting->update(['value' => $logoPath]);
    if (app()->getLocale() == 'en') {
 //     toastr()->success('Logo updated Successfully');
    } else {
 //     toastr()->success('تم تحديث اللوجو بنجاح');
    }
    return redirect()->route('settings.edit')->with('success', 'updated Successfully');
  }

  // Display the current logo
  public function showLogo()
  {
    $logoPath = Setting::where('key', 'logo')->value('value');
    $defaultLogoPath = null;
    if (!$logoPath) {
      // If no logo is uploaded, use the default logo path
      $defaultLogoPath = 'logo.png'; // Update with your default logo path
      return response()->file(public_path($defaultLogoPath));
    } else {
      return response()->file(public_path("storage/{$logoPath}"));
    }
  }



  // Update the file_banner
  public function updateFileBanner(Request $request)
  {
    $request->validate([
      'file_banner' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $setting = Setting::firstOrCreate(['key' => 'file_banner']);

    $fileBannerPath = $request->file('file_banner')->store('banners', 'public');

    $setting->update(['value' => $fileBannerPath]);
    if (app()->getLocale() == 'en') {
 //     toastr()->success('File banner updated successfully');
    } else {
 //     toastr()->success('تم تحديث البانر بنجاح');
    }
    return redirect()->route('settings.edit')->with('success', 'updated Successfully');
  }

  // Update the file_banner
  public function updatePrintBanner(Request $request)
  {
    $request->validate([
      'print_banner' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $setting = Setting::firstOrCreate(['key' => 'print_banner']);

    $fileBannerPath = $request->file('print_banner')->store('banners', 'public');

    $setting->update(['value' => $fileBannerPath]);
    if (app()->getLocale() == 'en') {
      return redirect()->route('settings.edit')->with('success', 'File banner updated successfully');
    } else {
      return redirect()->route('settings.edit')->with('success', 'تم تحديث البانر بنجاح');
    }

  }

  public function updateYear(Request $request)
  {
    $setting = Setting::firstOrCreate(['key' => 'year']);
    $setting->update(['value' => $request->current_year]);
    if (app()->getLocale() == 'en') {
 //     toastr()->success('Year updated Successfully');
    } else {
 //     toastr()->success('تم تحديث السنه بنجاح');
    }
    return redirect()->route('settings.edit')->with('success', 'updated Successfully');
  }
  // Display the current file_banner
  public function showFileBanner()
  {
    $fileBannerPath = Setting::where('key', 'file_banner')->value('value');
    $defaultBannerPath = null;
    if (!$fileBannerPath) {
      $defaultBannerPath = 'ledger_file.jpg'; // Update with your default logo path
      return response()->file(public_path($defaultBannerPath));
    } else {
      return response()->file(public_path("storage/{$fileBannerPath}"));
    }
  }
}
