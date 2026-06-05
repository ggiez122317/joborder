<?php
$imgPath = 'storage/app/public/id-templates/xVyp8mLoo4OAIraNE9AhJK8oMg5DN0J1joEtpYsj.jpg';
if (!file_exists($imgPath)) {
    die("File does not exist\n");
}
$im = imagecreatefromjpeg($imgPath);
list($width, $height) = getimagesize($imgPath);

echo "Dimensions: {$width}x{$height}\n";

// Scan horizontal band on the left (where labels are)
// Let's try x = 150 to 500
$rowBrightness = [];
for ($y = 0; $y < $height; $y++) {
    $darkCount = 0;
    for ($x = 100; $x < 550; $x++) {
        $rgb = imagecolorat($im, $x, $y);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;
        $gray = ($r + $g + $b) / 3;
        
        if ($gray < 150) {
            $darkCount++;
        }
    }
    $rowBrightness[$y] = $darkCount;
}

// Smooth it to find clean line peaks
$smoothed = [];
$window = 6;
for ($y = 0; $y < $height; $y++) {
    $sum = 0;
    $count = 0;
    for ($i = -$window; $i <= $window; $i++) {
        $cy = $y + $i;
        if ($cy >= 0 && $cy < $height) {
            $sum += $rowBrightness[$cy];
            $count++;
        }
    }
    $smoothed[$y] = $sum / $count;
}

echo "Local dark peaks (potential text rows):\n";
$peaks = [];
for ($y = 100; $y < $height - 100; $y++) {
    if ($smoothed[$y] > 10 && $smoothed[$y] >= $smoothed[$y-1] && $smoothed[$y] >= $smoothed[$y+1]) {
        // Ensure it is a distinct peak
        $isMax = true;
        for ($i = -25; $i <= 25; $i++) {
            $cy = $y + $i;
            if ($cy >= 0 && $cy < $height && $smoothed[$cy] > $smoothed[$y]) {
                $isMax = false;
                break;
            }
        }
        if ($isMax) {
            $peaks[] = [
                'y' => $y,
                'pct' => round(($y / $height) * 100, 2),
                'intensity' => $smoothed[$y]
            ];
            $y += 25; // Skip ahead
        }
    }
}

foreach ($peaks as $idx => $peak) {
    echo "Peak #{$idx}: y = {$peak['y']}, pct = {$peak['pct']}%, intensity = {$peak['intensity']}\n";
}
