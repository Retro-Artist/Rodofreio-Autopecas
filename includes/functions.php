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
    
    // Create backup command using mysqldump
    $command = sprintf(
        'mysqldump --user=%s --password=%s --host=%s %s > %s',
        escapeshellarg(DB_USER),
        escapeshellarg(DB_PASS),
        escapeshellarg(DB_HOST),
        escapeshellarg(DB_NAME),
        escapeshellarg($backup_path)
    );
    
    // Execute the command
    exec($command, $output, $return_var);
    
    // Check if backup was successful
    if ($return_var === 0) {
        // Log successful backup
        error_log("Database backup created successfully: $backup_path");
        return [
            'success' => true,
            'message' => "Backup do banco de dados criado com sucesso",
            'file' => $backup_path
        ];
    } else {
        // Log error
        error_log("Database backup failed with error code: $return_var");
        return [
            'success' => false,
            'message' => "Falha ao criar backup do banco de dados. Código de erro: $return_var"
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