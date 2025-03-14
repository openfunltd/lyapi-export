<?php

// prepare year parapmeter
$year_now = date('Y');
$year_input = $_SERVER['argv'][1] ?? $year_now;

if ($year_input > $year_now) {
    echo "no data from the future";
    exit;
}

//get all law_version_ids
$law_file = "data/law_version-{$year_input}.jsonl";
$f = @fopen($law_file, 'r');
if ($f === false) {
    echo "{$law_file} not found. Run 'php dump-law_version.php' first.\n";
    exit;
}
$law_version_ids = [];

while (($line = fgets($f)) !== false) {
   $law_version_data = json_decode($line); 
   $law_version_ids[] = $law_version_data->版本編號;
}
fclose($f);

//query law_content by law_version
$limit = 100;
$law_content_show = new stdClass();
$output = fopen(__DIR__ . "/data/law_content-{$year_input}.jsonl", "w");

foreach ($law_version_ids as $law_version_id) {
    $page = 1;
    while (true) {
        $url = sprintf("https://v2.ly.govapi.tw/law_contents?版本編號=%s&output_fields=*&limit=%d&page=%d",
            $law_version_id,
            $limit,
            $page
        );
        error_log($url);
        $ret = json_decode(file_get_contents($url));
        $hit = 0;
        foreach ($ret->lawcontents as $law_content) {
            if (property_exists($law_content_show, $law_content->法條編號)) {
                continue;
            }
            $law_content_show->{$law_content->法條編號} = true;
            fputs($output, json_encode($law_content, JSON_UNESCAPED_UNICODE) . "\n");
            $hit ++;
        }
        if (!$hit) {
            break;
        }
        $page ++;
    }
}
