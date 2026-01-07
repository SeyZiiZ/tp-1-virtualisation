<?php
header('Content-Type: application/json');

$response = [
    "surname" => "Yanis",
    "name" => "Ait Bihi",
];

echo json_encode($response);
?>