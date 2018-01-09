<?php
use google\appengine\api\cloud_storage\CloudStorageTools;

function write_data($filename, $time) {
    global $context;
    $url = 'https://blockchain.info/ticker';
    $price = file_get_contents($url);
    $json = json_decode($price, true);
    $thb = 0;
    $usd = 0;
    if (isset($json['THB']['last'])) {
        $thb = $json['THB']['last'];
    }
    if (isset($json['USD']['last'])) {
        $usd = $json['USD']['last'];
    }
    $content = array('thb' => $thb, 'usd' => $usd, 'time' => $time);
    file_put_contents($filename, json_encode($content), 0, $context);
    return array('THB' => $thb, 'USD' => $usd);
}

$now = time();
$default_bucket = CloudStorageTools::getDefaultGoogleStorageBucketName();
$options = array('gs' => array('acl' => 'public-read', 'Content-Type' => 'application/json'));
$context = stream_context_create($options);
$filename = "gs://${default_bucket}/price.json";
$file_content = file_get_contents($filename);
if ($file_content !== false) {
    $data = json_decode($file_content, true);
    if (isset($data['time']) && ($now > (int) $data['time'] + 900)) {
        $output = write_data($filename, $now);
    } else {
        $output = array('THB' => $data['thb'], 'USD' => $data['usd']);
    }
} else {
    $output = write_data($filename, $now);
}
echo json_encode($output);
