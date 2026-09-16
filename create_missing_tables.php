<?php
// Create missing tables for Maidfort application

$host = '127.0.0.1';
$user = 'root';
$pass = '';
$database = 'maidfort';
$port = 3307;

try {
    $conn = new mysqli($host, $user, $pass, $database, $port);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    echo "Creating missing tables...\n";
    
    // pref_blog table
    $sql_blog = "CREATE TABLE IF NOT EXISTS `pref_blog` (
      `blog_id` int(11) NOT NULL AUTO_INCREMENT,
      `blog_slug` varchar(255) NOT NULL,
      `blog_reg_date` datetime DEFAULT CURRENT_TIMESTAMP,
      `blog_thumb` varchar(255),
      `blog_status` int(11) DEFAULT 1,
      PRIMARY KEY (`blog_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    if ($conn->query($sql_blog)) {
        echo "✓ Created pref_blog\n";
    }
    
    // pref_blog_names table
    $sql_blog_names = "CREATE TABLE IF NOT EXISTS `pref_blog_names` (
      `blog_name_id` int(11) NOT NULL AUTO_INCREMENT,
      `blog_id` int(11) NOT NULL,
      `blog_title` varchar(255) NOT NULL,
      `blog_short_description` text,
      `blog_lang` varchar(10) DEFAULT 'en',
      PRIMARY KEY (`blog_name_id`),
      KEY `blog_id` (`blog_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    if ($conn->query($sql_blog_names)) {
        echo "✓ Created pref_blog_names\n";
    }
    
    // pref_users table
    $sql_users = "CREATE TABLE IF NOT EXISTS `pref_users` (
      `user_id` int(11) NOT NULL AUTO_INCREMENT,
      `user_login` varchar(255) NOT NULL,
      `user_email` varchar(255) NOT NULL,
      `user_pass` varchar(255) NOT NULL,
      `user_registered` datetime DEFAULT CURRENT_TIMESTAMP,
      `user_status` int(11) DEFAULT 1,
      PRIMARY KEY (`user_id`),
      UNIQUE KEY `user_email` (`user_email`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    if ($conn->query($sql_users)) {
        echo "✓ Created pref_users\n";
    }
    
    // pref_jobs table
    $sql_jobs = "CREATE TABLE IF NOT EXISTS `pref_jobs` (
      `job_id` int(11) NOT NULL AUTO_INCREMENT,
      `job_title` varchar(255) NOT NULL,
      `job_desc` text,
      `job_category` int(11),
      `job_budget` decimal(10,2),
      `job_posted_by` int(11),
      `job_status` int(11) DEFAULT 1,
      `job_created` datetime DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`job_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    if ($conn->query($sql_jobs)) {
        echo "✓ Created pref_jobs\n";
    }
    
    // pref_projects table
    $sql_projects = "CREATE TABLE IF NOT EXISTS `pref_projects` (
      `project_id` int(11) NOT NULL AUTO_INCREMENT,
      `project_title` varchar(255) NOT NULL,
      `project_desc` text,
      `project_category` int(11),
      `project_budget` decimal(10,2),
      `project_posted_by` int(11),
      `project_status` int(11) DEFAULT 1,
      `project_created` datetime DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`project_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    if ($conn->query($sql_projects)) {
        echo "✓ Created pref_projects\n";
    }
    
    echo "\n✅ All missing tables created successfully!\n";
    
    $conn->close();
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
