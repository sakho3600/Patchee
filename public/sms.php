<?php
require __DIR__ . '/../vendor/autoload.php';

// Use the REST API Client to make requests to the Twilio REST API
use Twilio\Rest\Client;

function sendSMS($toNumber, $fromNumber, $message)
{
    // Your Account SID and Auth Token from twilio.com/console
    $sid = 'AC6eb56ec296fd7b65ed3ef0f6c8dbe138';
    $token = 'your_auth_token';
    $client = new Client($sid, $token);

    $client->messages->create(
    // the number you'd like to send the message to
        $toNumber,
        array(
            // A Twilio phone number you purchased at twilio.com/console
            'from' => $fromNumber,
            // the body of the text message you'd like to send
            'body' => $message
        )
    );
}