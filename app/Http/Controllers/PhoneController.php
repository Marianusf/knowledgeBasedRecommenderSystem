<?php

namespace App\Http\Controllers;

use App\Models\Phone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class PhoneController extends Controller
{
    public function index()
    {
        $phones = Phone::latest();

        if (request('search')) {
            $phones = $phones->where('company_name', 'like', '%' . request('search') . '%')
                ->orWhere('model_name', 'like', '%' . request('search') . '%');
        }

        $phones = $phones->paginate(12);

        return view('admin.phones.index', compact('phones'));
    }

    public function create()
    {
        return view('admin.phones.createEdit');
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'model_name' => 'required|string|max:255',
            'mobile_weight' => 'required|numeric|min:50|max:3000',
            'ram' => 'required|numeric|min:1|max:128',
            'front_camera' => 'required|string|max:255',
            'back_camera' => 'required|string|max:255',
            'processor' => 'required|string|max:255',
            'battery_capacity' => 'required|numeric|min:500|max:10000',
            'screen_size' => 'required|string|max:50',
            'launched_year' => 'required|integer|min:2000|max:' . now()->year,
            'price' => 'required|numeric|min:100000|max:99999999',
            'image' => 'nullable|image|mimes:jpg,jpeg|max:2048',
        ]);

        $phone = Phone::create($request->except('image'));

        if ($request->hasFile('image')) {
            $filename = strtolower(str_replace(' ', '', $request->company_name)) . '.' . $request->file('image')->extension();
            $request->file('image')->storeAs('public/images/phone', $filename);
        }

        return redirect()->route('admin.phones.index')->with('success', 'Data HP berhasil ditambahkan.');
    }

    public function edit(Phone $phone)
    {
        return view('admin.phones.createEdit', compact('phone'));
    }

    public function update(Request $request, Phone $phone)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'model_name' => 'required|string|max:255',
            'mobile_weight' => 'required|numeric|min:50|max:1000',
            'ram' => 'required|numeric|min:1|max:64',
            'front_camera' => 'required|string|max:255',
            'back_camera' => 'required|string|max:255',
            'processor' => 'required|string|max:255',
            'battery_capacity' => 'required|numeric|min:500|max:10000',
            'screen_size' => 'required|string|max:50',
            'launched_year' => 'required|integer|min:2000|max:' . now()->year,
            'price' => 'required|numeric|min:100000|max:99999999',
            'image' => 'nullable|image|mimes:jpg,jpeg|max:2048',
        ]);

        $phone->update($request->except('image'));

        if ($request->hasFile('image')) {
            $filename = strtolower(str_replace(' ', '', $request->company_name)) . '.' . $request->file('image')->extension();
            $request->file('image')->storeAs('public/images/phone', $filename);
        }

        return redirect()->route('admin.phones.index')->with('success', 'Data HP berhasil diperbarui.');
    }
    public function destroy(Phone $phone)
    {
        $phone->delete();
        return redirect()->route('admin.phones.index')->with('success', 'Phone deleted.');
    }
}
