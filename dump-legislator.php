<?php

$term = $_SERVER['argv'][1] ?? 11;
$limit = 100;
$legislator_show = new stdClass();
$output = fopen(__DIR__ . "/data/legislator-" . str_pad($term, 2, '0', STR_PAD_LEFT) . ".jsonl", "w");
$page = 1;
while (true) {
    $url = sprintf("https://v2.ly.govapi.tw/legislators?屆=%d&limit=%d&page=%d", $term, $limit, $page);
    error_log($url);
    $ret = json_decode(file_get_contents($url));
    $hit = 0;
    foreach ($ret->legislators as $legislator) {
        if (property_exists($legislator_show, $legislator->歷屆立法委員編號)) {
            continue;
        }
        $legislator_show->{$legislator->歷屆立法委員編號} = true;
        fputs($output, json_encode($legislator, JSON_UNESCAPED_UNICODE) . "\n");
        $hit ++;
    }
    if (!$hit) {
        break;
    }
    $page ++;
}
