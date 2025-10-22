<?php
require_once __DIR__ . '/../includes/auth.php';
requererLogin();

// Busca dados para estatísticas
$campanhaId = $_GET['id'] ?? '';
$campanha = null;

if (!empty($campanhaId)) {
    $campanhas = lerJSON(CAMPANHAS_JSON);
    $campanha = buscarPorID($campanhas, $campanhaId, 'id');
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios - Sistema de Newsletter</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="container">
        <div class="page-header">
            <h1>Relatórios de Campanhas</h1>
        </div>
        
        <?php if (empty($campanha)): ?>
            <!-- Lista de Campanhas -->
            <div class="campanhas-list">
                <h2>Selecione uma Campanha</h2>
                <div id="listaCampanhasRelatorio"></div>
            </div>
        <?php else: ?>
            <!-- Detalhes da Campanha -->
            <div class="relatorio-detalhes">
                <div class="relatorio-header">
                    <h2><?php echo htmlspecialchars($campanha['assunto']); ?></h2>
                    <a href="relatorios.php" class="btn btn-secondary">← Voltar</a>
                </div>
                
                <div class="stats-grid">
                    <div class="stat-card">
                        <h3>Status</h3>
                        <p class="stat-text"><?php echo htmlspecialchars($campanha['status']); ?></p>
                    </div>
                    
                    <div class="stat-card">
                        <h3>Data de Envio</h3>
                        <p class="stat-text"><?php echo $campanha['data_envio'] ?? 'Não enviada'; ?></p>
                    </div>
                    
                    <div class="stat-card">
                        <h3>Total de Envios</h3>
                        <p class="stat-number" id="totalEnvios">0</p>
                    </div>
                    
                    <div class="stat-card">
                        <h3>Total de Aberturas</h3>
                        <p class="stat-number" id="totalAberturas">0</p>
                    </div>
                    
                    <div class="stat-card">
                        <h3>Taxa de Abertura</h3>
                        <p class="stat-number" id="taxaAberturaDetalhada">0%</p>
                    </div>
                    
                    <div class="stat-card">
                        <h3>Aberturas Únicas</h3>
                        <p class="stat-number" id="aberturasUnicas">0</p>
                    </div>
                </div>
                
                <div class="tabela-aberturas">
                    <h3>Quem Abriu o E-mail</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>Data da Abertura</th>
                                <th>Vezes Aberto</th>
                            </tr>
                        </thead>
                        <tbody id="tabelaAberturas">
                            <tr>
                                <td colspan="4" class="loading">Carregando...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <script>
                const CAMPANHA_ID = '<?php echo $campanhaId; ?>';
            </script>
        <?php endif; ?>
    </div>
    
    <script src="../assets/js/relatorios.js"></script>
</body>
</html>
