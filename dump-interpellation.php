<?php

$term = $_SERVER['argv'][1] ?? 11;
$limit = 100;
$interpellation_show = new stdClass();
$output = fopen(__DIR__ . "/data/interpellation-" . str_pad($term, 2, '0', STR_PAD_LEFT) . ".jsonl", "w");
$page = 1;
while (true) {
    $url = sprintf("https://v2.ly.govapi.tw/interpellations?屆=%d&limit=%d&page=%d", $term, $limit, $page);
    error_log($url);
    $ret = json_decode(file_get_contents($url));
    $hit = 0;
    foreach ($ret->interpellations as $interpellation) {
        if (property_exists($interpellation_show, $interpellation->質詢編號)) {
            continue;
        }
        $interpellation_show->{$interpellation->質詢編號} = true;
        fputs($output, json_encode($interpellation, JSON_UNESCAPED_UNICODE) . "\n");
        $hit ++;
    }
    if (!$hit) {
        break;
    }
    $page++;
}
