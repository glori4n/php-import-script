<!-- This snippet was made by Glori4n(https://glori4n.com). -->

<?php

// Batch.
$starttime = microtime(true);

// Path to CSV.
$file = 'file.csv';

// Transforms columns into arrays.
$csvdata = collect(array_map('str_getcsv', file($file)));
$count = $csvdata->values()->map (function ($item){
    return [
    $item[0],
    $item[1],
    $item[2],
    strtotime($item[3]),
    $item[4],
    $item[5],
    $item[6],
    $item[7],
    $item[8],
    json_decode($item[9]),
    $item[10],
    ];

})->toArray();

$issueCounter = 0;

// Populates DB Columns.
foreach ($count as $entries) {
    $issueCounter++;
    sleep(1);
    $_data = [
        'spending_id' => $entries[0],
        'user_id' => $entries[9]->user_id,
        'product_id' => $entries[9]->product_id,
        'timestamp' => $entries[3],
        'combination' => $entries[3]
    ];
        try {
            // Query.
            db_query("REPLACE INTO script_test ?e", $_data);

            // Logger.
            $log['Result'] .= 'Data Inserted Successfully: '.json_encode($_data)."\n";

        }catch(\Exception $e) {
            $log['Result'] .= 'Error: on '.json_encode($_data)."\n".json_encode($e->getMessage())."\n";
            break;
        }

    // Counter for batch.
    if ($issueCounter == 200) {
        $issueCounter = 0;
        sleep(10);
    }
}

echo nl2br($log['Result']."\n\n");

$endtime = microtime(true);
$timediff = $endtime - $starttime;
echo $timediff;