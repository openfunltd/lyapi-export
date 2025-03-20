<?php

$committee_show = new stdClass();
$output = fopen(__DIR__ . "/data/committee.jsonl", "w");
$page = 1;
while (true) {
    $url = sprintf("https://v2.ly.govapi.tw/committees?page=%d", $page);
    error_log($url);
    $ret = json_decode(file_get_contents($url));
    $hit = 0;
    foreach ($ret->committees as $committee) {
        if (property_exists($committee_show, $committee->委員會代號)) {
            continue;
        }
        $committee_show->{$committee->委員會代號} = true;
        fputs($output, json_encode($committee, JSON_UNESCAPED_UNICODE) . "\n");
        $hit++;
    }
    if (!$hit) {
        break;
    }
    $page++;
}
