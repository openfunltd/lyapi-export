<?php

$limit = 100;
$page = 1;
$gazette_show = new stdClass();
$output = fopen(__DIR__ . "/data/gazette.jsonl", "w");
while (true) {
    $url = sprintf("https://v2.ly.govapi.tw/gazettes?sort=發布日期>&output_fields=*&limit=%d&page=%d", $limit, $page);
    error_log($url);
    $ret = json_decode(file_get_contents($url));
    $hit = 0;
    foreach ($ret->gazettes as $gazette) {
        if (property_exists($gazette_show, $gazette->公報編號)) {
            continue;
        }
        $gazette_show->{$gazette->公報編號} = true;
        fputs($output, json_encode($gazette, JSON_UNESCAPED_UNICODE) . "\n");
        $hit ++;
    }
    if (!$hit) {
        break;
    }
    $page ++;
}
