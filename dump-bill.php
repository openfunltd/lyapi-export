<?php

$term = $_SERVER['argv'][1] ?? 10;
$limit = 100;
$bill_after = null;
$bill_show = new stdClass();
$output = fopen(__DIR__ . "/data/bill-" . str_pad($term, 2, '0', STR_PAD_LEFT) . ".jsonl", "w");
while (true) {
    if (is_null($bill_after)) {
        $url = sprintf("https://v2.ly.govapi.tw/bills?屆=%d&sort=議案編號>&output_fields=*&limit=%d", $term, $limit);
    } else {
        $url = sprintf("https://v2.ly.govapi.tw/bills?屆=%d&sort=議案編號>&output_fields=*&議案編號:,%s&limit=%d", $term, $bill_after, $limit);
    }
    error_log($url);
    $ret = json_decode(file_get_contents($url));
    $hit = 0;
    foreach ($ret->bills as $bill) {
        $bill_after = $bill->議案編號;
        if (property_exists($bill_show, $bill->議案編號)) {
            continue;
        }
        $bill_show->{$bill->議案編號} = true;
        fputs($output, json_encode($bill, JSON_UNESCAPED_UNICODE) . "\n");
        $hit ++;
    }
    if (!$hit) {
        break;
    }
}
