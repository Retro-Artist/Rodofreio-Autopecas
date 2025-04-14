<?php

// Define the absolute path to the site's root
define('ROOT_PATH', __DIR__);

// Load configuration
require_once ROOT_PATH . '/config/config.php';
require_once ROOT_PATH . '/includes/database.php';
require_once ROOT_PATH . '/includes/functions.php';

// Log start of backup process
error_log('Starting scheduled database backup...');

// Check if we've hit the backup limit
$backup_info = get_backup_count();
if ($backup_info['remaining'] <= 0) {
    error_log('Backup limit reached (' . $backup_info['count'] . '/' . $backup_info['max'] . '). Cleaning up oldest backup first.');
    
    // Find the oldest backup and delete it
    $backup_dir = ROOT_PATH . '/backups';
    $backup_files = glob($backup_dir . '/*.sql');
    
    // Sort files by modification time (oldest first)
    usort($backup_files, function($a, $b) {
        return filemtime($a) - filemtime($b);
    });
    
    if (!empty($backup_files)) {
        $oldest_backup = $backup_files[0];
        $oldest_filename = basename($oldest_backup);
        
        $delete_result = delete_backup($oldest_filename);
        if ($delete_result['success']) {
            error_log('Successfully deleted oldest backup: ' . $oldest_filename);
        } else {
            error_log('Failed to delete oldest backup: ' . $delete_result['message']);
            echo "Failed to delete oldest backup. Cannot proceed.\n";
            exit(1);
        }
    }
}

// Create backup filename with 10-day identifier
$timestamp = date('Y-m-d_H-i-s');
$filename = "database-{$timestamp}-10day-backup.sql";

// Attempt to create backup
$result = backup_database($filename);

// Log the result
if ($result['success']) {
    error_log('Scheduled database backup completed successfully: ' . $result['file']);
    echo "Backup completed successfully: {$result['file']}\n";
    exit(0);
} else {
    error_log('Scheduled database backup failed: ' . $result['message']);
    echo "Backup failed: {$result['message']}\n";
    exit(1);
}