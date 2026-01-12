<?php
// save as getBitcoinPrice.php
$api_url = "https://api.coingecko.com/api/v3/simple/price?ids=bitcoin&vs_currencies=usd";

// Use cURL to fetch the API
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($ch);
curl_close($ch);

// Return JSON to browser
header('Content-Type: application/json');
echo $result;
?>