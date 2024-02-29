<?php
$file = './data.json';
if (!file_exists($file)) {
    file_put_contents($file, '{}');
}
$database = json_decode(file_get_contents($file), true);
if ($data = $database[$_REQUEST['domain']][$_REQUEST['product_id']]) {
    if ($data['expire'] == 'unlimited' || $data['expire'] > time()) {
        echo true;
        exit;
    }
} else {
    $database[$_REQUEST['domain']] = [];
    $database[$_REQUEST['domain']][$_REQUEST['product_id']] = ['expire' => time()];
    file_put_contents($file, json_encode($database));
}
echo false;
exit;