<?php
// CLI Database setup script for local development

define('ENVIRONMENT', 'development');

$host = '127.0.0.1';
$user = 'root';
$pass = '';
$database = 'maidfort';
$port = 3307;

try {
    echo "🚀 Starting Maidfort Database Setup...\n";
    echo "----------------------------------------\n";
    
    // Connect to MySQL without database selection
    echo "Connecting to MySQL Server at $host:$port...\n";
    $conn = new mysqli($host, $user, $pass, '', $port);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    echo "✓ Connected to MySQL Server\n";
    
    // Create database
    echo "Creating database '$database'...\n";
    $sql = "CREATE DATABASE IF NOT EXISTS `$database` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    
    if ($conn->query($sql) === TRUE) {
        echo "✓ Database '$database' created/verified successfully\n";
    } else {
        throw new Exception("Error creating database: " . $conn->error);
    }
    
    // Select database
    $conn->select_db($database);
    echo "✓ Selected database '$database'\n";
    
    // Read and execute SQL file
    $sql_file = __DIR__ . '/sql.sql';
    
    if (!file_exists($sql_file)) {
        throw new Exception("SQL file not found: $sql_file");
    }
    
    echo "✓ Found SQL file: sql.sql\n";
    echo "Loading SQL file (this may take a moment)...\n";
    
    $sql_content = file_get_contents($sql_file);
    
    // Set max allowed packet and timeout
    $conn->query("SET SESSION max_allowed_packet=1024*1024*256");
    $conn->query("SET SESSION sql_mode=''");
    
    // Split on semicolons
    $statements = explode(';', $sql_content);
    
    $count = 0;
    $error_count = 0;
    $start_time = time();
    
    set_time_limit(600); // Allow 10 minutes for execution
    
    foreach ($statements as $idx => $statement) {
        $statement = trim($statement);
        if (!empty($statement) && strlen($statement) > 5) {
            if ($conn->query($statement) === TRUE) {
                $count++;
                if ($count % 100 == 0) {
                    echo ".";
                    if ($count % 1000 == 0) {
                        echo " [$count]\n";
                    }
                }
            } else {
                $error_count++;
                // Log errors only on first occurrence
                if ($error_count == 1 || ($error_count <= 10 && $error_count % 5 == 0)) {
                    echo "\n⚠ Error at statement $idx: " . $conn->error;
                    if ($error_count == 10) {
                        echo "\n(Suppressing further error messages)\n";
                    }
                }
            }
        }
    }
    
    $elapsed = time() - $start_time;
    
    echo "\n\n✓ SQL Import Complete!\n";
    echo "----------------------------------------\n";
    echo "✓ Executed $count SQL statements successfully\n";
    if ($error_count > 0) {
        echo "⚠ Encountered $error_count errors (may be expected for IF NOT EXISTS clauses)\n";
    }
    echo "Time taken: {$elapsed} seconds\n";
    
    // Verify database has tables
    $result = $conn->query("SELECT COUNT(*) as table_count FROM information_schema.tables WHERE table_schema='$database' AND table_type='BASE TABLE'");
    if ($result) {
        $row = $result->fetch_assoc();
        echo "✓ Database now contains " . $row['table_count'] . " tables\n";
    }
    
    echo "\n✓ Database setup completed successfully!\n";
    echo "----------------------------------------\n";
    echo "\n📝 Next Steps:\n";
    echo "  • Access the application: http://localhost/maidfort-master\n";
    echo "  • Admin panel: http://localhost/maidfort-master/hackground\n";
    echo "  • phpMyAdmin: http://localhost/phpmyadmin/?route=/database/structure&db=maidfort\n";
    
    $conn->close();
    
} catch (Exception $e) {
    echo "\n✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
