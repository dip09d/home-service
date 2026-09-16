<?php
$conn = new mysqli("127.0.0.1", "root", "", "maidfort", 3306);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if there's a member and provider
$member_id = 1;
$provider_id = 2; // Assuming 2 is a worker
$cat_id = 1;
$sub_cat_id = 1;

// Insert dummy data
$sql = "INSERT INTO `pref_booking_services` (
    `member_id`, `provider_id`, `cat_id`, `sub_cat_id`, `provider_religion`, `provider_gender`, 
    `booking_date`, `booking_time`, `duration_hours`, `special_instructions`, `address_id`, 
    `created_at`, `status`, `cancelled_by`
) VALUES (
    $member_id, $provider_id, $cat_id, $sub_cat_id, 1, 'Any', 
    '2026-08-25', '10:00:00', 2, 'Please arrive on time.', 1, 
    NOW(), 1, NULL
)";

if ($conn->query($sql)) {
    echo "Dummy booking inserted successfully with ID: " . $conn->insert_id . "\n";
} else {
    echo "Error inserting dummy booking: " . $conn->error . "\n";
}
