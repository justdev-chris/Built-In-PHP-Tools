<?php
// visits.php - single JSON file tracks everything
$file = 'visits.json';

// Create the file if it doesn't exist
if(!file_exists($file)){
    $data = [
        'total' => 0,
        'today' => 0,
        'yesterday' => 0,
        'last_date' => date('Y-m-d')
    ];
    file_put_contents($file, json_encode($data));
}

// Read current data
$data = json_decode(file_get_contents($file), true);

// Update counters
$today_date = date('Y-m-d');
if($data['last_date'] != $today_date){
    // Yesterday = previous today, reset today
    $data['yesterday'] = $data['today'];
    $data['today'] = 0;
    $data['last_date'] = $today_date;
}

$data['today']++;
$data['total']++;

// Save updated data
file_put_contents($file, json_encode($data));

// Return JSON for front-end
header('Content-Type: application/json');
echo json_encode([
    'yesterday' => $data['yesterday'],
    'today' => $data['today'],
    'total' => $data['total']
]);
?>