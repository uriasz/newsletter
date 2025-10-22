let listas = [];
let noticiasDisponiveis = [];

// Carregar campanhas
async function carregarCampanhas() {
    try {
        const response = await fetch('../api/campanhas.php');
        const result = await response.json();
        
        if (result.success) {
            exibirCampanhas(result.data);
        }
    } catch (error) {
        console.error('Erro ao carregar campanhas:', error);
    }
}

// Carregar listas
async function carregarListas() {
    try {
        const response = await fetch('../api/listas.php');
        const result = await response.json();
        
        if (result.success) {
            listas = result.data;
        }
    } catch (error) {
        console.error('Erro ao carregar listas:', error);
    }
}

function exibirCampanhas(campanhas) {
    const tbody = document.getElementById('listaCampanhas');
    
    if (campanhas.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="loading">Nenhuma campanha criada</td></tr>';
        return;
    }
    
    tbody.innerHTML = campanhas.map(campanha => {
        const statusClass = campanha.status === 'enviada' ? 'badge-success' : 'badge-warning';
        const statusTexto = campanha.status === 'enviada' ? 'Enviada' : 'Rascunho';
        const data = campanha.data_envio || campanha.data_criacao;
        
        return `
            <tr>
                <td>${campanha.id}</td>
                <td>${campanha.assunto}</td>
                <td><span class="badge ${statusClass}">${statusTexto}</span></td>
                <td>${data}</td>
                <td>
                    ${campanha.status === 'enviada' 
                        ? `<a href="relatorios.php?id=${campanha.id}" class="btn btn-secondary">Ver Relatório</a>`
                        : `<button class="btn btn-secondary" onclick="editarCampanha('${campanha.id}')">Editar</button>`
                    }
                    <button class="btn btn-danger" onclick="excluirCampanha('${campanha.id}')">Excluir</button>
                </td>
            </tr>
        `;
    }).join('');
}

function atualizarCheckboxListas(listasSelecionadas = []) {
    const container = document.getElementById('listasCheckbox');
    container.innerHTML = '';
    
    if (listas.length === 0) {
        container.innerHTML = '<p style="color: #999;">Nenhuma lista criada. Crie uma lista primeiro.</p>';
        return;
    }
    
    listas.forEach(lista => {
        const label = document.createElement('label');
        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.value = lista.id;
        checkbox.checked = listasSelecionadas.includes(lista.id);
        
        label.appendChild(checkbox);
        label.appendChild(document.createTextNode(' ' + lista.nome));
        container.appendChild(label);
    });
}

function abrirModalCriar() {
    document.getElementById('modalTitulo').textContent = 'Nova Campanha';
    document.getElementById('campanhaId').value = '';
    document.getElementById('formCampanha').reset();
    document.getElementById('palavraChave').value = '';
    atualizarCheckboxListas();
    limparNoticiasCheckbox();
    document.getElementById('modalCampanha').style.display = 'block';
}

async function editarCampanha(id) {
    try {
        const response = await fetch('../api/campanhas.php');
        const result = await response.json();
        
        if (result.success) {
            const campanha = result.data.find(c => c.id === id);
            
            if (campanha) {
                document.getElementById('modalTitulo').textContent = 'Editar Campanha';
                document.getElementById('campanhaId').value = campanha.id;
                document.getElementById('assunto').value = campanha.assunto;
                document.getElementById('conteudoHtml').value = campanha.conteudo_html;
                atualizarCheckboxListas(campanha.listas);
                document.getElementById('modalCampanha').style.display = 'block';
            }
        }
    } catch (error) {
        console.error('Erro ao carregar campanha:', error);
    }
}

