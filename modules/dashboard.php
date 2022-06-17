<?php
//https://rapidapi.com/community/api/open-weather-map/
$curl = curl_init();

curl_setopt_array($curl, [
	CURLOPT_URL => "https://community-open-weather-map.p.rapidapi.com/weather?q=Melbourne%2Caus&lat=0&lon=0&callback=test&id=2172797&lang=null&units=metric&mode=xml",
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "GET",
	CURLOPT_HTTPHEADER => [
		"X-RapidAPI-Host: community-open-weather-map.p.rapidapi.com",
		"X-RapidAPI-Key: 5998ba99a4msh887ab7956a4a975p1aa7d3jsnf2d491c06098"
	],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
	echo "cURL Error #:" . $err;
} else {
	// $myArray = print_r_reverse($response);
	// $resArr = json_decode($response);
	echo $response;
}

?>


<p style="color: green;font-size: 18px;"><strong>Welcome to the backend of your WordPress web site!</strong></p>
<p>Some helpful advice is located here:</p>
<h2>
	<a href="https://www.lukeketchen.com/" target="_blank" style="text-decoration: underline; font-weight:strong;">Link to the help page</a>
</h2>
<p>Contact <a href="mailto:ketchlabs@gmail.com">Luke Ketchen</a> when questions arise.</p>
