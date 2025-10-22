<?php
define('SYSTEM_INIT', true);
require_once __DIR__ . '/../includes/auth.php';
requererLogin();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assinantes - Sistema de Newsletter</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="container">
        <div class="page-header">
            <h1>Gerenciar Assinantes</h1>
            <button class="btn btn-primary" onclick="abrirModalAdicionar()">+ Adicionar Assinante</button>
        </div>
        
        <div id="mensagem"></div>
        
        <div class="table-container">
            <table id="tabelaAssinantes">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Listas</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody id="listaAssinantes">
                    <tr>
                        <td colspan="5" class="loading">Carregando...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Modal Adicionar/Editar -->
    <div id="modalAssinante" class="modal">
        <div class="modal-content">
            <span class="close" onclick="fecharModal()">&times;</span>
            <h2 id="modalTitulo">Adicionar Assinante</h2>
            
            <form id="formAssinante">
                <input type="hidden" id="assinanteId">
                
                <div class="form-group">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome" required>
                </div>
                
                <div class="form-group">
                    <label for="email">E-mail:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label>Listas:</label>
                    <div id="listasCheckbox"></div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Salvar</button>
                    <button type="button" class="btn btn-secondary" onclick="fecharModal()">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
    
    <script src="../assets/js/assinantes.js"></script>
</body>
</html>
