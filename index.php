<?php
// Inicializa o sistema
define('SYSTEM_INIT', true);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema de Newsletter</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container">
        <div class="dashboard">
            <h1>Bem-vindo, <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?>!</h1>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Total de Assinantes</h3>
                    <p class="stat-number" id="totalAssinantes">0</p>
                </div>
                
                <div class="stat-card">
                    <h3>Listas Criadas</h3>
                    <p class="stat-number" id="totalListas">0</p>
                </div>
                
                <div class="stat-card">
                    <h3>Campanhas Enviadas</h3>
                    <p class="stat-number" id="totalCampanhas">0</p>
                </div>
                
                <div class="stat-card">
                    <h3>Taxa de Abertura Média</h3>
                    <p class="stat-number" id="taxaAbertura">0%</p>
                </div>
            </div>
            
            <div class="quick-actions">
                <h2>Ações Rápidas</h2>
                <div class="action-buttons">
                    <a href="pages/assinantes.php" class="btn btn-primary">Gerenciar Assinantes</a>
                    <a href="pages/listas.php" class="btn btn-primary">Gerenciar Listas</a>
                    <a href="pages/campanhas.php" class="btn btn-primary">Criar Campanha</a>
                    <a href="pages/relatorios.php" class="btn btn-secondary">Ver Relatórios</a>
                </div>
            </div>
            
            <div class="noticias-section" id="noticiasRecentes">
                <h3>Notícias Recentes</h3>
                <div class="noticias-grid" id="gridNoticias">
                    <p style="text-align: center; color: rgba(255,255,255,0.7);">Carregando notícias...</p>
                </div>
            </div>
        </div>
    </div>
    
    <script src="assets/js/dashboard.js"></script>
</body>
</html>
