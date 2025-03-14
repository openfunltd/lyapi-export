<?php

$term = $_SERVER['argv'][1] ?? 11;
$limit = 100;
$page = 1;
$meet_show = new stdClass();
$output = fopen(__DIR__ . "/data/meet-" . str_pad($term, 2, '0', STR_PAD_LEFT) . ".jsonl", "w");
while (true) {
    $url = sprintf("https://v2.ly.govapi.tw/meets?屆=%d&sort=日期>&output_fields=*&limit=%d&page=%d", $term, $limit, $page);
    error_log($url);
    $ret = json_decode(file_get_contents($url));
    $hit = 0;
    foreach ($ret->meets as $meet) {
        $meet_after = $meet->會議代碼;
        if (property_exists($meet_show, $meet->會議代碼)) {
            continue;
        }
        $meet_show->{$meet->會議代碼} = true;
        fputs($output, json_encode($meet, JSON_UNESCAPED_UNICODE) . "\n");
        $hit ++;
    }
    if (!$hit) {
        break;
    }
    $page ++;
}
