<?php
// admin/pages/settings.php

// Initialize settings array to store current values
$settings = [];

// Define available settings
$availableSettings = [
    'WHATSAPP_NUMBER' => [
        'title' => 'Número do WhatsApp',
        'description' => 'O número de WhatsApp usado para contato direto com clientes. Inclua código do país (ex: 5511999999999)',
        'type' => 'text',
        'validation' => 'whatsapp'
    ],
];

// Get current settings from config file
$configFilePath = __DIR__ . '/../../config/config.php';
$configContent = file_get_contents($configFilePath);

// Process form submission
$message = '';
$messageClass = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_settings'])) {
    $errors = [];
    $newSettings = [];
    
    // Validate and collect settings
    foreach ($availableSettings as $settingKey => $settingInfo) {
        $value = trim($_POST[$settingKey] ?? '');
        
        // Basic validation
        if ($settingInfo['validation'] === 'required' && empty($value)) {
            $errors[] = "O campo '{$settingInfo['title']}' é obrigatório.";
        }
        
        // WhatsApp validation
        if ($settingInfo['validation'] === 'whatsapp') {
            $whatsappValidation = validateWhatsAppNumber($value);
            if (!$whatsappValidation['valid']) {
                $errors[] = "O número de WhatsApp não é válido: {$whatsappValidation['error']}";
            } else {
                $value = $whatsappValidation['formatted'];
            }
        }
        
        $newSettings[$settingKey] = $value;
    }
    
    // If no errors, update the config file
    if (empty($errors)) {
        $updatedContent = $configContent;
        
        foreach ($newSettings as $key => $value) {
            // Create regex pattern to match the constant definition
            $pattern = "/define\('$key',\s*'[^']*'\);/";
            $replacement = "define('$key', '$value');";
            
            // Update the content
            $updatedContent = preg_replace($pattern, $replacement, $updatedContent);
        }
        
        // Check if all replacements were made
        if ($updatedContent !== $configContent) {
            // Create a backup of the current config file
            $backupPath = $configFilePath . '.bak.' . date('YmdHis');
            file_put_contents($backupPath, $configContent);
            
            // Write the updated content
            if (file_put_contents($configFilePath, $updatedContent)) {
                $message = 'Configurações atualizadas com sucesso, atualize a página para ver as mudanças';
                $messageClass = 'success';
                
                // Instead of trying to redefine constants, update existing variables
                // to use in this page's context (we don't redefine constants)
                foreach ($newSettings as $key => $value) {
                    if ($key === 'WHATSAPP_NUMBER') {
                        $WHATSAPP_NUMBER = $value;
                    } elseif ($key === 'SITE_NAME') {
                        $SITE_NAME = $value;
                    }
                }

            } else {
                $message = 'Erro ao salvar as configurações. Verifique as permissões do arquivo.';
                $messageClass = 'error';
            }
        } else {
            $message = 'Nenhuma alteração foi feita nas configurações.';
            $messageClass = 'info';
        }
    } else {
        $message = 'Erro ao salvar as configurações:<br>' . implode('<br>', $errors);
        $messageClass = 'error';
    }
}

// Extract current settings from the config file
foreach ($availableSettings as $key => $info) {
    // Extract the value using regex
    if (preg_match("/define\('$key',\s*'([^']*)'\);/", $configContent, $matches)) {
        $settings[$key] = $matches[1];
    } else {
        $settings[$key] = '';
    }
}
?>

<div class="settings-page">
    <div class="page-header">
        <h2><i class="fas fa-cogs"></i> Configurações do Site</h2>
    </div>
    
    <?php if ($message): ?>
        <div class="message <?= $messageClass ?>">
            <?= $message ?>
        </div>
    <?php endif; ?>
    
    <div class="form-container">
        <form method="POST" class="settings-form">
            <?php foreach ($availableSettings as $key => $info): ?>
                <div class="form-group">
                    <label for="<?= $key ?>"><?= $info['title'] ?><?php if ($info['validation'] === 'required'): ?> <span class="required">*</span><?php endif; ?></label>
                    
                    <?php if ($info['type'] === 'text'): ?>
                        <input type="text" id="<?= $key ?>" name="<?= $key ?>" value="<?= htmlspecialchars($settings[$key]) ?>" class="form-control">
                    <?php elseif ($info['type'] === 'textarea'): ?>
                        <textarea id="<?= $key ?>" name="<?= $key ?>" class="form-control" rows="3"><?= htmlspecialchars($settings[$key]) ?></textarea>
                    <?php endif; ?>
                    
                    <?php if (!empty($info['description'])): ?>
                        <small class="form-text"><?= $info['description'] ?></small>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
            
            <div class="form-actions">
                <button type="submit" name="save_settings" class="submit-button">
                    <i class="fas fa-save"></i> Salvar Configurações
                </button>
            </div>
        </form>
    </div>

</div>

<style>
.settings-page {
    max-width: 900px;
    margin: 0 auto;
}

.settings-form {
    background-color: #fff;
    border-radius: var(--border-radius);
    padding: 30px;
    box-shadow: var(--box-shadow);
    margin-bottom: 30px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    font-weight: 500;
    margin-bottom: 8px;
}

.form-group .required {
    color: #dc3545;
}

.form-control {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 16px;
}

.form-control:focus {
    border-color: var(--primary-color);
    outline: none;
    box-shadow: 0 0 0 3px rgba(10, 0, 96, 0.1);
}

.form-text {
    display: block;
    margin-top: 6px;
    font-size: 13px;
    color: #6c757d;
}

.form-actions {
    margin-top: 30px;
}

.settings-info {
    margin-top: 30px;
}

.info-card {
    background-color: #fff;
    border-radius: var(--border-radius);
    padding: 25px;
    box-shadow: var(--box-shadow);
}

.info-card h3 {
    margin-bottom: 20px;
    color: var(--primary-color);
    font-size: 18px;
}

.info-card h3 i {
    margin-right: 8px;
}

.info-table {
    width: 100%;
    border-collapse: collapse;
}

.info-table th,
.info-table td {
    padding: 12px 15px;
    border-bottom: 1px solid #eee;
    text-align: left;
}

.info-table th {
    width: 30%;
    color: #555;
    font-weight: 500;
}

.info-table tr:last-child th,
.info-table tr:last-child td {
    border-bottom: none;
}
</style>