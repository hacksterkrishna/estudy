<?php
require '../includes/connect.php'; // adjust path if needed

header('Content-Type: application/json');

// Validate and sanitize input
$country_id = isset($_GET['country_id']) ? intval($_GET['country_id']) : 0;

if ($country_id <= 0) {
    echo json_encode([]);
    exit;
}

// Prepare query to fetch states based on country_id
$stmt = $conn->prepare("SELECT id, name FROM states WHERE country_id = ? ORDER BY name ASC");
$stmt->bind_param("i", $country_id);
$stmt->execute();

$result = $stmt->get_result();
$states = [];

while ($row = $result->fetch_assoc()) {
    $states[] = [
        'id' => $row['id'],
        'name' => $row['name']
    ];
}

echo json_encode($states);
$stmt->close();
