<?php
define('SYSTEM_INIT', true);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listas - Sistema de Newsletter</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="container">
        <div class="page-header">
            <h1>Gerenciar Listas de Segmentação</h1>
            <button class="btn btn-primary" onclick="abrirModalAdicionar()">+ Adicionar Lista</button>
        </div>
        
        <div id="mensagem"></div>
        
        <div class="table-container">
            <table id="tabelaListas">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome da Lista</th>
                        <th>Qtd. Assinantes</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody id="listaListas">
                    <tr>
                        <td colspan="4" class="loading">Carregando...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Modal Adicionar/Editar -->
    <div id="modalLista" class="modal">
        <div class="modal-content">
            <span class="close" onclick="fecharModal()">&times;</span>
            <h2 id="modalTitulo">Adicionar Lista</h2>
            
            <form id="formLista">
                <input type="hidden" id="listaId">
                
                <div class="form-group">
                    <label for="nomeLista">Nome da Lista:</label>
                    <input type="text" id="nomeLista" name="nome" required>
                </div>
                
                <div class="form-group">
                    <label for="descricao">Descrição (opcional):</label>
                    <textarea id="descricao" name="descricao" rows="3"></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Salvar</button>
                    <button type="button" class="btn btn-secondary" onclick="fecharModal()">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
    
    <script src="../assets/js/listas.js"></script>
</body>
</html>
