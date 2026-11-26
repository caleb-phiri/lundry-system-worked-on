<?php
/**
 * PWA Icon Generator Script
 * Run with: php generate-icons.php
 * Requires: GD extension with PNG support
 */

$sizes = [
    ['name' => '72x72', 'width' => 72, 'height' => 72],
    ['name' => '96x96', 'width' => 96, 'height' => 96],
    ['name' => '128x128', 'width' => 128, 'height' => 128],
    ['name' => '144x144', 'width' => 144, 'height' => 144],
    ['name' => '152x152', 'width' => 152, 'height' => 152],
    ['name' => '192x192', 'width' => 192, 'height' => 192],
    ['name' => '384x384', 'width' => 384, 'height' => 384],
    ['name' => '512x512', 'width' => 512, 'height' => 512],
];

$svgPath = __DIR__ . '/icon-source.svg';

if (!file_exists($svgPath)) {
    die("SVG source not found at $svgPath\n");
}

// Check if we have ImageMagick or GD
if (extension_loaded('imagick')) {
    generateWithImageMagick($sizes, $svgPath);
} elseif (extension_loaded('gd')) {
    generateWithGD($sizes, $svgPath);
} else {
    die("Neither ImageMagick nor GD extension is available. Please install one to generate icons.\n");
}

function generateWithImageMagick($sizes, $svgPath) {
    echo "Using ImageMagick...\n";
    
    foreach ($sizes as $size) {
        $outputPath = __DIR__ . "/icon-{$size['name']}.png";
        
        $cmd = sprintf(
            'magick convert -background none -resize %dx%d "%s" "%s"',
            $size['width'], $size['height'],
            $svgPath,
            $outputPath
        );
        
        exec($cmd, $output, $returnVar);
        
        if ($returnVar === 0) {
            echo "Generated: icon-{$size['name']}.png\n";
        } else {
            echo "Failed: icon-{$size['name']}.png (exit code: $returnVar)\n";
            echo implode("\n", $output) . "\n";
        }
    }
    
    // Generate badge icon (72x72 with just the symbol)
    $badgePath = __DIR__ . '/badge-72x72.png';
    exec(sprintf(
        'magick convert -background none -resize 72x72 -gravity center -extent 72x72 "%s" "%s"',
        $svgPath,
        $badgePath
    ));
    echo "Generated: badge-72x72.png\n";
    
    // Generate apple touch icon (180x180)
    $applePath = __DIR__ . '/icon-180x180.png';
    exec(sprintf(
        'magick convert -background none -resize 180x180 "%s" "%s"',
        $svgPath,
        $applePath
    ));
    echo "Generated: icon-180x180.png (Apple touch icon)\n";
    
    // Generate maskable icons
    foreach ($sizes as $size) {
        $maskablePath = __DIR__ . "/icon-{$size['name']}-maskable.png";
        $padding = max($size['width'], $size['height']) * 0.1;
        $safeSize = min($size['width'], $size['height']) * 0.8;
        
        exec(sprintf(
            'magick convert -background none -resize %dx%d -gravity center -extent %dx%d "%s" "%s"',
            $safeSize, $safeSize,
            $size['width'], $size['height'],
            $svgPath,
            $maskablePath
        ));
        echo "Generated: icon-{$size['name']}-maskable.png\n";
    }
}

function generateWithGD($sizes, $svgPath) {
    echo "Using GD (requires manual SVG to PNG conversion first)...\n";
    echo "Please convert the SVG to a 512x512 PNG first, then this script can resize it.\n";
    echo "You can use the generate-icons.html file in a browser to create the base PNG.\n";
    
    $sourcePng = __DIR__ . '/icon-512x512.png';
    
    if (!file_exists($sourcePng)) {
        die("Base PNG not found. Please generate it first using generate-icons.html\n");
    }
    
    $source = imagecreatefrompng($sourcePng);
    if (!$source) {
        die("Failed to load source PNG\n");
    }
    
    foreach ($sizes as $size) {
        if ($size['width'] === 512 && $size['height'] === 512) {
            // Already have this size
            echo "Skipping 512x512 (source)\n";
            continue;
        }
        
        $outputPath = __DIR__ . "/icon-{$size['name']}.png";
        $dest = imagecreatetruecolor($size['width'], $size['height']);
        
        // Preserve transparency
        imagealphablending($dest, false);
        imagesavealpha($dest, true);
        
        imagecopyresampled(
            $dest, $source,
            0, 0, 0, 0,
            $size['width'], $size['height'],
            imagesx($source), imagesy($source)
        );
        
        if (imagepng($dest, $outputPath)) {
            echo "Generated: icon-{$size['name']}.png\n";
        } else {
            echo "Failed: icon-{$size['name']}.png\n";
        }
        
        imagedestroy($dest);
    }
    
    // Generate badge
    $badge = imagecreatetruecolor(72, 72);
    imagealphablending($badge, false);
    imagesavealpha($badge, true);
    imagecopyresampled($badge, $source, 0, 0, 0, 0, 72, 72, imagesx($source), imagesy($source));
    imagepng($badge, __DIR__ . '/badge-72x72.png');
    echo "Generated: badge-72x72.png\n";
    
    // Generate apple touch icon
    $apple = imagecreatetruecolor(180, 180);
    imagealphablending($apple, false);
    imagesavealpha($apple, true);
    imagecopyresampled($apple, $source, 0, 0, 0, 0, 180, 180, imagesx($source), imagesy($source));
    imagepng($apple, __DIR__ . '/icon-180x180.png');
    echo "Generated: icon-180x180.png (Apple touch icon)\n";
    
    imagedestroy($source);
}

echo "\nDone! Icons generated in " . __DIR__ . "\n";
echo "Don't forget to update manifest.json with the correct icon paths.\n";