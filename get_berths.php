<?php
$date = '2025-07-19';


for($i = 0; $i < 7; $i++) {
    getBerths($date);
    $date = date('Y-m-d', strtotime("+$i days", strtotime($date)));
}


function getBerths($date){
    $weekday = date('l', strtotime($date));
    echo "Berths available on $weekday $date:\n";

    $huts = glob('availability/*.json');
    foreach ($huts as $hut) {
        $json = json_decode(file_get_contents($hut), false);
        
        foreach( $json->answer->contents as $day){
            if(!str_starts_with($day->date, $date)){
                continue;
            }

            $available = is_numeric(substr($day->textTotal, 0, 1));
          
            echo ($available) ? "\033[32m" : "\033[31m";
                echo basename($hut, '.json') . ": " . $day->textTotal . "\n";
            echo ($available) ? "\033[0m" : "\033[0m";
        }
    }

    echo "--------------------------------\n";
}
