<?php
// send_verification.php

// Replace these with your actual Twilio Account SID, Auth Token, and Verify Service SID
$accountSid = 'AC8431222f0df64cc3e4c1955c5eb8df65';
$authToken = 'fea48f953e0c41755bc5a226df0aba95';
$verifyServiceSid = 'VA161cf8718dd5f5987f5fa47252e6660f';

// Get the recipient number from the client-side application
$recipientNumber = $_POST['recipient'];
$locale = $_POST['locale'];


// Verify the recipient number using Twilio Verify
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://verify.twilio.com/v2/Services/$verifyServiceSid/Verifications");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/x-www-form-urlencoded',
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'To' => $recipientNumber,
    'Channel' => 'sms', // Verification code will be sent via SMS
    'locale' => $locale
]));

curl_setopt($ch, CURLOPT_USERPWD, "$accountSid:$authToken");
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Check if the Verify request was successful
if ($httpCode >= 200 && $httpCode < 300) {
    $responseObj = json_decode($response, true);

    if ($responseObj['status'] === 'pending') {
        // Verification code sent successfully
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'message' => 'Verification code sent successfully!']);
    } else {
        // Verification code request failed
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Failed to send verification code.']);
    }
} else {
    // Verify request failed
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Failed to send verification code.']);
}
?>
