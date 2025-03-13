<?php

$limit = 100;
$law_show = new stdClass();
$law_after = null;
$output = fopen(__DIR__ . "/data/law.jsonl", "w");

while (true) {
    if (is_null($law_after)) {
        $url = sprintf("https://v2.ly.govapi.tw/laws?sort=法律編號>&output_fields=*&limit=%d", $limit);
    } else {
        $url = sprintf("https://v2.ly.govapi.tw/laws?sort=法律編號>&output_fields=*&法律編號:,%s&limit=%d", $law_after, $limit);
    }
    error_log($url);
    $ret = json_decode(file_get_contents($url));
    $hit = 0;
    foreach ($ret->laws as $law) {
        $law_after = $law->法律編號;
        if (property_exists($law_show, $law->法律編號)) {
            continue;
        }
        $law_show->{$law->法律編號} = true;
        fputs($output, json_encode($law, JSON_UNESCAPED_UNICODE) . "\n");
        $hit ++;
    }
    if (!$hit) {
        break;
    }
}
