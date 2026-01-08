<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{

    public function __construct() {
        $this->middleware('permission:settings.index')->only(['index']);
        $this->middleware('permission:settings.create')->only(['create', 'store']);
        $this->middleware('permission:settings.update')->only(['edit', 'update']);
        $this->middleware('permission:settings.delete')->only('destroy');
    }


    public function index()
    {
        $settings = Setting::firstOrCreate([]);
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'company_name'   => 'required|string|max:255',
            'address'        => 'nullable|string',
            'phone'          => 'nullable|string|max:20',
            'email'          => 'nullable|email',
            'logo'           => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'invoice_logo'   => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'favicon'        => 'nullable|image|mimes:ico,png|max:512',
            'paid_stamp' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',  
        ]);

        $settings = Setting::first();

        $data = $request->only(['company_name', 'address', 'phone', 'email']);

        // Handle Logo Upload
        if ($request->hasFile('logo')) {
            if ($settings->logo) {
                Storage::delete($settings->logo);
            }
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        // Handle Invoice Logo
        if ($request->hasFile('invoice_logo')) {
            if ($settings->invoice_logo) {
                Storage::delete($settings->invoice_logo);
            }
            $data['invoice_logo'] = $request->file('invoice_logo')->store('logos', 'public');
        }

        // Handle Favicon
        if ($request->hasFile('favicon')) {
            if ($settings->favicon) {
                Storage::delete($settings->favicon);
            }
            $data['favicon'] = $request->file('favicon')->store('favicons', 'public');
        }

        if ($request->hasFile('paid_stamp')) {
            if ($settings->paid_stamp) {
                Storage::delete($settings->paid_stamp);
            }
            $data['paid_stamp'] = $request->file('paid_stamp')->store('stamps', 'public');  // folder 'stamps' bana len storage/app/public mein
        }

        $settings->update($data);

        return back()->with('success', 'Settings updated successfully!');
    }
}