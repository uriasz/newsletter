// Carregar listas
async function carregarListas() {
    try {
        const response = await fetch('../api/listas.php');
        const result = await response.json();
        
        if (result.success) {
            exibirListas(result.data);
        }
    } catch (error) {
        console.error('Erro ao carregar listas:', error);
    }
}

function exibirListas(listas) {
    const tbody = document.getElementById('listaListas');
    
    if (listas.length === 0) {
        tbody.innerHTML = '<tr><td colspan="4" class="loading">Nenhuma lista criada</td></tr>';
        return;
    }
    
    tbody.innerHTML = listas.map(lista => `
        <tr>
            <td>${lista.id}</td>
            <td>${lista.nome}</td>
            <td>${lista.total_assinantes || 0}</td>
            <td>
                <button class="btn btn-secondary" onclick="editarLista(${lista.id})">Editar</button>
                <button class="btn btn-danger" onclick="excluirLista(${lista.id})">Excluir</button>
            </td>
        </tr>
    `).join('');
}

function abrirModalAdicionar() {
    document.getElementById('modalTitulo').textContent = 'Adicionar Lista';
    document.getElementById('listaId').value = '';
    document.getElementById('formLista').reset();
    document.getElementById('modalLista').style.display = 'block';
}

async function editarLista(id) {
    try {
        const response = await fetch('../api/listas.php');
        const result = await response.json();
        
        if (result.success) {
            const lista = result.data.find(l => l.id === id);
            
            if (lista) {
                document.getElementById('modalTitulo').textContent = 'Editar Lista';
                document.getElementById('listaId').value = lista.id;
                document.getElementById('nomeLista').value = lista.nome;
                document.getElementById('descricao').value = lista.descricao || '';
                document.getElementById('modalLista').style.display = 'block';
            }
        }
    } catch (error) {
        console.error('Erro ao carregar lista:', error);
    }
}

async function excluirLista(id) {
    if (!confirm('Tem certeza que deseja excluir esta lista? Ela será removida de todos os assinantes.')) return;
    
    try {
        const response = await fetch('../api/listas.php', {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id=${id}`
        });
        
        const result = await response.json();
        mostrarMensagem(result.message, result.success ? 'success' : 'error');
        
        if (result.success) {
            carregarListas();
        }
    } catch (error) {
        mostrarMensagem('Erro ao excluir lista', 'error');
    }
}

function fecharModal() {
    document.getElementById('modalLista').style.display = 'none';
}

document.getElementById('formLista').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const id = document.getElementById('listaId').value;
    const formData = new FormData(e.target);
    
    try {
        let response;
        
        if (id) {
            // Atualizar
            const body = new URLSearchParams();
            body.append('id', id);
            body.append('nome', formData.get('nome'));
            body.append('descricao', formData.get('descricao'));
            
            response = await fetch('../api/listas.php', {
                method: 'PUT',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: body.toString()
            });
        } else {
            // Adicionar
            response = await fetch('../api/listas.php', {
                method: 'POST',
                body: formData
            });
        }
        
        const result = await response.json();
        mostrarMensagem(result.message, result.success ? 'success' : 'error');
        
        if (result.success) {
            fecharModal();
            carregarListas();
        }
    } catch (error) {
        mostrarMensagem('Erro ao salvar lista', 'error');
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
document.addEventListener('DOMContentLoaded', carregarListas);

// Fechar modal ao clicar fora
window.onclick = (event) => {
    const modal = document.getElementById('modalLista');
    if (event.target === modal) {
        fecharModal();
    }
};
