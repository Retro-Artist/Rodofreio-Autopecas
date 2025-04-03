<?php
// admin/pages/home.php

// Get user info
$userQuery = "SELECT username, email, created_at FROM users WHERE id = :user_id";
$userStmt = $pdo->prepare($userQuery);
$userStmt->execute(['user_id' => $_SESSION['admin_id']]);
$user = $userStmt->fetch();

// Get dashboard statistics
$statsQuery = "SELECT 
    COUNT(*) as total_products,
    SUM(CASE WHEN status = 'published' THEN 1 ELSE 0 END) as published_products,
    SUM(CASE WHEN featured = 1 THEN 1 ELSE 0 END) as featured_products,
    SUM(CASE WHEN availability = 1 THEN 1 ELSE 0 END) as available_products,
    COUNT(DISTINCT category_id) as categories
FROM posts";
$stats = $pdo->query($statsQuery)->fetch();

// Dados reais de visualizações mensais
$monthsLabels = [];
$viewsData = [];
$contactsData = [];

// Obtém os últimos 12 meses
for ($i = 11; $i >= 0; $i--) {
    $month = date('Y-m', strtotime("-$i months"));
    $monthName = date('F', strtotime("-$i months"));
    $monthsLabels[] = $monthName;
    
    // Consulta para visualizações mensais
    $viewsQuery = "SELECT COUNT(*) FROM product_events 
                  WHERE event_type = 'view' 
                  AND DATE_FORMAT(created_at, '%Y-%m') = :month";
    $viewsStmt = $pdo->prepare($viewsQuery);
    $viewsStmt->execute(['month' => $month]);
    $viewsData[] = (int)$viewsStmt->fetchColumn();
    
    // Consulta para contatos WhatsApp mensais
    $contactsQuery = "SELECT COUNT(*) FROM product_events 
                     WHERE event_type = 'whatsapp_click' 
                     AND DATE_FORMAT(created_at, '%Y-%m') = :month";
    $contactsStmt = $pdo->prepare($contactsQuery);
    $contactsStmt->execute(['month' => $month]);
    $contactsData[] = (int)$contactsStmt->fetchColumn();
}

// Total do mês atual
$currentMonth = date('Y-m');
$currentMonthViewsQuery = "SELECT COUNT(*) FROM product_events 
                          WHERE event_type = 'view' 
                          AND DATE_FORMAT(created_at, '%Y-%m') = :month";
$currentMonthViewsStmt = $pdo->prepare($currentMonthViewsQuery);
$currentMonthViewsStmt->execute(['month' => $currentMonth]);
$currentMonthViews = (int)$currentMonthViewsStmt->fetchColumn();

// Contatos WhatsApp - inclui todos os tipos
$currentMonthContactsQuery = "SELECT COUNT(*) FROM product_events 
                             WHERE event_type = 'whatsapp_click' 
                             AND DATE_FORMAT(created_at, '%Y-%m') = :month";
$currentMonthContactsStmt = $pdo->prepare($currentMonthContactsQuery);
$currentMonthContactsStmt->execute(['month' => $currentMonth]);
$currentMonthContacts = (int)$currentMonthContactsStmt->fetchColumn();

// Calcular taxa de conversão
$conversionRate = $currentMonthViews > 0 ? round(($currentMonthContacts / $currentMonthViews) * 100, 1) : 0;

// Produtos por categoria para gráfico
$categoryStatsQuery = "SELECT 
    c.name as category_name, 
    COUNT(p.id) as product_count
FROM categories c
LEFT JOIN posts p ON c.id = p.category_id
GROUP BY c.id
ORDER BY product_count DESC";
$categoryStats = $pdo->query($categoryStatsQuery)->fetchAll();

// Get recent products (últimos 8)
$recentQuery = "SELECT p.id, p.title, p.slug, p.sku, p.description, p.status, 
                p.featured, p.availability, p.main_picture, p.created_at, c.name as category_name 
                FROM posts p
                LEFT JOIN categories c ON p.category_id = c.id 
                ORDER BY p.created_at DESC 
                LIMIT 8";
$products = $pdo->query($recentQuery)->fetchAll();

