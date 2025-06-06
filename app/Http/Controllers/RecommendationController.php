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
        /* ===== 1. CONSTRAINT-BASED FILTER ===== */
        $query = Phone::query();

        // sinkron dengan nama field di form
        if ($request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }
        if ($request->min_launched_year) {
            $query->where('launched_year', '>=', $request->min_launched_year);
        }
        if ($request->min_ram) {
            $query->where('ram', '>=', $request->min_ram);
        }
        if ($request->battery_capacity) {                     // <─ sama dgn form
            $query->where('battery_capacity', '>=', $request->battery_capacity);
        }
        if ($request->preferred_brand) {                      // <─ sama dgn form
            $query->where('company_name', 'like', '%' . $request->preferred_brand . '%');
        }

        $constraintPhones = $query->get();


        /* ===== 2. CASE-BASED SIMILARITY (tanpa bobot) ===== */
        $similarity = function ($phone) use ($request) {
            $score = 0;
            $count = 0;

            if ($request->preferred_brand) {
                $score += strtolower($request->preferred_brand) === strtolower($phone->company_name) ? 1 : 0;
                $count++;
            }
            if ($request->min_ram) {
                $score += $phone->ram >= $request->min_ram ? 1 : 0;
                $count++;
            }
            if ($request->max_price) {
                $score += $phone->price <= $request->max_price ? 1 : 0;
                $count++;
            }
            if ($request->min_launched_year) {
                $score += $phone->launched_year >= $request->min_launched_year ? 1 : 0;
                $count++;
            }
            if ($request->battery_capacity) {
                $score += $phone->battery_capacity >= $request->battery_capacity ? 1 : 0;
                $count++;
            }
            return $count ? $score / $count : 0;
        };

        /* hasil utama: sudah lolos constraint + di-rank */
        $rankedPhones = $constraintPhones
            ->map(function ($p) use ($similarity) {
                $p->similarity_score = $similarity($p);
                return $p;
            })
            ->sortByDesc('similarity_score')
            ->values();

        /* alternatif: MASIH menggunakan data yg lolos constraint */
        $alternativePhones = $rankedPhones->slice(5);   // misalnya tampilkan 5 teratas sebagai utama, sisanya alternatif


        /* ===== 3. TAMBAH PATH GAMBAR ===== */
        $addImage = function ($p) {
            $base = strtolower(str_replace(' ', '', $p->company_name));

            foreach (['jpg', 'jpeg'] as $ext) {
                $rel = "storage/images/phone/{$base}.{$ext}";
                if (File::exists(public_path($rel))) {
                    $p->image_path = asset($rel);
                    return $p;
                }
            }

            $p->image_path = asset('images/phone/default.jpg');
            return $p;
        };

        $rankedPhones      = $rankedPhones->map($addImage);
        $alternativePhones = $alternativePhones->map($addImage);


        /* ===== 4. RETURN VIEW ===== */
        return view('recommendation.index', [
            'rankedPhones' => $rankedPhones->take(5),      // 5 terbaik
            'allPhones'    => $alternativePhones           // sisanya alternatif, tapi tetap memenuhi constraint
        ]);
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
