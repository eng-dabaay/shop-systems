<?php
session_start();

if (isset($_SESSION['username'])) {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "iqlaascollection";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $username = $_SESSION['username'];

    $update_stmt = $conn->prepare("UPDATE users SET status = 'offline' WHERE username = ?");
    $update_stmt->bind_param("s", $username);
    $update_stmt->execute();

    session_unset();
    session_destroy();

    header("Location: login.php");
    exit();
}
?>
