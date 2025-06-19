<?php
// File: get-states.php
require '../includes/connect.php';

header('Content-Type: application/json');

if (isset($_GET['country_id'])) {
    $country_id = (int) $_GET['country_id'];
    $stmt = $conn->prepare("SELECT id, name FROM states WHERE country_id = ? ORDER BY name ASC");
    $stmt->bind_param("i", $country_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $states = [];
    while ($row = $result->fetch_assoc()) {
        $states[] = $row;
    }

    echo json_encode($states);
} else {
    echo json_encode([]);
} ?>