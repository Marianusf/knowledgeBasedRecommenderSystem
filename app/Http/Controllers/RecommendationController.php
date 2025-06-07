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
        $query = Phone::query();

        if ($request->max_price) {
            $minPrice = max(0, $request->max_price - 300000);
            $maxPrice = $request->max_price + 300000;
            $query->whereBetween('price', [$minPrice, $maxPrice]);
        }
        if ($request->min_launched_year) {
            $query->where('launched_year', '>=', $request->min_launched_year);
        }
        if ($request->min_ram) {
            $query->where('ram', '>=', $request->min_ram - 1);
        }
        if ($request->battery_capacity) {
            $query->where('battery_capacity', '>=', $request->battery_capacity - 500);
        }
        if ($request->preferred_brand) {
            $query->whereRaw('LOWER(company_name) = ?', [strtolower($request->preferred_brand)]);
        }

        $constraintPhones = $query->get();

        $similarity = function ($phone) use ($request) {
            $score = 0;
            $count = 0;

            if ($request->preferred_brand) {
                if (strtolower($request->preferred_brand) !== strtolower($phone->company_name)) return 0;
                $score += 1;
                $count++;
            }

            if ($request->min_ram) {
                $diff = abs($phone->ram - $request->min_ram);
                if ($diff <= 1) {
                    $score += 1 - ($diff / 1);
                    $count++;
                } else return 0;
            }

            if ($request->max_price) {
                $diff = abs($phone->price - $request->max_price);
                if ($diff <= 300000) {
                    $score += 1 - ($diff / 300000);
                    $count++;
                } else return 0;
            }

            if ($request->min_launched_year) {
                if ($phone->launched_year < $request->min_launched_year) return 0;
                $score += 1;
                $count++;
            }

            if ($request->battery_capacity) {
                $diff = abs($phone->battery_capacity - $request->battery_capacity);
                if ($diff <= 500) {
                    $score += 1 - ($diff / 500);
                    $count++;
                } else return 0;
            }

            return $count ? $score / $count : 0;
        };

        // Ranked phones
        $rankedPhones = $constraintPhones
            ->map(function ($p) use ($similarity) {
                $p->similarity_score = $similarity($p);
                return $p;
            })
            ->filter(fn($p) => $p->similarity_score > 0)
            ->sortByDesc('similarity_score')
            ->values();

        // Top 5 recommendations
        $topPhones = $rankedPhones->take(5);

        // Remaining alternatives from rankedPhones
        $rankedAlternatives = $rankedPhones->slice(5, 10)->values();

        // Additional alternatives from constraintPhones but not in rankedPhones
        $rankedIds = $rankedPhones->pluck('id')->toArray();
        $extraAlternatives = $constraintPhones
            ->filter(fn($p) => !in_array($p->id, $rankedIds))
            ->map(function ($p) {
                $p->similarity_score = null;
                return $p;
            })
            ->values();

        // Merge alternatives
        $alternativePhones = $rankedAlternatives->merge($extraAlternatives)->take(10);

        // Add image path
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

        $topPhones = $topPhones->map($addImage);
        $alternativePhones = $alternativePhones->map($addImage);

        return view('recommendation.index', [
            'rankedPhones' => $topPhones,
            'allPhones' => $alternativePhones,
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
// public function searchHybrid(Request $request)
// {
//     // 1. Constraint-Based Filter (hard filter)
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
//     if ($request->battery_capacity) {
//         $query->where('battery_capacity', '>=', $request->battery_capacity);
//     }
//     if ($request->preferred_brand) {
//         $query->where('company_name', 'like', '%' . $request->preferred_brand . '%');
//     }

//     $constraintPhones = $query->get();

//     // Data stats untuk normalisasi (ambil dari dataset Anda)
//     $maxBattery = 6000;    // misal max baterai dataset
//     $minBattery = 1000;    // min baterai dataset
//     $maxRam = 16;          // max RAM dataset
//     $maxPrice = 20000000;  // max harga dataset
//     $maxYearDiff = 10;     // max perbedaan tahun

//     // 2. Similarity with weighted distance
//     $similarity = function ($phone) use ($request, $maxBattery, $minBattery, $maxRam, $maxPrice, $maxYearDiff) {
//         $score = 0;
//         $count = 0;

//         if ($request->preferred_brand) {
//             // brand exact match = 1, else 0
//             $score += strtolower($request->preferred_brand) === strtolower($phone->company_name) ? 1 : 0;
//             $count++;
//         }
//         if ($request->min_ram) {
//             $ramDiff = abs($phone->ram - $request->min_ram);
//             $score += 1 - min(1, $ramDiff / $maxRam);
//             $count++;
//         }
//         if ($request->max_price) {
//             $priceDiff = max(0, $phone->price - $request->max_price);
//             $score += 1 - min(1, $priceDiff / $maxPrice);
//             $count++;
//         }
//         if ($request->min_launched_year) {
//             $yearDiff = abs($phone->launched_year - $request->min_launched_year);
//             $score += 1 - min(1, $yearDiff / $maxYearDiff);
//             $count++;
//         }
//         if ($request->battery_capacity) {
//             $batteryDiff = abs($phone->battery_capacity - $request->battery_capacity);
//             $score += 1 - min(1, $batteryDiff / ($maxBattery - $minBattery));
//             $count++;
//         }

//         return $count ? $score / $count : 0;
//     };

//     // 3. Hitung similarity dan sorting
//     $rankedPhones = $constraintPhones
//         ->map(function ($p) use ($similarity) {
//             $p->similarity_score = $similarity($p);
//             return $p;
//         })
//         ->sortByDesc('similarity_score')
//         ->values();

//     $alternativePhones = $rankedPhones->slice(5);

//     // 4. Tambah path gambar
//     $addImage = function ($p) {
//         $base = strtolower(str_replace(' ', '', $p->company_name));
//         foreach (['jpg', 'jpeg'] as $ext) {
//             $rel = "storage/images/phone/{$base}.{$ext}";
//             if (File::exists(public_path($rel))) {
//                 $p->image_path = asset($rel);
//                 return $p;
//             }
//         }
//         $p->image_path = asset('images/phone/default.jpg');
//         return $p;
//     };

//     $rankedPhones = $rankedPhones->map($addImage);
//     $alternativePhones = $alternativePhones->map($addImage);

//     // 5. Return view
//     return view('recommendation.index', [
//         'rankedPhones' => $rankedPhones->take(5),
//         'allPhones' => $alternativePhones,
//     ]);
// }
