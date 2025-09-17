<?php
header('Content-Type: application/json');

// File to store visit data
$file = 'visit_data.json';

// Load existing data or initialize
if (file_exists($file)) {
    $data = json_decode(file_get_contents($file), true);
} else {
    $data = [
        'total' => 0,
        'today' => 0,
        'yesterday' => 0,
        'last_date' => date('Y-m-d')
    ];
}

// Check if date changed
$today = date('Y-m-d');
if ($data['last_date'] !== $today) {
    $data['yesterday'] = $data['today'];
    $data['today'] = 0;
    $data['last_date'] = $today;
}

// Increment today's and total visits
$data['today'] += 1;
$data['total'] += 1;

// Save back to file
file_put_contents($file, json_encode($data));

// Return JSON data
echo json_encode([
    'yesterday' => $data['yesterday'],
    'today' => $data['today'],
    'total' => $data['total']
]);
?>