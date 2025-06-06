<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Models\Phone;

class RecommendationController extends Controller
{
    public function index()
    {
        return view('recommendation.index');
    }

    public function search(Request $request)
    {
        $query = Phone::query();

        if ($request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }
        if ($request->min_launched_year) {
            $query->where('launched_year', '>=', $request->min_launched_year);
        }
        if ($request->min_ram) {
            $query->where('ram', '>=', $request->min_ram);
        }
        if ($request->processor) {
            $query->where('processor', 'like', '%' . $request->processor . '%');
        }

        $phones = $query->get();
        foreach ($phones as $phone) {
            $baseName = strtolower(str_replace(' ', '', $phone->company_name));
            $extensions = ['jpg', 'jpeg'];
            $imageFound = false;

            foreach ($extensions as $ext) {
                $relativePath = "storage/images/phone/{$baseName}.{$ext}";

                if (File::exists(public_path($relativePath))) {
                    $phone->image_path = asset($relativePath);
                    $imageFound = true;
                    break;
                }
            }

            if (!$imageFound) {
                $phone->image_path = asset('images/phone/default.jpg');
            }
        }
        // dd($phones->first()->toArray());


        return view('recommendation.index', compact('phones'));
    }
}
