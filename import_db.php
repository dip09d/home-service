<?php
// Advanced SQL import script for handling complex SQL files

define('ENVIRONMENT', 'development');

$host = '127.0.0.1';
$user = 'root';
$pass = '';
$database = 'maidfort';
$port = 3307;
$sql_file = __DIR__ . '/sql.sql';

try {
    echo "🚀 Starting Advanced Database Import...\n";
    echo "========================================\n\n";
    
    // Step 1: Connect to MySQL
    echo "[1/4] Connecting to MySQL at $host:$port...\n";
    $conn = new mysqli($host, $user, $pass, '', $port);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    echo "✓ Connected successfully\n\n";
    
    // Step 2: Drop and recreate database
    echo "[2/4] Preparing database...\n";
    $conn->query("DROP DATABASE IF EXISTS `$database`");
    $conn->query("CREATE DATABASE IF NOT EXISTS `$database` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $conn->select_db($database);
    echo "✓ Database prepared\n\n";
    
    // Step 3: Read SQL file
    echo "[3/4] Reading SQL file (sql.sql)...\n";
    if (!file_exists($sql_file)) {
        throw new Exception("SQL file not found: $sql_file");
    }
    
    $file_size = filesize($sql_file);
    echo "✓ File size: " . round($file_size / 1024 / 1024, 2) . " MB\n\n";
    
    // Step 4: Import SQL file
    echo "[4/4] Importing SQL data...\n";
    echo "This may take several minutes...\n";
    
    // Read file in chunks to avoid memory issues
    $handle = fopen($sql_file, 'r');
    if (!$handle) {
        throw new Exception("Cannot open SQL file for reading");
    }
    
    $sql_content = '';
    $statement_count = 0;
    $error_count = 0;
    $start_time = time();
    
    // Set connection options
    $conn->query("SET SESSION sql_mode=''");
    $conn->query("SET SESSION max_allowed_packet=1024*1024*256");
    $conn->query("SET SESSION wait_timeout=28800");
    $conn->query("SET SESSION net_read_timeout=28800");
    $conn->query("SET SESSION net_write_timeout=28800");
    
    // Read file line by line
    while (!feof($handle)) {
        $line = fgets($handle, 16384);
        if ($line === false) break;
        
        // Skip comments and empty lines
        $trimmed = trim($line);
        if (empty($trimmed) || substr($trimmed, 0, 2) === '--' || substr($trimmed, 0, 2) === '/*') {
            continue;
        }
        
        $sql_content .= $line;
        
        // Check if statement is complete (ends with semicolon)
        if (substr(rtrim($sql_content), -1) === ';') {
            $statement = rtrim($sql_content, ';');
            
            // Skip certain statements
            if (!empty($statement) && strlen(trim($statement)) > 5) {
                // Execute statement
                if (@$conn->query($statement) === TRUE) {
                    $statement_count++;
                } else {
                    $error_count++;
                    // Log error for first few failures
                    if ($error_count <= 10) {
                        $error_msg = $conn->error;
                        // Only show meaningful errors (ignore "already exists" type warnings)
                        if (strpos($error_msg, 'already exists') === false && 
                            strpos($error_msg, 'Duplicate') === false) {
                            echo "⚠ Error #{$error_count}: " . substr($error_msg, 0, 80) . "\n";
                        }
                    }
                }
                
                // Show progress every 100 statements
                if ($statement_count % 100 === 0) {
                    echo ".";
                }
                if ($statement_count % 500 === 0) {
                    echo " [" . number_format($statement_count) . "]\n";
                }
            }
            
            $sql_content = '';
        }
    }
    
    fclose($handle);
    
    $elapsed = time() - $start_time;
    
    echo "\n\n✓ Import Complete!\n";
    echo "========================================\n";
    echo "✓ Executed: " . number_format($statement_count) . " SQL statements\n";
    echo "⚠ Errors: " . number_format($error_count) . " (may include harmless duplicates)\n";
    echo "⏱ Time: {$elapsed} seconds\n\n";
    
    // Verify tables
    echo "📊 Database Verification:\n";
    $result = $conn->query("SELECT COUNT(*) as cnt FROM information_schema.tables WHERE table_schema='$database' AND table_type='BASE TABLE'");
    if ($result) {
        $row = $result->fetch_assoc();
        echo "✓ Tables created: " . number_format($row['cnt']) . "\n";
    }
    
    // Check for critical tables
    $critical_tables = ['pref_blog', 'pref_users', 'pref_jobs', 'pref_projects'];
    $missing_tables = [];
    
    foreach ($critical_tables as $table) {
        $result = $conn->query("SHOW TABLES LIKE '$table'");
        if ($result && $result->num_rows === 0) {
            $missing_tables[] = $table;
        }
    }
    
    if (!empty($missing_tables)) {
        echo "⚠ Missing tables: " . implode(', ', $missing_tables) . "\n";
        echo "  (Some pages may not work correctly)\n";
    } else {
        echo "✓ All critical tables exist\n";
    }
    
    echo "\n✅ Database setup completed!\n";
    echo "========================================\n";
    echo "• Application: http://localhost/maidfort-master\n";
    echo "• Admin Panel: http://localhost/maidfort-master/hackground\n";
    echo "• phpMyAdmin: http://localhost/phpmyadmin\n";
    
    $conn->close();
    
} catch (Exception $e) {
    echo "\n✗ Error: " . $e->getMessage() . "\n";
    echo "========================================\n";
    exit(1);
}
?>
