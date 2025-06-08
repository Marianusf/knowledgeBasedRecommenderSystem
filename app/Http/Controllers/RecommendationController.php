<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Models\Phone;
use App\Services\RecommendationService;

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
        $service = new RecommendationService();
        $result = $service->recommend($request->all());

        $getImagePath = function ($companyName) {
            $baseFolder = storage_path('app/public/images/phone/');  // folder penyimpanan gambar
            $fileNameJpg = strtolower($companyName) . '.jpg';
            $fileNameJpeg = strtolower($companyName) . '.jpeg';

            if (File::exists($baseFolder . $fileNameJpg)) {
                return 'storage/images/phone/' . $fileNameJpg;
            } elseif (File::exists($baseFolder . $fileNameJpeg)) {
                return 'storage/images/phone/' . $fileNameJpeg;
            } else {
                return 'storage/images/phone/default.jpg'; // fallback jika gambar tidak ada
            }
        };

        foreach ($result['top'] as $phone) {
            $phone->image_path = $getImagePath($phone->company_name);
        }

        foreach ($result['alternatives'] as $phone) {
            $phone->image_path = $getImagePath($phone->company_name);
        }

        return view('recommendation.index', [
            'rankedPhones' => $result['top'],
            'allPhones' => $result['alternatives'],
        ]);
    }
}

//     public function searchHybrid(Request $request)
//     {
//         $query = Phone::query();

//         if ($request->max_price) {
//             $minPrice = max(0, $request->max_price - 300000);
//             $maxPrice = $request->max_price + 300000;
//             $query->whereBetween('price', [$minPrice, $maxPrice]);
//         }
//         if ($request->min_launched_year) {
//             $query->where('launched_year', '>=', $request->min_launched_year);
//         }
//         if ($request->min_ram) {
//             $query->where('ram', '>=', $request->min_ram - 1);
//         }
//         if ($request->battery_capacity) {
//             $query->where('battery_capacity', '>=', $request->battery_capacity - 500);
//         }
//         if ($request->preferred_brand) {
//             $query->whereRaw('LOWER(company_name) = ?', [strtolower($request->preferred_brand)]);
//         }

//         $constraintPhones = $query->get();

//         $similarity = function ($phone) use ($request) {
//             $score = 0;
//             $count = 0;

//             if ($request->preferred_brand) {
//                 if (strtolower($request->preferred_brand) !== strtolower($phone->company_name)) return 0;
//                 $score += 1;
//                 $count++;
//             }

//             if ($request->min_ram) {
//                 $diff = abs($phone->ram - $request->min_ram);
//                 if ($diff <= 1) {
//                     $score += 1 - ($diff / 1);
//                     $count++;
//                 } else return 0;
//             }

//             if ($request->max_price) {
//                 $diff = abs($phone->price - $request->max_price);
//                 if ($diff <= 300000) {
//                     $score += 1 - ($diff / 300000);
//                     $count++;
//                 } else return 0;
//             }

//             if ($request->min_launched_year) {
//                 if ($phone->launched_year < $request->min_launched_year) return 0;
//                 $score += 1;
//                 $count++;
//             }

//             if ($request->battery_capacity) {
//                 $diff = abs($phone->battery_capacity - $request->battery_capacity);
//                 if ($diff <= 500) {
//                     $score += 1 - ($diff / 500);
//                     $count++;
//                 } else return 0;
//             }

//             return $count ? $score / $count : 0;
//         };

//         $rankedPhones = $constraintPhones
//             ->map(function ($p) use ($similarity) {
//                 $p->similarity_score = $similarity($p);
//                 return $p;
//             })
//             ->filter(fn($p) => $p->similarity_score > 0)
//             ->sortByDesc('similarity_score')
//             ->values();

//         $topPhones = $rankedPhones->take(5);
//         #ini mengambil 5 teratas, dan 10 opsi tambahan
//         $rankedAlternatives = $rankedPhones->slice(5, 10)->values();

//         $rankedIds = $rankedPhones->pluck('id')->toArray();
//         $extraAlternatives = $constraintPhones
//             ->filter(fn($p) => !in_array($p->id, $rankedIds))
//             ->map(function ($p) {
//                 $p->similarity_score = null;
//                 return $p;
//             })
//             ->values();

//         // gabungkan alternatif dengan opsi tambahan
//         $alternativePhones = $rankedAlternatives->merge($extraAlternatives)->take(10);

//         $addImage = function ($p) {
//             $base = strtolower(str_replace(' ', '', $p->company_name));
//             foreach (['jpg', 'jpeg'] as $ext) {
//                 $rel = "storage/images/phone/{$base}.{$ext}";
//                 if (File::exists(public_path($rel))) {
//                     $p->image_path = asset($rel);
//                     return $p;
//                 }
//             }
//             $p->image_path = asset('images/phone/default.jpg');
//             return $p;
//         };

//         $topPhones = $topPhones->map($addImage);
//         $alternativePhones = $alternativePhones->map($addImage);

//         return view('recommendation.index', [
//             'rankedPhones' => $topPhones,
//             'allPhones' => $alternativePhones,
//         ]);
//     }
// }
