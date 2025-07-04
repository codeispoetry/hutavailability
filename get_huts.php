<?php
$html = file_get_contents('huts.html');
$dom = new DOMDocument();
libxml_use_internal_errors(true);
$dom->loadHTML($html);
libxml_clear_errors();

$xpath = new DOMXPath($dom);

$huts = [];

foreach ($xpath->query('.//div[contains(@class, "oax-id")]') as $oaxIdDiv) {
    $otherClasses = [];

    $classAttr = $oaxIdDiv->getAttribute('class');
    $classes = preg_split('/\s+/', $classAttr, -1, PREG_SPLIT_NO_EMPTY);
    foreach ($classes as $class) {
        if ($class !== 'oax-id') {
            $otherClasses[] = $class;
        }
    }
    $id = substr($otherClasses[1], 7);
    if(empty($id)) {
        continue;
    }

    $table = $oaxIdDiv->getElementsByTagName('table')->item(0);
    $oaxName = '';
    if ($table) {
        foreach ($table->getElementsByTagName('td') as $td) {
            if (strpos($td->getAttribute('class'), 'oax_name') !== false) {
                $name = utf8_decode(trim($td->textContent));
          
            }
            if (strpos($td->getAttribute('class'), 'oax_region') !== false) {
                $region = utf8_decode(trim($td->textContent));
            
            }
        }
    }


    echo $id . ': ' . $name . ' (' . $region . ")\n";
    $huts[] = (object) [
        'id' => $id,
        'name' => $name,
        'region' => $region,
    ];
}

file_put_contents('huts.json', json_encode($huts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));