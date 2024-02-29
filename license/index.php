<?php
$file = './data.json';
if (!file_exists($file)) {
    file_put_contents($file, '{}');
    chmod($file, 0600);
}
$database = json_decode(file_get_contents($file), true);
if ($data = $database[$_REQUEST['domain']][$_REQUEST['product_id']]) {
    if ($data['expire'] == 'unlimited' || $data['expire'] > time()) {
        if ($_REQUEST['request_demo']) {
            echo "شما لایسنس فعال دارید.";
        } else {
            echo "true";
        }
        exit;
    }
}
if (filter_var($_REQUEST['domain'], FILTER_VALIDATE_DOMAIN)) {
    if ($_REQUEST['request_demo']) {
        if (!$database[$_REQUEST['domain']][$_REQUEST['product_id']]['request_demo']) {
            $database[$_REQUEST['domain']] = [];
            $database[$_REQUEST['domain']][$_REQUEST['product_id']] = ['expire' => time() + (60 * 60 * 24), 'request_demo' => true];
            file_put_contents($file, json_encode($database));
            echo "دمو این دامنه برای 24 ساعت فعال شد.";
        } else {
            echo "قبلا برای این دامنه درخواست دمو ارسال کرده اید و امکان درخواست دمو مجدد وجود ندارد.";
        }
        exit;
    }

    if (!$database[$_REQUEST['domain']][$_REQUEST['product_id']]) {
        $database[$_REQUEST['domain']] = [];
        $database[$_REQUEST['domain']][$_REQUEST['product_id']] = ['expire' => time()];
        file_put_contents($file, json_encode($database));
    }
}else{
    echo "دامنه وارد شده نامعتبر است.";
    exit;
}
echo "false";
exit;