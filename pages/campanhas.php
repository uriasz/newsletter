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
    <title>Campanhas - Sistema de Newsletter</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="container">
        <div class="page-header">
            <h1>Gerenciar Campanhas</h1>
            <button class="btn btn-primary" onclick="abrirModalCriar()">+ Nova Campanha</button>
        </div>
        
        <div id="mensagem"></div>
        
        <div class="table-container">
            <table id="tabelaCampanhas">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Assunto</th>
                        <th>Status</th>
                        <th>Data</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody id="listaCampanhas">
                    <tr>
                        <td colspan="5" class="loading">Carregando...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Modal Criar/Editar Campanha -->
    <div id="modalCampanha" class="modal">
        <div class="modal-content modal-large">
            <span class="close" onclick="fecharModal()">&times;</span>
            <h2 id="modalTitulo">Nova Campanha</h2>
            
            <form id="formCampanha">
                <input type="hidden" id="campanhaId">
                
                <div class="form-group">
                    <label for="assunto">Assunto do E-mail:</label>
                    <input type="text" id="assunto" name="assunto" required>
                </div>
                
                <div class="form-group">
                    <label>Selecionar Listas:</label>
                    <div id="listasCheckbox"></div>
                </div>
                
                <div class="form-group">
                    <label>Notícias a Incluir (opcional):</label>
                    <div style="margin-bottom: 1rem;">
                        <label for="palavraChave">Palavra-chave de busca:</label>
                        <input type="text" id="palavraChave" placeholder="Ex: Programa de Acelera" value="">
                        <button type="button" class="btn btn-secondary" onclick="buscarNoticias()" style="margin-top: 0.5rem;">Buscar Notícias</button>
                    </div>
                    <div id="noticiasCheckbox" class="noticias-checkbox">
                        <p style="color: #999; text-align: center;">Digite uma palavra-chave e clique em "Buscar Notícias"</p>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="conteudoHtml">Conteúdo HTML:</label>
                    <textarea id="conteudoHtml" name="conteudoHtml" rows="15" required></textarea>
                    <small>Dica: Use HTML para formatar seu e-mail. O pixel de rastreamento será adicionado automaticamente.</small>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Salvar Rascunho</button>
                    <button type="button" class="btn btn-success" onclick="enviarCampanha()">Enviar Agora</button>
                    <button type="button" class="btn btn-secondary" onclick="fecharModal()">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
    
    <script src="../assets/js/campanhas.js"></script>
</body>
</html>
