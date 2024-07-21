<?php

// Connection
$conn = new mysqli('localhost', 'root', '', 'iqlaascollection');

if ($conn->connect_error) {
    die ("Connection Failed: " . $conn->connect_error);
}

?>