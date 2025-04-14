<?php
// includes/functions.php

function createSlug($string) {
    // Replace non letter or digits by -
    $string = preg_replace('~[^\pL\d]+~u', '-', $string);
    
    // Transliterate
    $string = iconv('utf-8', 'us-ascii//TRANSLIT', $string);
    
    // Remove unwanted characters
    $string = preg_replace('~[^-\w]+~', '', $string);
    
    // Trim
    $string = trim($string, '-');
    
    // Remove duplicate -
    $string = preg_replace('~-+~', '-', $string);
    
    // Lowercase
    $string = strtolower($string);
    
    return $string ?: 'n-a';
}

/**
 * Validates and formats a WhatsApp number
 * 
 * @param string $number The WhatsApp number to validate
 * @return array Array with 'valid' boolean and 'formatted' string or 'error' message
 */
function validateWhatsAppNumber(string $number): array {
    // Remove any non-digit characters
    $cleanNumber = preg_replace('/[^0-9]/', '', $number);
    
    // Check if the number is empty
    if (empty($cleanNumber)) {
        return [
            'valid' => false,
            'error' => 'Número de WhatsApp não pode estar vazio'
        ];
    }
    
    // Check minimum length (country code + area code + number)
    if (strlen($cleanNumber) < 10) {
        return [
            'valid' => false,
            'error' => 'Número de WhatsApp muito curto'
        ];
    }
    
    // Check if it has country code, add Brazilian code (55) if not
    if (strlen($cleanNumber) < 12) {
        // Assume it's a Brazilian number without country code
        $cleanNumber = '55' . $cleanNumber;
    }
    
    // Format for WhatsApp API (should be country code + number)
    $formattedNumber = $cleanNumber;
    
    return [
        'valid' => true,
        'formatted' => $formattedNumber
    ];
}

/**
 * Generate WhatsApp message URL
 * 
 * @param string $number The WhatsApp number to use
 * @param string $message Optional message to pre-fill
 * @return string The full WhatsApp URL
 */
function getWhatsAppUrl(string $number, string $message = ''): string {
    $validation = validateWhatsAppNumber($number);
    
    if (!$validation['valid']) {
        // Fallback to the constant if the provided number is invalid
        $number = WHATSAPP_NUMBER;
    } else {
        $number = $validation['formatted'];
    }
    
    $url = "https://wa.me/{$number}";
    
    if (!empty($message)) {
        $url .= '?text=' . urlencode($message);
    }
    
    return $url;
}

/**
 * Generate a product URL
 * 
 * @param string $slug The product slug
 * @return string The product URL
 */
function getProductUrl(string $slug): string {
    return BASE_URL . '/produto/' . $slug;
}

/**
 * Generate a category URL
 * 
 * @param string $slug The category slug
 * @return string The category URL
 */
function getCategoryUrl(string $slug): string {
    return BASE_URL . '/produtos/categoria/' . $slug;
}

/**
 * Generate an archive URL
 * 
 * @param array $params Optional query parameters (category, search, etc.)
 * @return string The archive URL
 */
function getArchiveUrl(array $params = []): string {
    $base = BASE_URL . '/produtos';
    
    if (!empty($params['category'])) {
        return $base . '/categoria/' . urlencode($params['category']);
    }
    
    if (!empty($params['search'])) {
        return $base . '/busca/' . urlencode($params['search']);
    }
    
    return $base;
}


/**
 * Creates a backup of the database
 * 
 * @param string $filename Optional custom filename for the backup
 * @return array Array with success status and message
 */
