<?php
$date = '2025-07-25';

$hutInfo = json_decode(file_get_contents('huts.json'), true);

for($i = 0; $i <= 1; $i++) {
    getBerths($date);
    $date = date('Y-m-d', strtotime("+1 days", strtotime($date)));
}


function getRegion($hutName) {
    global $hutInfo;

    foreach ($hutInfo as $hut) {
       
        if ($hut['name'] === $hutName) {
            return $hut['region'];
        }
    }
    return 'x';
}


function getBerths($date){
    $weekday = date('l', strtotime($date));
    echo "Berths on $weekday $date:\n";

    $huts = glob('availability/*.json');
    foreach ($huts as $hut) {
        $json = json_decode(file_get_contents($hut), false);
        
        foreach( $json->answer->contents as $day){
            if(!str_starts_with($day->date, $date)){
                continue;
            }

            $available = is_numeric(substr($day->textTotal, 0, 1));

            if(!$available) {
                continue;
            }
            $total = preg_replace('/\D/', '', $day->textTotal);
            echo ($available) ? "\033[32m" : "\033[31m";
                echo basename($hut, '.json') . "(" . getRegion(basename($hut, '.json')) . "): " . $total . "\n";
            echo ($available) ? "\033[0m" : "\033[0m";
        }
    }

    echo "--------------------------------\n";
}
