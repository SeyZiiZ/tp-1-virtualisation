<?php
header('Content-Type: application/json');

$response = [
    "surname" => "Yanis",
];

echo json_encode($response);
?>