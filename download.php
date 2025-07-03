<?php

$huts = [
    'schwarzenberghuette' => '7026235',
    'rappenseehuette' => '7026234',
    'kemptner-huette' => '7026232',
    'staufner-haus' => '6938789',
    'waltenberger-haus' => '7026236',
    'prinz-luitpold-haus' => '7026233',
    'mindelheimer-huette' => '6938787',
    'edmund-probst-haus' => '7026230',
];


$url = 'https://www.alpenvereinaktiv.com/api/v2/project/alpenverein/contents/hut/%d/availability?key=1';

foreach ($huts as $hut => $id) {
    $data = file_get_contents(sprintf($url, $id));

    $json = json_decode($data, false);
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo "Error decoding JSON for $hut: " . json_last_error_msg() . "\n";
        continue;
    }
    if (!isset($json->answer) || !isset($json->answer->contents)) {
        echo "Missing data for $hut\n";
        continue;
    }
    echo count($json->answer->contents) . " entries for $hut\n";

    file_put_contents(sprintf('availability/%s.json', $hut), $data);
}