// Get produtos mais visualizados (top 5)
$topViewedQuery = "SELECT p.id, p.title, p.slug, 
                 COUNT(CASE WHEN pe.event_type = 'view' THEN 1 ELSE NULL END) as view_count,
                 COUNT(CASE WHEN pe.event_type = 'whatsapp_click' THEN 1 ELSE NULL END) as contact_count
                 FROM posts p
                 LEFT JOIN product_events pe ON p.id = pe.product_id
                 GROUP BY p.id
                 ORDER BY view_count DESC
                 LIMIT 5";
$topViewedProducts = $pdo->query($topViewedQuery)->fetchAll();
?>

<div class="admin-dashboard">
    <!-- Welcome Section with Business Metrics -->
    <div class="dashboard-welcome">
        <div class="welcome-text">
            <h2>Bem-vindo ao Painel Rodofreios</h2>
            <p>Gerencie produtos, categorias e acompanhe o desempenho da loja</p>
        </div>
        <div class="business-metrics">
            <div class="metric-item">
                <i class="fas fa-eye"></i>
                <div class="metric-content">
                    <span class="metric-label">Visualizações Este Mês</span>
                    <span class="metric-value"><?= number_format($currentMonthViews, 0, ',', '.') ?></span>
                </div>
            </div>
            <div class="metric-item">
                <i class="fab fa-whatsapp"></i>
                <div class="metric-content">
                    <span class="metric-label">Contatos WhatsApp</span>
                    <span class="metric-value"><?= number_format($currentMonthContacts, 0, ',', '.') ?></span>
                </div>
            </div>
            <div class="metric-item conversion-metric">
                <i class="fas fa-exchange-alt"></i>
                <div class="metric-content">
                    <span class="metric-label">Taxa de Conversão</span>
                    <span class="metric-value"><?= $conversionRate ?>%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="dashboard-stats">
        <div class="stat-card">
            <div class="stat-icon products-icon">
                <i class="fas fa-box"></i>
            </div>
            <div class="stat-info">
                <span class="stat-number"><?= $stats['total_products'] ?></span>
                <span class="stat-label">Total de Produtos</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon published-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-info">
                <span class="stat-number"><?= $stats['published_products'] ?></span>
                <span class="stat-label">Publicados</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon available-icon">
                <i class="fas fa-truck"></i>
            </div>
            <div class="stat-info">
                <span class="stat-number"><?= $stats['available_products'] ?></span>
                <span class="stat-label">Disponíveis</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon categories-icon">
                <i class="fas fa-tags"></i>
            </div>
            <div class="stat-info">
                <span class="stat-number"><?= $stats['categories'] ?></span>
                <span class="stat-label">Categorias</span>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="dashboard-charts">
        <div class="chart-container">
            <div class="chart-header">
                <h3>Visualizações e Contatos</h3>
            </div>
            <div class="chart-body">
                <canvas id="performanceChart"></canvas>
            </div>
        </div>

        <div class="chart-container">
            <div class="chart-header">
                <h3>Produtos por Categoria</h3>
            </div>
            <div class="chart-body">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <a href="index.php?page=product_create" class="action-button">
            <i class="fas fa-plus"></i> Adicionar Produto
        </a>
        <a href="index.php?page=category_create" class="action-button action-button-secondary">
            <i class="fas fa-folder-plus"></i> Adicionar Categoria
        </a>
        <a href="<?= BASE_URL ?>" class="action-button action-button-outline" target="_blank">
            <i class="fas fa-eye"></i> Visualizar Site
        </a>
    </div>

    <!-- Recent Products & Top Products Section -->
    <div class="dashboard-data-containers">
        <!-- Recent Products -->
        <div class="data-container">
            <div class="section-header">
                <h3>Produtos Recentes</h3>
                <a href="?page=product_list" class="view-all">Ver Todos</a>
            </div>

            <div class="product-mini-list">
                <?php foreach (array_slice($products, 0, 4) as $product): ?>
                    <div class="product-mini">
                        <div class="product-mini__image">
                            <?php if ($product['main_picture']): ?>
                                <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($product['main_picture']) ?>"
                                    alt="<?= htmlspecialchars($product['title']) ?>">
                            <?php else: ?>
                                <div class="no-image"><i class="fas fa-image"></i></div>
                            <?php endif; ?>
                        </div>
                        <div class="product-mini__info">
                            <h4 class="product-mini__title"><?= htmlspecialchars($product['title']) ?></h4>
                            <div class="product-mini__meta">
                                <span class="product-mini__category"><?= htmlspecialchars($product['category_name'] ?? 'Sem categoria') ?></span>
                            </div>
                            <div class="product-mini__status">
                                <?php if ($product['status'] === 'published'): ?>
                                    <span class="status-badge status-published">Publicado</span>
                                <?php else: ?>
                                    <span class="status-badge status-draft">Rascunho</span>
                                <?php endif; ?>
                                <?php if ($product['featured']): ?>
                                    <span class="status-badge status-featured">Destaque</span>
                                <?php endif; ?>
                            </div>
                            <div class="product-mini__actions">
                                <a href="?page=product_update&id=<?= $product['id'] ?>" class="mini-button mini-edit-button">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <a href="<?= getProductUrl($product['slug']) ?>" class="mini-button mini-view-button" target="_blank">
                                    <i class="fas fa-eye"></i> Ver
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Top Viewed Products -->
        <div class="data-container">
            <div class="section-header">
                <h3>Produtos Mais Visualizados</h3>
            </div>

            <div class="data-table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Visualizações</th>
                            <th>Contatos</th>
                            <th>Conversão</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($topViewedProducts as $product): 
                            $productViews = $product['view_count'];
                            $productContacts = $product['contact_count'];
                            $productConversion = $productViews > 0 ? round(($productContacts / $productViews) * 100, 1) : 0;
                        ?>
                            <tr>
                                <td>
                                    <div class="product-table-info">
                                        <?= htmlspecialchars($product['title']) ?>
                                        <span class="whatsapp-info">Compra pelo WhatsApp</span>
                                    </div>
                                </td>
                                <td><?= number_format($productViews, 0, ',', '.') ?></td>
                                <td><?= number_format($productContacts, 0, ',', '.') ?></td>
                                <td>
                                    <div class="conversion-rate <?= $productConversion >= 10 ? 'high' : ($productConversion >= 5 ? 'medium' : 'low') ?>">
                                        <?= $productConversion ?>%
                                    </div>
                                </td>
                                <td>
                                    <div class="table-actions">
                                        <a href="?page=product_update&id=<?= $product['id'] ?>" class="table-action edit-action" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?= getProductUrl($product['slug']) ?>" class="table-action view-action" title="Visualizar" target="_blank">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Performance Chart (Views & Contacts)
        const performanceCtx = document.getElementById('performanceChart').getContext('2d');
        const months = <?= json_encode($monthsLabels) ?>;
        
        const performanceChart = new Chart(performanceCtx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [
                    {
                        label: 'Visualizações',
                        data: <?= json_encode($viewsData) ?>,
                        backgroundColor: 'rgba(10, 0, 96, 0.1)',
                        borderColor: '#0a0060',
                        borderWidth: 2,
                        tension: 0.3,
                        pointBackgroundColor: '#0a0060',
                        yAxisID: 'y-views',
                        fill: true
                    },
                    {
                        label: 'Contatos WhatsApp',
                        data: <?= json_encode($contactsData) ?>,
                        backgroundColor: 'rgba(193, 0, 0, 0.1)',
                        borderColor: '#c10000',
                        borderWidth: 2,
                        tension: 0.3,
                        pointBackgroundColor: '#c10000',
                        yAxisID: 'y-contacts',
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += context.parsed.y.toLocaleString('pt-BR');
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    'y-views': {
                        type: 'linear',
                        position: 'left',
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Visualizações'
                        },
                        grid: {
                            display: true
                        },
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString('pt-BR');
                            }
                        }
                    },
                    'y-contacts': {
                        type: 'linear',
                        position: 'right',
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Contatos WhatsApp'
                        },
                        grid: {
                            display: false
                        },
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString('pt-BR');
                            }
                        }
                    }
                }
            }
        });

        // Produtos por Categoria Chart
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        const categoryData = <?= json_encode(array_column($categoryStats, 'product_count')) ?>;
        const categoryLabels = <?= json_encode(array_column($categoryStats, 'category_name')) ?>;

        // Cores para cada categoria
        const categoryColors = [
            '#0a0060', '#c10000', '#3498db', '#2ecc71', '#f1c40f', '#e74c3c', '#9b59b6', '#34495e'
        ];

        // Garantir que temos cores suficientes
        const backgroundColors = categoryData.map((_, i) => categoryColors[i % categoryColors.length]);

        const categoryChart = new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: categoryLabels,
                datasets: [{
                    data: categoryData,
                    backgroundColor: backgroundColors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            boxWidth: 15,
                            padding: 15
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((acc, val) => acc + val, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    });
</script>