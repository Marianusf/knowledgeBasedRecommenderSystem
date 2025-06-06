<?php

namespace App\Http\Controllers;

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

        return view('recommendation.index', compact('phones'));
    }
}
