<?php
if (!isset($_FILES['image'])) {
    exit("No image uploaded");
}

$uploadDir = __DIR__ . "\\uploads\\";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir);
}

/* =========================
   1. SAVE UPLOADED IMAGE
========================= */
$ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
$imageName = uniqid("img_") . "." . strtolower($ext);
$imagePath = $uploadDir . $imageName;

move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);

/* =========================
   2. LOAD IMAGE SAFELY
========================= */
switch ($ext) {
    case 'jpg':
    case 'jpeg':
        $img = imagecreatefromjpeg($imagePath);
        break;
    case 'png':
        $img = imagecreatefrompng($imagePath);
        break;
    default:
        exit("Unsupported image format");
}

if (!$img) {
    exit("Failed to load image");
}

/* =========================
   3. UPSCALE IMAGE (2x)
========================= */
$w = imagesx($img);
$h = imagesy($img);

$scaled = imagecreatetruecolor($w * 2, $h * 2);
imagecopyresampled(
    $scaled, $img,
    0, 0, 0, 0,
    $w * 2, $h * 2,
    $w, $h
);
imagedestroy($img);

/* =========================
   4. PREPROCESS IMAGE
========================= */
// Convert to grayscale
imagefilter($scaled, IMG_FILTER_GRAYSCALE);

// Increase contrast (negative = stronger)
imagefilter($scaled, IMG_FILTER_CONTRAST, -25);

// Sharpen text
imageconvolution(
    $scaled,
    [
        [-1, -1, -1],
        [-1, 16, -1],
        [-1, -1, -1]
    ],
    8,
    0
);

// Save preprocessed image
$processedPath = $uploadDir . "processed_" . $imageName;
imagepng($scaled, $processedPath, 9);
imagedestroy($scaled);

/* =========================
   5. RUN TESSERACT OCR
========================= */
$tesseractPath = '"C:\\Program Files\\Tesseract-OCR\\tesseract.exe"';
$outputFile = $uploadDir . "ocr_" . uniqid();

$command = $tesseractPath . " " .
           escapeshellarg($processedPath) . " " .
           escapeshellarg($outputFile) .
           " -l eng --oem 1 --psm 6";

exec($command, $out, $code);

if ($code !== 0) {
    exit("Tesseract OCR failed");
}

/* =========================
   6. READ OCR OUTPUT
========================= */
$text = file_get_contents($outputFile . ".txt");

/* =========================
   7. DISPLAY RESULT
========================= */
echo "<h3>Extracted Text</h3>";
echo "<pre style='white-space: pre-wrap;'>";
echo htmlspecialchars($text);
echo "</pre>";