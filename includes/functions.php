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