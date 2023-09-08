<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank you!</title>
    <link rel="stylesheet" href="submit.css">
</head>
<body>
    <h1>Welcome to EcomfyLead: Your Journey Begin Here</h1>
    <h3>Thanks for signing up</h3>
    <p>We're thrilled to have you on board! This is not just a 'Thank You' page, it's the starting point of an exciting journey that we're going to undertake together.At EcomfyLead, we're committed to ensuring that your affiliate marketing endeavors are seamless, rewarding, and scalable. We're here to empower you, guide you, and celebrate your successes along the way.</p>
    <br>
    <p>Remember, this is more than just a sign-up. You've taken the first step towards transforming your affiliate marketing game. We're excited to see where this journey will take you.Once again, thank you for choosing EcomfyLead. Here's to scaling new heights together!</p> 
</body>
</html>
<?php

header('Content-type: application/json');

function clean($string)
{
    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

    $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

    return preg_replace('/-+/', '', $string);
}

$fullname = $_POST['name'];
$email = $_POST['email'];
$phone = clean($_POST['phone']);
$country = $_POST['country'];
$platform = $_POST['platform'];
$verticals = $_POST['verticals'];
$revenue = $_POST['revenue'];

$apiPayload = [
    'email' => $email,
    'name' => $name,
    'phone' => $phone,
    //	'country'   => $country,
    'country' => $country,
    'tags' => [
        "EcomfyForm",
        "lead"
    ]
];
$customsFields = [
    'AVPIjDVHmKv1Mtn2N1RS' => $platform,
    'O1g1kwjzgVyMkjisJmVW' => $verticals,
    '01UWVssoYoJnO1DF7ZC5' => $revenue,
];

$apiPayload['customField'] = $customsFields;
$apiKey = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJsb2NhdGlvbl9pZCI6InV3VFRkTXFpNmZFdEhBYlVCV2JpIiwiY29tcGFueV9pZCI6IkI1ZkRGM0g3RmUwOVFCVlN3VFpoIiwidmVyc2lvbiI6MSwiaWF0IjoxNjcwODk3ODk5MDI1LCJzdWIiOiJBV202c1F1NEFUOEtMV0FRSWVrQSJ9.pojXDxMNlrg7U0rVZH-aetkzTQpSd0GmCOlvTeeoaWA";

$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => "https://rest.gohighlevel.com/v1/contacts/",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => json_encode($apiPayload),
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer " . $apiKey,
        "Content-Type: application/json",
    ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    echo "cURL Error #:" . $err;
    file_put_contents('./log_' . date("j.n.Y") . '.log', $err, FILE_APPEND);
    die();
}

$msg = null;


echo json_encode(['code' => 200, 'body' => $response, 'tag' => $tag, 'msg' => $msg]);
die();
?>