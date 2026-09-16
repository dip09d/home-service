<?php
require_once 'base_config/database.php';
$db_config = $db['default'];
$mysqli = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

echo "<h3>Adding Booking History...</h3>";

$parent_code = 'MEN0150';
$child_code = 'MEN0150_LIST';

// Check parent Booking History
$result = $mysqli->query("SELECT id FROM pref_adminmenu WHERE name = 'Booking History' AND parent_id = 0");
if ($result->num_rows > 0) {
    $parent = $result->fetch_assoc();
    $parent_id = $parent['id'];
    echo "Parent Booking History already exists ($parent_id). <br>";
} else {
    $mysqli->query("INSERT INTO pref_adminmenu (name, title, url, menu_desc, style_class, parent_id, status, show_left, ord, menu_code, action) 
    VALUES ('Booking History', 'Booking History', 'booking_history_main', 'Booking History', 'icon-feather-calendar', 0, 1, 'Y', 15, '$parent_code', '')");
    $parent_id = $mysqli->insert_id;
    echo "Inserted parent Booking History ($parent_id). <br>";
}

// Check child Booking History
$result = $mysqli->query("SELECT id FROM pref_adminmenu WHERE url = 'booking/list_record'");
if ($result->num_rows > 0) {
    $child = $result->fetch_assoc();
    $child_id = $child['id'];
    echo "Child Booking History already exists ($child_id). <br>";
} else {
    $mysqli->query("INSERT INTO pref_adminmenu (name, title, url, menu_desc, style_class, parent_id, status, show_left, ord, menu_code, action) 
    VALUES ('Booking History', 'Booking History', 'booking/list_record', 'Booking History', '', $parent_id, 1, 'Y', 1, '$child_code', 'LIST')");
    $child_id = $mysqli->insert_id;
    echo "Inserted child Booking History ($child_id). <br>";
}

// Update permissions for Booking History
$mysqli->query("INSERT IGNORE INTO pref_adminmenu_permission (menu_id, menu_code, role_id) VALUES ($parent_id, '$parent_code', 1)");
$mysqli->query("INSERT IGNORE INTO pref_adminmenu_permission (menu_id, menu_code, role_id) VALUES ($child_id, '$child_code', 1)");
$mysqli->query("INSERT IGNORE INTO pref_adminmenu_permission (menu_id, menu_code, role_id) VALUES ($parent_id, '$parent_code', 2)");
$mysqli->query("INSERT IGNORE INTO pref_adminmenu_permission (menu_id, menu_code, role_id) VALUES ($child_id, '$child_code', 2)");


echo "<h3>Adding Message Board...</h3>";

$parent_code_mb = 'MEN0160';
$child_code_mb = 'MEN0160_LIST';

// Create or get top-level 'Message Board' parent
$result = $mysqli->query("SELECT id FROM pref_adminmenu WHERE name = 'Message Board' AND parent_id = 0");
if ($result->num_rows > 0) {
    $parent = $result->fetch_assoc();
    $parent_id_mb = $parent['id'];
    echo "Parent Message Board already exists ($parent_id_mb). <br>";
} else {
    $mysqli->query("INSERT INTO pref_adminmenu (name, title, url, menu_desc, style_class, parent_id, status, show_left, ord, menu_code, action) 
    VALUES ('Message Board', 'Message Board', 'message_main_menu', 'Message Board', 'icon-feather-message-circle', 0, 1, 'Y', 16, '$parent_code_mb', '')");
    $parent_id_mb = $mysqli->insert_id;
    echo "Inserted parent Message Board ($parent_id_mb). <br>";
}

// Check if child 'message/list_record' exists
$result = $mysqli->query("SELECT id FROM pref_adminmenu WHERE url = 'message/list_record'");
if ($result->num_rows > 0) {
    $child = $result->fetch_assoc();
    $child_id_mb = $child['id'];
    // Update its parent to the new top-level parent
    $mysqli->query("UPDATE pref_adminmenu SET parent_id = $parent_id_mb, show_left = 'Y' WHERE id = $child_id_mb");
    echo "Updated existing Message Board child to new parent ($child_id_mb). <br>";
} else {
    // Insert if not exists
    $mysqli->query("INSERT INTO pref_adminmenu (name, title, url, menu_desc, style_class, parent_id, status, show_left, ord, menu_code, action) 
    VALUES ('Message Board', 'Message Board', 'message/list_record', 'Message Board List', '', $parent_id_mb, 1, 'Y', 1, '$child_code_mb', 'LIST')");
    $child_id_mb = $mysqli->insert_id;
    echo "Inserted child Message Board ($child_id_mb). <br>";
}

// Update permissions for Message Board
$mysqli->query("INSERT IGNORE INTO pref_adminmenu_permission (menu_id, menu_code, role_id) VALUES ($parent_id_mb, '$parent_code_mb', 1)");
$mysqli->query("INSERT IGNORE INTO pref_adminmenu_permission (menu_id, menu_code, role_id) VALUES ($child_id_mb, '$child_code_mb', 1)");
$mysqli->query("INSERT IGNORE INTO pref_adminmenu_permission (menu_id, menu_code, role_id) VALUES ($parent_id_mb, '$parent_code_mb', 2)");
$mysqli->query("INSERT IGNORE INTO pref_adminmenu_permission (menu_id, menu_code, role_id) VALUES ($child_id_mb, '$child_code_mb', 2)");

echo "<br><b>Both Booking History and Message Board successfully added to sidebar menu! You can now delete this file from the live server.</b><br>";
