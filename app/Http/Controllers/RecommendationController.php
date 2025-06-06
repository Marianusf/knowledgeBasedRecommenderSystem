<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Models\Phone;

class RecommendationController extends Controller
{
    public function index()
    {
        // Kirim variabel kosong agar tidak error di view
        return view('recommendation.index', [
            'rankedPhones' => collect(),
            'allPhones' => collect(),
        ]);
    }

    public function searchHybrid(Request $request)
    {
        $constraintQuery = Phone::query();

        if ($request->max_price) {
            $constraintQuery->where('price', '<=', $request->max_price);
        }
        if ($request->min_launched_year) {
            $constraintQuery->where('launched_year', '>=', $request->min_launched_year);
        }
        if ($request->min_ram) {
            $constraintQuery->where('ram', '>=', $request->min_ram);
        }
        if ($request->processor) {
            $constraintQuery->where('processor', 'like', '%' . $request->processor . '%');
        }

        $constraintPhones = $constraintQuery->get();
        $caseBasedPhones = Phone::all();

        $calculateSimilarity = function ($phone) use ($request) {
            $score = 0;
            $count = 0;

            if ($request->min_ram && $phone->ram) {
                $score += 1 - abs($phone->ram - $request->min_ram) / max($phone->ram, $request->min_ram);
                $count++;
            }

            if ($request->min_launched_year && $phone->launched_year) {
                $score += 1 - abs($phone->launched_year - $request->min_launched_year) / max($phone->launched_year, $request->min_launched_year);
                $count++;
            }

            return $count > 0 ? $score / $count : 0;
        };

        $rankedPhones = $constraintPhones->map(function ($phone) use ($calculateSimilarity) {
            $phone->similarity_score = $calculateSimilarity($phone);
            return $phone;
        })->sortByDesc('similarity_score')->values();

        $allPhones = $caseBasedPhones->map(function ($phone) use ($calculateSimilarity) {
            $phone->similarity_score = $calculateSimilarity($phone);
            return $phone;
        })->sortByDesc('similarity_score')->values();

        $addImagePath = function ($phone) {
            $baseName = strtolower(str_replace(' ', '', $phone->company_name . '_' . $phone->model_name));
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

            return $phone;
        };

        $rankedPhones = $rankedPhones->map($addImagePath);
        $allPhones = $allPhones->map($addImagePath);

        return view('recommendation.index', compact('rankedPhones', 'allPhones'));
    }




    // public function search(Request $request)
    // {
    //     $query = Phone::query();

    //     if ($request->max_price) {
    //         $query->where('price', '<=', $request->max_price);
    //     }
    //     if ($request->min_launched_year) {
    //         $query->where('launched_year', '>=', $request->min_launched_year);
    //     }
    //     if ($request->min_ram) {
    //         $query->where('ram', '>=', $request->min_ram);
    //     }
    //     if ($request->processor) {
    //         $query->where('processor', 'like', '%' . $request->processor . '%');
    //     }

    //     $phones = $query->get();
    //     foreach ($phones as $phone) {
    //         $baseName = strtolower(str_replace(' ', '', $phone->company_name));
    //         $extensions = ['jpg', 'jpeg'];
    //         $imageFound = false;

    //         foreach ($extensions as $ext) {
    //             $relativePath = "storage/images/phone/{$baseName}.{$ext}";

    //             if (File::exists(public_path($relativePath))) {
    //                 $phone->image_path = asset($relativePath);
    //                 $imageFound = true;
    //                 break;
    //             }
    //         }

    //         if (!$imageFound) {
    //             $phone->image_path = asset('images/phone/default.jpg');
    //         }
    //     }
    //     // dd($phones->first()->toArray());


    //     return view('recommendation.index', compact('phones'));
    // }
}
