<?php

function customAsset($path): string
{
    return config('app.env') === 'production' ? asset("public/{$path}") : asset($path);
}

function isVictimNear($currentLat, $currentLon, $targetLat, $targetLon, $target_distance = 1): bool
{
    $earthRadius = 6371; // Earth's radius in kilometers

    $dLat = deg2rad($targetLat - $currentLat);
    $dLon = deg2rad($targetLon - $currentLon);

    $a = sin($dLat / 2) * sin($dLat / 2) +
        cos(deg2rad($currentLat)) * cos(deg2rad($targetLat)) *
        sin($dLon / 2) * sin($dLon / 2);

    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    $distance = $earthRadius * $c;

    return $distance <= $target_distance;
}
