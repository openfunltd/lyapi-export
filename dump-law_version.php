<?php

//get all law_ids
$law_file = 'data/law.jsonl';
$f = fopen($law_file, 'r');
$law_ids = [];

while (($line = fgets($f)) !== false) {
   $law_data = json_decode($line); 
   $law_ids[] = $law_data->法律編號;
}
fclose($f);

//query law_version by law_id
$limit = 100;
$law_version_show = new stdClass();
$output = fopen(__DIR__ . "/data/law_version.jsonl", "w");

foreach ($law_ids as $law_id) {
    $page = 1;
    while (true) {
        $url = sprintf("https://v2.ly.govapi.tw/law_versions?法律編號=%s&output_fields=*&limit=%d&page=%d",
            $law_id,
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
}
