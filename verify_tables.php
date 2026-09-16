<?php
$conn = new mysqli('127.0.0.1', 'root', '', 'maidfort', 3307);
if ($conn->connect_error) {
    echo "Error: " . $conn->connect_error;
    exit(1);
}

echo "Checking for pref_blog tables:\n";
$result = $conn->query('SHOW TABLES LIKE "pref_blog%"');
if ($result) {
    $count = 0;
    while ($row = $result->fetch_row()) {
        echo "✓ Found: " . $row[0] . "\n";
        $count++;
    }
    if ($count == 0) {
        echo "✗ No tables found!\n";
        echo "\nAll tables in database:\n";
        $all = $conn->query('SHOW TABLES');
        while ($row = $all->fetch_row()) {
            echo "  - " . $row[0] . "\n";
        }
    }
}
$conn->close();
?>
