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

// Handle manual backup
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_backup'])) {
    // Create a backup
    $backup_result = backup_database();
    
    if ($backup_result['success']) {
        $message = "Backup criado com sucesso: " . basename($backup_result['file']);
        $messageClass = 'success';
    } else {
        $message = "Erro ao criar backup: " . $backup_result['message'];
        $messageClass = 'error';
    }
}

// Handle save settings
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

// Get list of recent backups
$backup_dir = __DIR__ . '/../../backups';
$backups = [];

if (is_dir($backup_dir)) {
    $backup_files = glob($backup_dir . '/*.sql');
    
    // Sort files by modification time (newest first)
    usort($backup_files, function($a, $b) {
        return filemtime($b) - filemtime($a);
    });
    
    // Get the 5 most recent backups
    $backups = array_slice($backup_files, 0, 5);
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
    
    <div class="settings-tabs">
        <div class="tabs-header">
            <button class="tab-button active" data-tab="general">Configurações Gerais</button>
            <button class="tab-button" data-tab="backup">Backup do Banco de Dados</button>
        </div>
        
        <div class="tabs-content">
            <!-- General Settings Tab -->
            <div class="tab-content active" id="general-tab">
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
            
            <!-- Backup Tab -->
            <div class="tab-content" id="backup-tab">
                <div class="backup-container">
                    <div class="backup-info">
                        <h3><i class="fas fa-database"></i> Backup do Banco de Dados</h3>
                        <p>Crie um backup manual do banco de dados ou visualize os backups recentes.</p>
                        
                        <div class="backup-actions">
                            <form method="POST" class="backup-form">
                                <button type="submit" name="create_backup" class="backup-button">
                                    <i class="fas fa-download"></i> Criar Backup Manual
                                </button>
                            </form>
                        </div>
                        
                        <?php if (!empty($backups)): ?>
                            <div class="recent-backups">
                                <h4>Backups Recentes</h4>
                                <div class="backup-list">
                                    <table class="data-table">
                                        <thead>
                                            <tr>
                                                <th>Nome do Arquivo</th>
                                                <th>Data</th>
                                                <th>Tamanho</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($backups as $backup): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars(basename($backup)) ?></td>
                                                    <td><?= date('d/m/Y H:i:s', filemtime($backup)) ?></td>
                                                    <td><?= formatFileSize(filesize($backup)) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="no-backups">
                                <p>Nenhum backup encontrado. Crie seu primeiro backup clicando no botão acima.</p>
                            </div>
                        <?php endif; ?>
                        
                        <div class="backup-info-box">
                            <h4>Informações sobre Backups</h4>
                            <ul>
                                <li><strong>Backup Manual:</strong> Cria um backup imediato do banco de dados.</li>
                                <li><strong>Backup Automático de Login:</strong> Um backup é criado toda vez que um administrador faz login.</li>
                                <li><strong>Backup Programado:</strong> Um backup automático é criado a cada 10 dias.</li>
                                <li><strong>Localização dos Backups:</strong> Todos os backups são armazenados no diretório <code>/backups</code> do site.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab functionality
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons and contents
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));
            
            // Add active class to current button
            this.classList.add('active');
            
            // Show the corresponding tab content
            const tabId = this.getAttribute('data-tab');
            document.getElementById(tabId + '-tab').classList.add('active');
        });
    });
});
</script>