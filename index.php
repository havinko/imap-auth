<?php

$clientSecret = '';
$clientId = '';
$tenatId = '';

use Javanile\Imap2\Connection;

require_once 'vendor/autoload.php';

// Get Auth code in bearer
$url = 'https://login.microsoftonline.com/'.$tenatId.'/oauth2/v2.0/token';
$data = array(
    'client_id' => $clientId,
    'scope' => 'https://graph.microsoft.com/.default',
    'client_secret' => $clientSecret,
    'grant_type' => 'client_credentials');

// use key 'http' even if you send the request to https://...
$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data)
    )
);

$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);

$dataFromMicrosoft = json_decode($result);
$token = $dataFromMicrosoft->access_token;

$user = 'mdstoue@universaledition.com';

$username = 'mdstoue@universaledition.com';
$accessToken = base64_encode("user=".$username."^Aauth=Bearer " .$token."^A^A");

$mailbox = '{outlook.office365.com:993/imap/ssl/novalidate-cert}';
$imap = new Connection($mailbox, $username, $token, OP_XOAUTH2);
$imap->openMailbox($mailbox);


$asd = imap2_open($mailbox, $username, $accessToken, OP_XOAUTH2);

if (! $asd) {
    error_log(imap2_last_error());
    throw new \RuntimeException('Unable to open the INBOX');
}
var_dump($asd);
exit;