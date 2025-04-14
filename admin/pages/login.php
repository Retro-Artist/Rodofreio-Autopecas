<?php
// admin/pages/login.php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/database.php';
require_once __DIR__ . '/../../includes/functions.php';

// If already logged in, redirect to admin homepage
if (isset($_SESSION['admin_logged_in'])) {
    header("Location: ../index.php");
    exit();
}

// Process login form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Input validation
    if (empty($username) || empty($password)) {
        $error = "Por favor, preencha todos os campos";
    } else {
        try {
            // Query the database for the user
            $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_username'] = $user['username'];
                $_SESSION['admin_id'] = $user['id']; // Store user ID in session
                
                // Regenerate session ID for security
                session_regenerate_id(true);
                
                // Create a backup when user logs in
                $backup_result = backup_database();
                if ($backup_result['success']) {
                    // Optionally store backup info in session if needed
                    $_SESSION['last_backup'] = [
                        'time' => time(),
                        'file' => basename($backup_result['file'])
                    ];
                    
                    // Log successful backup after login
                    error_log("Login backup created by user {$user['username']} (ID: {$user['id']})");
                } else {
                    // Just log the error but continue with login
                    error_log("Failed to create login backup: {$backup_result['message']}");
                }
                
                header("Location: ../index.php");
                exit();
            } else {
                $error = "Credenciais inválidas. Tente novamente.";
            }
        } catch (PDOException $e) {
            error_log("Login error: " . $e->getMessage());
            $error = "Ocorreu um erro. Por favor, tente novamente mais tarde.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrativo - <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/styles.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
                <div class="site-logo">
                    <i class="fas fa-cogs"></i>
                </div>
                <h1><?= SITE_NAME ?></h1>
                <h2>Painel Administrativo</h2>
            </div>
            
            <?php if (isset($error)): ?>
                <div class="message error">
                    <i class="fas fa-exclamation-circle"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="" class="login-form">
                <div class="form-group">
                    <label for="username">Usuário:</label>
                    <div class="input-with-icon">
                        <i class="fas fa-user"></i>
                        <input type="text" id="username" name="username" 
                               required value="<?= htmlspecialchars($username ?? '') ?>" autocomplete="username">
                    </div>
                </div>
                <div class="form-group">
                    <label for="password">Senha:</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" required autocomplete="current-password">
                    </div>
                </div>
                <button type="submit" class="login-button">
                    <i class="fas fa-sign-in-alt"></i> Entrar
                </button>
            </form>
            
            <div class="back-link">
                <a href="<?= BASE_URL ?>/">
                    <i class="fas fa-arrow-left"></i> Voltar para o site
                </a>
            </div>
        </div>
        
        <div class="login-footer">
            &copy; <?= date('Y') ?> <?= SITE_NAME ?> - Todos os direitos reservados
        </div>
    </div>
</body>
</html>