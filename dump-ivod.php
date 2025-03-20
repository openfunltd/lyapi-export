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

$ivod_show = new stdClass();
$output = fopen(__DIR__ . "/data/ivod-{$year_input}.jsonl", "w");

$ivod_show = dumpWorker($date_start, "{$year_input}-06-30", $ivod_show, $output);
dumpWorker("{$year_input}-07-01", $date_end, $ivod_show, $output);

function dumpWorker($lower_bound_date, $upper_bound_date, $ivod_show, $output) {
    $limit = 100;
    $page = 1;
    while (true) {
        $url = sprintf("https://v2.ly.govapi.tw/ivods?sort=日期<&日期:%s,%s&output_fields=*&limit=%d&page=%d",
            $lower_bound_date,
            $upper_bound_date,
            $limit,
            $page
        );
        error_log($url);
        $ret = json_decode(file_get_contents($url));
        $hit = 0;
        foreach ($ret->ivods as $ivod) {
            if (property_exists($ivod_show, $ivod->IVOD_ID)) {
                continue;
            }
            $ivod_show->{$ivod->IVOD_ID} = true;
            fputs($output, json_encode($ivod, JSON_UNESCAPED_UNICODE) . "\n");
            $hit ++;
        }
        if (!$hit) {
            break;
        }
        $page ++;
    }

    return $ivod_show;
}
