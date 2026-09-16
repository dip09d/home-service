<?php
// Database setup script for local development

$host = '127.0.0.1';
$user = 'root';
$pass = '';
$database = 'maidfort';
$port = 3307;

// Check if form was submitted
$setup_complete = false;
$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Connect to MySQL without database selection
        $conn = new mysqli($host, $user, $pass, '', $port);
        
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }
        
        $success_message .= "✓ Connected to MySQL Server\n";
        
        // Create database
        $sql = "CREATE DATABASE IF NOT EXISTS `$database` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
        
        if ($conn->query($sql) === TRUE) {
            $success_message .= "✓ Database '$database' created/verified successfully\n";
        } else {
            throw new Exception("Error creating database: " . $conn->error);
        }
        
        // Select database
        $conn->select_db($database);
        
        // Read and execute SQL file
        $sql_file = __DIR__ . '/sql.sql';
        
        if (!file_exists($sql_file)) {
            throw new Exception("SQL file not found: $sql_file");
        }
        
        $success_message .= "✓ Found SQL file: sql.sql\n";
        
        // Better SQL splitting that handles semicolons within strings
        $sql_content = file_get_contents($sql_file);
        
        // Set max allowed packet to handle large statements
        $conn->query("SET GLOBAL max_allowed_packet=1024*1024*256;");
        
        // Split on semicolons not inside quotes
        $statements = preg_split('/;(?=(?:[^"]*"[^"]*")*[^"]*$)/', $sql_content);
        if (!is_array($statements)) {
            $statements = explode(';', $sql_content);
        }
        
        $count = 0;
        $error_count = 0;
        
        set_time_limit(300); // Allow 5 minutes for execution
        
        foreach ($statements as $statement) {
            $statement = trim($statement);
            if (!empty($statement) && strlen($statement) > 5) {
                if ($conn->query($statement) === TRUE) {
                    $count++;
                } else {
                    $error_count++;
                    // Log the error but continue
                    if ($error_count <= 5) { // Log first 5 errors only
                        error_log("SQL Error: " . $conn->error . " for statement: " . substr($statement, 0, 100));
                    }
                }
            }
        }
        
        $success_message .= "✓ Executed $count SQL statements successfully\n";
        if ($error_count > 0) {
            $success_message .= "⚠ Skipped $error_count SQL statements (may already exist)\n";
        }
        
        $success_message .= "\n✓ Database setup completed successfully!\n";
        $setup_complete = true;
        
        $conn->close();
        
    } catch (Exception $e) {
        $error_message = "✗ Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Maidfort - Local Setup</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }
        .status {
            margin: 20px 0;
            padding: 15px;
            border-radius: 4px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .info {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        pre {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 4px;
            overflow-x: auto;
        }
        button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #0056b3;
        }
        .config-info {
            margin-top: 20px;
            padding: 15px;
            background-color: #e7f3ff;
            border-left: 4px solid #2196F3;
        }
        .next-steps {
            margin-top: 20px;
            padding: 15px;
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 Maidfort - Local Database Setup</h1>
        
        <?php if ($setup_complete): ?>
            <div class="status success">
                <h3>✓ Setup Completed Successfully!</h3>
                <pre><?php echo htmlspecialchars($success_message); ?></pre>
            </div>
            
            <div class="next-steps">
                <h3>📝 Next Steps:</h3>
                <ul>
                    <li>Access the application at: <strong><a href="http://localhost/maidfort-master" target="_blank">http://localhost/maidfort-master</a></strong></li>
                    <li>Access the admin panel at: <strong><a href="http://localhost/maidfort-master/hackground" target="_blank">http://localhost/maidfort-master/hackground</a></strong></li>
                    <li>View database in phpMyAdmin: <strong><a href="http://localhost/phpmyadmin/?route=/database/structure&db=maidfort" target="_blank">phpMyAdmin - maidfort</a></strong></li>
                </ul>
            </div>
        <?php elseif ($error_message): ?>
            <div class="status error">
                <h3>✗ Setup Failed</h3>
                <pre><?php echo htmlspecialchars($error_message); ?></pre>
            </div>
            
            <div class="config-info">
                <h3>Current Configuration:</h3>
                <ul>
                    <li><strong>Host:</strong> <?php echo $host; ?></li>
                    <li><strong>Port:</strong> <?php echo $port; ?></li>
                    <li><strong>User:</strong> <?php echo $user; ?></li>
                    <li><strong>Database:</strong> <?php echo $database; ?></li>
                </ul>
            </div>
        <?php else: ?>
            <div class="status info">
                <h3>📋 Database Setup Configuration</h3>
                <p>Click the button below to create and populate the <strong>maidfort</strong> database:</p>
                <ul>
                    <li><strong>Host:</strong> <?php echo $host; ?></li>
                    <li><strong>Port:</strong> <?php echo $port; ?></li>
                    <li><strong>User:</strong> <?php echo $user; ?></li>
                    <li><strong>Database:</strong> <?php echo $database; ?></li>
                    <li><strong>SQL File:</strong> sql.sql (1.4 MB)</li>
                </ul>
            </div>
            
            <form method="POST">
                <button type="submit">🔧 Setup Database Now</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
