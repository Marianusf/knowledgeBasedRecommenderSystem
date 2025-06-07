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
        $phones = Phone::latest()->paginate(10);
        return view('admin.phones.index', compact('phones'));
    }

    public function create()
    {
        return view('admin.phones.createEdit');
    }

    public function store(Request $request)
    {
        $request->validate([
            'model' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'launched_year' => 'required|integer|min:2000|max:' . now()->year,
            'ram' => 'required|numeric|min:1',
            'battery_capacity' => 'required|numeric|min:500',
            'image' => 'nullable|image|mimes:jpg,jpeg|max:2048',
        ]);

        $phone = Phone::create($request->except('image'));

        if ($request->hasFile('image')) {
            $filename = strtolower(str_replace(' ', '', $request->company_name)) . '.' . $request->file('image')->extension();
            $request->file('image')->storeAs('public/images/phone', $filename);
        }

        return redirect()->route('admin.phones.index')->with('success', 'Phone added.');
    }

    public function edit(Phone $phone)
    {
        return view('admin.phones.edit', compact('phone'));
    }

    public function update(Request $request, Phone $phone)
    {
        $request->validate([
            'model' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'launched_year' => 'required|integer|min:2000|max:' . now()->year,
            'ram' => 'required|numeric|min:1',
            'battery_capacity' => 'required|numeric|min:500',
            'image' => 'nullable|image|mimes:jpg,jpeg|max:2048',
        ]);

        $phone->update($request->except('image'));

        if ($request->hasFile('image')) {
            $filename = strtolower(str_replace(' ', '', $request->company_name)) . '.' . $request->file('image')->extension();
            $request->file('image')->storeAs('public/images/phone', $filename);
        }

        return redirect()->route('admin.phones.index')->with('success', 'Phone updated.');
    }

    public function destroy(Phone $phone)
    {
        $phone->delete();
        return redirect()->route('admin.phones.index')->with('success', 'Phone deleted.');
    }
}
