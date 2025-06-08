<?php

namespace App\Services;

use App\Models\Phone;

class RecommendationService
{
    public function recommend(array $input)
    {
        $phones = Phone::all();

        // Hard constraints: filter ketat
        $filtered = $phones->filter(function ($phone) use ($input) {
            if (isset($input['max_price']) && $phone->price > $input['max_price']) {
                return false;
            }
            if (isset($input['min_ram']) && $phone->ram < $input['min_ram']) {
                return false;
            }
            if (isset($input['preferred_brand']) && strtolower($phone->company_name) !== strtolower($input['preferred_brand'])) {
                return false;
            }
            if (isset($input['min_launched_year']) && $phone->launched_year < $input['min_launched_year']) {
                return false;
            }
            return true;
        });

        // Case-based scoring dengan explanations yang diperbaiki
        $scored = $filtered->map(function ($phone) use ($input) {
            $score = 0;
            $count = 0;
            $explanations = [];

            // Harga - dihitung proporsional berdasarkan selisih harga dibanding max_price
            if (isset($input['max_price'])) {
                $priceDiff = abs($input['max_price'] - $phone->price);
                $priceScore = max(0, 1 - $priceDiff / max(1, $input['max_price']));
                $score += $priceScore;
                $count++;
                if ($priceScore > 0.8) {
                    $explanations[] = "Harga sangat cocok";
                } elseif ($priceScore > 0.5) {
                    $explanations[] = "Harga cukup cocok";
                } else {
                    $explanations[] = "Harga kurang cocok";
                }
            }

            // RAM dengan threshold +2 GB dan scoring proporsional
            if (isset($input['min_ram'])) {
                $minRam = $input['min_ram'];
                $thresholdRam = $minRam + 2;

                if ($phone->ram >= $thresholdRam) {
                    $ramScore = 1;
                    $explanations[] = "RAM jauh di atas minimal";
                } elseif ($phone->ram >= $minRam) {
                    $ramScore = ($phone->ram - $minRam) / 2; // proporsional antara minRam dan minRam+2
                    $explanations[] = "RAM sedikit di atas minimal";
                } else {
                    $ramScore = 0;
                    $explanations[] = "RAM kurang";
                }

                $score += $ramScore;
                $count++;
            }

            // Baterai dengan threshold +500 mAh dan scoring proporsional
            if (isset($input['battery_capacity'])) {
                $minBattery = $input['battery_capacity'];
                $thresholdBattery = $minBattery + 500;

                if ($phone->battery_capacity >= $thresholdBattery) {
                    $batteryScore = 1;
                    $explanations[] = "Kapasitas baterai sangat memadai";
                } elseif ($phone->battery_capacity >= $minBattery) {
                    $batteryScore = ($phone->battery_capacity - $minBattery) / 500;
                    $explanations[] = "Kapasitas baterai cukup";
                } else {
                    $batteryScore = 0;
                    $explanations[] = "Kapasitas baterai kurang";
                }

                $score += $batteryScore;
                $count++;
            }

            // Tahun rilis - nilai biner 1 atau 0
            if (isset($input['min_launched_year'])) {
                $yearScore = $phone->launched_year >= $input['min_launched_year'] ? 1 : 0;
                $score += $yearScore;
                $count++;
                $explanations[] = $yearScore ? "Tahun rilis sesuai" : "Tahun rilis lama";
            }

            // Brand - nilai biner 1 atau 0
            if (isset($input['preferred_brand'])) {
                $brandScore = strtolower($phone->company_name) === strtolower($input['preferred_brand']) ? 1 : 0;
                $score += $brandScore;
                $count++;
                $explanations[] = $brandScore ? "Brand sesuai" : "Brand berbeda";
            }

            $phone->score = $count ? $score / $count : 0;
            $phone->explanations = $explanations;
            return $phone;
        })
            ->sortByDesc('score')
            ->values();

        return [
            'top' => $scored->take(5),
            'alternatives' => $scored->slice(5, 10)->values(),
        ];
    }
}
