<?php

// prepare year, date parapmeter
$year_now = date('Y');
$date_now = date('Y-m-d');
$year_input = $_SERVER['argv'][1] ?? $year_now;

if ($year_input > $year_now) {
    echo "no data from the future";
    exit;
}
$date_end = "{$year_input}-12-31";
$date_start = "{$year_input}-01-01";

$limit = 100;
$law_version_show = new stdClass();
$output = fopen(__DIR__ . "/data/law_version-{$year_input}.jsonl", "w");

$page = 1;
while (true) {
    $url = sprintf("https://v2.ly.govapi.tw/law_versions?sort=日期<&日期:%s,%s&output_fields=*&limit=%d&page=%d",
        $date_start,
        $date_end,
        $limit,
        $page
    );
    error_log($url);
    $ret = json_decode(file_get_contents($url));
    $hit = 0;
    foreach ($ret->lawversions as $law_version) {
        if (property_exists($law_version_show, $law_version->版本編號)) {
            continue;
        }
        $law_version_show->{$law_version->版本編號} = true;
        fputs($output, json_encode($law_version, JSON_UNESCAPED_UNICODE) . "\n");
        $hit ++;
    }
    if (!$hit) {
        break;
    }
    $page ++;
}
