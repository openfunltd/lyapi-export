<?php

$term = $_SERVER['argv'][1] ?? 11;
$limit = 100;
$gazette_agenda_show = new stdClass();
$output = fopen(__DIR__ . "/data/gazette_agenda-" . str_pad($term, 2, '0', STR_PAD_LEFT) . ".jsonl", "w");

$page = 1;
while (true) {
    $url = sprintf("https://v2.ly.govapi.tw/gazette_agendas?屆=%s&output_fields=*&limit=%d&page=%d",
        $term,
        $limit,
        $page
    );
    error_log($url);
    $ret = json_decode(file_get_contents($url));
    $hit = 0;
    foreach ($ret->gazetteagendas as $gazette_agenda) {
        if (property_exists($gazette_agenda_show, $gazette_agenda->公報議程編號)) {
            continue;
        }
        $gazette_agenda_show->{$gazette_agenda->公報議程編號} = true;
        fputs($output, json_encode($gazette_agenda, JSON_UNESCAPED_UNICODE) . "\n");
        $hit ++;
    }
    if (!$hit) {
        break;
    }
    $page ++;
}