async function excluirCampanha(id) {
    if (!confirm('Tem certeza que deseja excluir esta campanha?')) return;
    
    try {
        const response = await fetch('../api/campanhas.php', {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id=${id}`
        });
        
        const result = await response.json();
        mostrarMensagem(result.message, result.success ? 'success' : 'error');
        
        if (result.success) {
            carregarCampanhas();
        }
    } catch (error) {
        mostrarMensagem('Erro ao excluir campanha', 'error');
    }
}

function fecharModal() {
    document.getElementById('modalCampanha').style.display = 'none';
}

async function enviarCampanha() {
    if (!confirm('Tem certeza que deseja enviar esta campanha agora? Esta ação não pode ser desfeita.')) return;
    
    const formData = new FormData(document.getElementById('formCampanha'));
    
    const listasCheckboxes = document.querySelectorAll('#listasCheckbox input[type="checkbox"]:checked');
    const listasSelecionadas = Array.from(listasCheckboxes).map(cb => parseInt(cb.value));
    
    if (listasSelecionadas.length === 0) {
        mostrarMensagem('Selecione pelo menos uma lista para enviar a campanha', 'error');
        return;
    }
    
    formData.append('listas', JSON.stringify(listasSelecionadas));
    formData.append('enviar', 'true');
    
    try {
        const response = await fetch('../api/campanhas.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        mostrarMensagem(result.message, result.success ? 'success' : 'error');
        
        if (result.success) {
            fecharModal();
            carregarCampanhas();
        }
    } catch (error) {
        mostrarMensagem('Erro ao enviar campanha', 'error');
    }
}

document.getElementById('formCampanha').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const id = document.getElementById('campanhaId').value;
    const formData = new FormData(e.target);
    
    const listasCheckboxes = document.querySelectorAll('#listasCheckbox input[type="checkbox"]:checked');
    const listasSelecionadas = Array.from(listasCheckboxes).map(cb => parseInt(cb.value));
    
    formData.append('listas', JSON.stringify(listasSelecionadas));
    formData.append('enviar', 'false');
    
    try {
        let response;
        
        if (id) {
            // Atualizar
            const body = new URLSearchParams();
            body.append('id', id);
            body.append('assunto', formData.get('assunto'));
            body.append('conteudoHtml', formData.get('conteudoHtml'));
            body.append('listas', JSON.stringify(listasSelecionadas));
            
            response = await fetch('../api/campanhas.php', {
                method: 'PUT',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: body.toString()
            });
        } else {
            // Adicionar
            response = await fetch('../api/campanhas.php', {
                method: 'POST',
                body: formData
            });
        }
        
        const result = await response.json();
        mostrarMensagem(result.message, result.success ? 'success' : 'error');
        
        if (result.success) {
            fecharModal();
            carregarCampanhas();
        }
    } catch (error) {
        mostrarMensagem('Erro ao salvar campanha', 'error');
    }
});

function mostrarMensagem(texto, tipo) {
    const mensagem = document.getElementById('mensagem');
    mensagem.textContent = texto;
    mensagem.className = tipo;
    mensagem.style.display = 'block';
    
    setTimeout(() => {
        mensagem.style.display = 'none';
    }, 5000);
}

// Inicialização
document.addEventListener('DOMContentLoaded', () => {
    carregarListas();
    carregarCampanhas();
});

// Fechar modal ao clicar fora
window.onclick = (event) => {
    const modal = document.getElementById('modalCampanha');
    if (event.target === modal) {
        fecharModal();
    }
};

// Funções para buscar e gerenciar notícias
async function buscarNoticias() {
    const palavraChave = document.getElementById('palavraChave').value.trim();
    
    if (!palavraChave) {
        mostrarMensagem('Digite uma palavra-chave para buscar notícias', 'error');
        return;
    }
    
    try {
        const response = await fetch(`../api/noticias.php?palavra_chave=${encodeURIComponent(palavraChave)}&limite=10`);
        const result = await response.json();
        
        if (result.success && result.data.length > 0) {
            noticiasDisponiveis = result.data;
            exibirNoticiasCheckbox(result.data);
            mostrarMensagem(result.message, 'success');
        } else {
            limparNoticiasCheckbox();
            mostrarMensagem('Nenhuma notícia encontrada com essa palavra-chave', 'error');
        }
    } catch (error) {
        console.error('Erro ao buscar notícias:', error);
        mostrarMensagem('Erro ao buscar notícias: ' + error.message, 'error');
    }
}

function exibirNoticiasCheckbox(noticias) {
    const container = document.getElementById('noticiasCheckbox');
    container.innerHTML = '';
    
    noticias.forEach(noticia => {
        const div = document.createElement('div');
        div.className = 'noticia-item-checkbox';
        
        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.value = noticia.id;
        checkbox.id = `noticia_${noticia.id}`;
        
        const info = document.createElement('div');
        info.className = 'noticia-item-info';
        info.innerHTML = `
            <strong>${noticia.assunto}</strong>
            <small>${noticia.data} - ${noticia.tipo}</small>
        `;
        
        div.appendChild(checkbox);
        div.appendChild(info);
        container.appendChild(div);
    });
}

function limparNoticiasCheckbox() {
    const container = document.getElementById('noticiasCheckbox');
    container.innerHTML = '<p style="color: #999; text-align: center;">Digite uma palavra-chave e clique em "Buscar Notícias"</p>';
    noticiasDisponiveis = [];
}

function obterNoticiasSelecionadas() {
    const checkboxes = document.querySelectorAll('#noticiasCheckbox input[type="checkbox"]:checked');
    const noticiasIds = Array.from(checkboxes).map(cb => parseInt(cb.value));
    
    return noticiasDisponiveis.filter(n => noticiasIds.includes(n.id));
}

function gerarHTMLNoticias(noticiasSelecionadas) {
    if (noticiasSelecionadas.length === 0) {
        return '';
    }
    
    let html = `
    <div style="margin-top: 30px; padding: 20px; background-color: #f8f9fa; border-radius: 8px;">
        <h2 style="color: #ec4899; text-align: center; margin-bottom: 20px;">Notícias em Destaque</h2>
        <div style="display: grid; gap: 20px;">
    `;
    
    noticiasSelecionadas.forEach(noticia => {
        html += `
        <div style="background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <a href="${noticia.link}" target="_blank" style="text-decoration: none; color: inherit; display: block;">
                <img src="${noticia.imagem_url}" alt="${noticia.assunto}" style="width: 100%; height: 200px; object-fit: cover;">
                <div style="padding: 15px;">
                    <div style="color: #6366f1; font-size: 12px; margin-bottom: 8px;">
                        <strong>DATA:</strong> ${noticia.data} | <strong>TIPO:</strong> ${noticia.tipo}
                    </div>
                    <h3 style="color: #1e293b; margin: 0; font-size: 16px; line-height: 1.4;">
                        ${noticia.assunto}
                    </h3>
                </div>
            </a>
        </div>
        `;
    });
    
    html += `
        </div>
    </div>
    `;
    
    return html;
}

// Modificar a função de envio para incluir notícias
async function enviarCampanhaComNoticias() {
    if (!confirm('Tem certeza que deseja enviar esta campanha agora? Esta ação não pode ser desfeita.')) return;
    
    const formData = new FormData(document.getElementById('formCampanha'));
    
    const listasCheckboxes = document.querySelectorAll('#listasCheckbox input[type="checkbox"]:checked');
    const listasSelecionadas = Array.from(listasCheckboxes).map(cb => parseInt(cb.value));
    
    if (listasSelecionadas.length === 0) {
        mostrarMensagem('Selecione pelo menos uma lista para enviar a campanha', 'error');
        return;
    }
    
    // Obtém notícias selecionadas
    const noticiasSelecionadas = obterNoticiasSelecionadas();
    
    // Gera HTML das notícias
    const htmlNoticias = gerarHTMLNoticias(noticiasSelecionadas);
    
    // Combina conteúdo original com notícias
    let conteudoFinal = formData.get('conteudoHtml');
    if (htmlNoticias) {
        conteudoFinal += htmlNoticias;
    }
    
    // Atualiza o conteúdo no FormData
    formData.set('conteudoHtml', conteudoFinal);
    formData.set('listas', JSON.stringify(listasSelecionadas));
    formData.set('enviar', 'true');
    
    try {
        const response = await fetch('../api/campanhas.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        mostrarMensagem(result.message, result.success ? 'success' : 'error');
        
        if (result.success) {
            fecharModal();
            carregarCampanhas();
        }
    } catch (error) {
        mostrarMensagem('Erro ao enviar campanha', 'error');
    }
}

// Atualizar a função enviarCampanha original
window.enviarCampanha = enviarCampanhaComNoticias;
