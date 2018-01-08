<?php
use google\appengine\api\cloud_storage\CloudStorageTools;

function write_data($filename, $data_price, $data_time) {
    global $context;
    $content = array('last' => $data_price, 'time' => $data_time);
    file_put_contents($filename, json_encode($content), 0, $context);
}

$now = time();
$default_bucket = CloudStorageTools::getDefaultGoogleStorageBucketName();
$options = array('gs' => array('acl' => 'public-read'));
$context = stream_context_create($options);
$filename = "gs://${default_bucket}/price.json";

$url = 'https://blockchain.info/ticker';
$price = file_get_contents($url);
$json = json_decode($price, true);
if (isset($json['THB']['last'])) {
    $last = $json['THB']['last'];
    $file_content = file_get_contents($filename);
    if ($file_content !== false) {
        $data = json_decode($file_content, true);
        if (isset($data['time']) && ($now > (int) $data['time'] + 900)) {
            write_data($filename, $last, $now);
        }
    } else {
        write_data($filename, $last, $now);
    }
}
$output = array('THB' => $last);
echo json_encode($output);
