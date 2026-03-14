<?php
$mysqli = new mysqli("localhost", "root", "root", "yespl");

if ($mysqli->connect_error) {
    die("Connect Error: " . $mysqli->connect_error);
}
echo "✅ PHP is connected to MySQL successfully.";
$mysqli->close();
?>