function backup_database($filename = null) {
    // Default filename with timestamp
    if ($filename === null) {
        $timestamp = date('Y-m-d_H-i-s');
        $filename = "database-{$timestamp}-backup.sql";
    }
    
    // Ensure backup directory exists
    $backup_dir = __DIR__ . '/../backups';
    if (!is_dir($backup_dir)) {
        if (!mkdir($backup_dir, 0755, true)) {
            return [
                'success' => false,
                'message' => "Não foi possível criar o diretório de backup"
            ];
        }
    }
    
    $backup_path = $backup_dir . '/' . $filename;
    
    // Try to detect mysqldump location
    $possible_paths = [
        '/usr/bin/mysqldump',
        '/usr/local/bin/mysqldump',
        '/usr/local/mysql/bin/mysqldump',
        '/opt/local/bin/mysqldump',
        '/opt/local/mysql/bin/mysqldump',
        '/opt/mysql/bin/mysqldump',
        '/opt/homebrew/bin/mysqldump', // For macOS with Homebrew
        'C:\\xampp\\mysql\\bin\\mysqldump.exe', // For Windows with XAMPP
        'C:\\wamp64\\bin\\mysql\\mysql8.0.21\\bin\\mysqldump.exe', // For Windows with WAMP
    ];
    
    $mysqldump_path = '';
    foreach ($possible_paths as $path) {
        if (file_exists($path)) {
            $mysqldump_path = $path;
            break;
        }
    }
    
    // Log path detection for debugging
    error_log("Testing mysqldump path: $mysqldump_path");
    
    // If we found a valid path, try using mysqldump
    if (!empty($mysqldump_path) && is_executable($mysqldump_path)) {
        // Create a defaults file to avoid password issues in command line
        $defaults_file = $backup_dir . '/.my.cnf.tmp';
        $defaults_content = "[mysqldump]\nuser=" . DB_USER . "\npassword=" . DB_PASS . "\nhost=" . DB_HOST . "\n";
        file_put_contents($defaults_file, $defaults_content);
        chmod($defaults_file, 0600); // Secure the file
        
        // Build command with defaults file
        $command = sprintf(
            '%s --defaults-file=%s %s > %s',
            escapeshellcmd($mysqldump_path),
            escapeshellarg($defaults_file),
            escapeshellarg(DB_NAME),
            escapeshellarg($backup_path)
        );
        
        // Log the command (with password masked)
        error_log("Executing backup command: " . preg_replace('/--defaults-file=[^ ]+/', '--defaults-file=***', $command));
        
        // Execute the command
        exec($command, $output, $return_var);
        
        // Remove the temporary file
        if (file_exists($defaults_file)) {
            unlink($defaults_file);
        }
        
        // Check if backup was successful
        if ($return_var === 0 && filesize($backup_path) > 0) {
            error_log("mysqldump successful, file size: " . filesize($backup_path) . " bytes");
            return [
                'success' => true,
                'message' => "Backup do banco de dados criado com sucesso",
                'file' => $backup_path
            ];
        } else {
            error_log("mysqldump failed with code: $return_var, output: " . implode("\n", $output));
            // Don't return yet - try the PHP method below
        }
    } else {
        error_log("mysqldump not found or not executable. Trying PHP method.");
    }
    
    // Fall back to PHP method
    try {
        global $pdo;
        
        // If $pdo is not available, create a new connection
        if (!isset($pdo) || !($pdo instanceof PDO)) {
            $pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        }
        
        // Start the SQL file content
        $sql = "-- Database backup for " . DB_NAME . "\n";
        $sql .= "-- Generated on " . date('Y-m-d H:i:s') . "\n";
        $sql .= "-- Using PHP PDO Backup Method\n\n";
        $sql .= "SET FOREIGN_KEY_CHECKS = 0;\n\n";
        
        // Get all tables
        $tables_query = $pdo->query("SHOW TABLES");
        $tables = $tables_query->fetchAll(PDO::FETCH_COLUMN);
        
        if (empty($tables)) {
            error_log("No tables found in database");
            return [
                'success' => false,
                'message' => "Nenhuma tabela encontrada no banco de dados"
            ];
        }
        
        error_log("Found " . count($tables) . " tables in database");
        
        foreach ($tables as $table) {
            // Get create table syntax
            $create_query = $pdo->query("SHOW CREATE TABLE `$table`");
            $create_table = $create_query->fetch(PDO::FETCH_ASSOC);
            $create_table_keys = array_keys($create_table);
            $create_syntax = $create_table[$create_table_keys[1]];
            
            // Add create table statement
            $sql .= "\n-- Table structure for table `$table`\n\n";
            $sql .= "DROP TABLE IF EXISTS `$table`;\n";
            $sql .= $create_syntax . ";\n\n";
            
            // Get table data
            $row_query = $pdo->query("SELECT * FROM `$table`");
            $rows = $row_query->fetchAll(PDO::FETCH_ASSOC);
            
            if (count($rows) > 0) {
                $sql .= "-- Dumping data for table `$table`\n";
                
                // Get column names
                $columns = array_keys($rows[0]);
                $column_list = "`" . implode("`, `", $columns) . "`";
                
                // Start insert statement
                $sql .= "INSERT INTO `$table` ($column_list) VALUES\n";
                
                $values_list = [];
                foreach ($rows as $row) {
                    $values = [];
                    foreach ($row as $value) {
                        if ($value === null) {
                            $values[] = "NULL";
                        } else {
                            $values[] = $pdo->quote($value);
                        }
                    }
                    $values_list[] = "(" . implode(", ", $values) . ")";
                    
                    // Write in chunks to avoid memory issues with large tables
                    if (count($values_list) >= 1000) {
                        $sql .= implode(",\n", $values_list) . ";\n\n";
                        $values_list = [];
                        
                        // Start a new INSERT statement if there are more rows
                        if (count($values_list) === 0 && count($rows) > 1000) {
                            $sql .= "INSERT INTO `$table` ($column_list) VALUES\n";
                        }
                    }
                }
                
                // Write any remaining rows
                if (count($values_list) > 0) {
                    $sql .= implode(",\n", $values_list) . ";\n\n";
                }
            }
        }
        
        $sql .= "SET FOREIGN_KEY_CHECKS = 1;\n";
        
        // Write SQL file
        $bytes_written = file_put_contents($backup_path, $sql);
        if ($bytes_written !== false && $bytes_written > 0) {
            error_log("PHP backup method successful, wrote $bytes_written bytes");
            return [
                'success' => true,
                'message' => "Backup do banco de dados criado com sucesso (método PHP)",
                'file' => $backup_path
            ];
        } else {
            error_log("Failed to write backup file or file is empty: $backup_path");
            return [
                'success' => false,
                'message' => "Não foi possível criar o arquivo de backup ou o arquivo está vazio"
            ];
        }
    } catch (Exception $e) {
        error_log("PHP backup method failed: " . $e->getMessage());
        return [
            'success' => false,
            'message' => "Falha ao criar backup do banco de dados: " . $e->getMessage()
        ];
    }
}

/**
 * Format file size in human-readable format
 * 
 * @param int $bytes File size in bytes
 * @return string Formatted file size (e.g., "2.5 MB")
 */
function formatFileSize($bytes) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    
    $bytes /= (1 << (10 * $pow));
    
    return round($bytes, 2) . ' ' . $units[$pow];
}