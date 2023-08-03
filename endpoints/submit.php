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


$tag = $_POST['tag'];
$state = $_POST['statec'];
$affid = $_POST['affid'] ?? null;
$oid = $_POST['oid'] ?? null;
$apiPayload = [
    'email' => $email,
    'firstName' => $firstName,
    'lastName' => $lastName,
    'phone' => $phone,
    //	'country'   => $country,
    'state' => $state,
    'name' => sprintf('%s %s', $firstName, $lastName),
    'address1' => $fullAddress,

    'postalCode' => $postalCode,
];
$consultor = $_POST['consultor'];
$customsFields = [
    'bf6Z12dI4R0IMwLgpqSC' => $propertyOwnership,
    'B5AsVW3l05R8ofllftMP' => $provider,
    'Pg93Hl6L7PWFHUEKkyvo' => $bill,
    '18joKpNF6obsRJXyg1Mb' => $roofShade,
];
$customsFields['Bji84fMEnv49A5cdIK7j'] = $affid;
$customsFields['QVkyPJzdOMdrNk39tljT'] = $oid;

$tag2 = null;
$api = "";
$apiPayload['customField'] = $customsFields;
$apiKey = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJsb2NhdGlvbl9pZCI6InV3VFRkTXFpNmZFdEhBYlVCV2JpIiwiY29tcGFueV9pZCI6IkI1ZkRGM0g3RmUwOVFCVlN3VFpoIiwidmVyc2lvbiI6MSwiaWF0IjoxNjcwODk3ODk5MDI1LCJzdWIiOiJBV202c1F1NEFUOEtMV0FRSWVrQSJ9.pojXDxMNlrg7U0rVZH-aetkzTQpSd0GmCOlvTeeoaWA";

if ($tag) {

    $apiPayload['tags'] = [$tag];
    if ($tag2) {
        $apiPayload['tags'][1] = $tag2;
    }
}

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

$apiPayload['propertyOwnerShip'] = $propertyOwnership;
$apiPayload['provider'] = $provider;
$apiPayload['bill'] = $bill;
$apiPayload['roofShade'] = $roofShade;
$apiPayload['affid'] = $affid;
$apiPayload['oid'] = $oid;
$apiPayload['consultor'] = $consultor;

$msg = null;
try {
    // NOSOTROS USAMOS UNA BASE DE DATOS APARTE A GOHIGHLEVEL, ESTO QUITALO SI NO LO USAS
    // SI USTEDES LO USAN HABLAME Y TE DOY MAS CODIGO DE ESO
    $db->store('leads', $apiPayload);
} catch (Exception $e) {
    $msg = $e->getMessage();
    file_put_contents('./log_' . date("j.n.Y") . '.log', $msg, FILE_APPEND);
    echo json_encode(['code' => 500, 'body' => null, 'tag' => $tag, 'msg' => $msg]);
}

echo json_encode(['code' => 200, 'body' => $response, 'tag' => $tag, 'msg' => $msg]);
die();
