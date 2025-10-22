let listas = [];

// Carregar assinantes
async function carregarAssinantes() {
    try {
        const response = await fetch('../api/assinantes.php');
        const result = await response.json();
        
        if (result.success) {
            exibirAssinantes(result.data);
        }
    } catch (error) {
        console.error('Erro ao carregar assinantes:', error);
    }
}

// Carregar listas para os checkboxes
async function carregarListas() {
    try {
        const response = await fetch('../api/listas.php');
        const result = await response.json();
        
        if (result.success) {
            listas = result.data;
            atualizarCheckboxListas();
        }
    } catch (error) {
        console.error('Erro ao carregar listas:', error);
    }
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

function exibirAssinantes(assinantes) {
    const tbody = document.getElementById('listaAssinantes');
    
    if (assinantes.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="loading">Nenhum assinante cadastrado</td></tr>';
        return;
    }
    
    tbody.innerHTML = assinantes.map(assinante => {
        const nomesListas = assinante.listas
            .map(listaId => {
                const lista = listas.find(l => l.id === listaId);
                return lista ? lista.nome : 'Lista não encontrada';
            })
            .join(', ') || 'Nenhuma';
        
        return `
            <tr>
                <td>${assinante.id}</td>
                <td>${assinante.nome}</td>
                <td>${assinante.email}</td>
                <td>${nomesListas}</td>
                <td>
                    <button class="btn btn-secondary" onclick="editarAssinante(${assinante.id})">Editar</button>
                    <button class="btn btn-danger" onclick="excluirAssinante(${assinante.id})">Excluir</button>
                </td>
            </tr>
        `;
    }).join('');
}

function abrirModalAdicionar() {
    document.getElementById('modalTitulo').textContent = 'Adicionar Assinante';
    document.getElementById('assinanteId').value = '';
    document.getElementById('formAssinante').reset();
    atualizarCheckboxListas();
    document.getElementById('modalAssinante').style.display = 'block';
}

async function editarAssinante(id) {
    try {
        const response = await fetch('../api/assinantes.php');
        const result = await response.json();
        
        if (result.success) {
            const assinante = result.data.find(a => a.id === id);
            
            if (assinante) {
                document.getElementById('modalTitulo').textContent = 'Editar Assinante';
                document.getElementById('assinanteId').value = assinante.id;
                document.getElementById('nome').value = assinante.nome;
                document.getElementById('email').value = assinante.email;
                atualizarCheckboxListas(assinante.listas);
                document.getElementById('modalAssinante').style.display = 'block';
            }
        }
    } catch (error) {
        console.error('Erro ao carregar assinante:', error);
    }
}

async function excluirAssinante(id) {
    if (!confirm('Tem certeza que deseja excluir este assinante?')) return;
    
    try {
        const response = await fetch('../api/assinantes.php', {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id=${id}`
        });
        
        const result = await response.json();
        mostrarMensagem(result.message, result.success ? 'success' : 'error');
        
        if (result.success) {
            carregarAssinantes();
        }
    } catch (error) {
        mostrarMensagem('Erro ao excluir assinante', 'error');
    }
}

function fecharModal() {
    document.getElementById('modalAssinante').style.display = 'none';
}

document.getElementById('formAssinante').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const id = document.getElementById('assinanteId').value;
    const nome = document.getElementById('nome').value;
    const email = document.getElementById('email').value;
    
    const listasCheckboxes = document.querySelectorAll('#listasCheckbox input[type="checkbox"]:checked');
    const listasSelecionadas = Array.from(listasCheckboxes).map(cb => parseInt(cb.value));
    
    const formData = new FormData();
    formData.append('nome', nome);
    formData.append('email', email);
    formData.append('listas', JSON.stringify(listasSelecionadas));
    
    try {
        let response;
        
        if (id) {
            // Atualizar
            formData.append('id', id);
            const body = new URLSearchParams();
            body.append('id', id);
            body.append('nome', nome);
            body.append('email', email);
            body.append('listas', JSON.stringify(listasSelecionadas));
            
            response = await fetch('../api/assinantes.php', {
                method: 'PUT',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: body.toString()
            });
        } else {
            // Adicionar
            response = await fetch('../api/assinantes.php', {
                method: 'POST',
                body: formData
            });
        }
        
        const result = await response.json();
        mostrarMensagem(result.message, result.success ? 'success' : 'error');
        
        if (result.success) {
            fecharModal();
            carregarAssinantes();
        }
    } catch (error) {
        mostrarMensagem('Erro ao salvar assinante', 'error');
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
    carregarAssinantes();
});

// Fechar modal ao clicar fora
window.onclick = (event) => {
    const modal = document.getElementById('modalAssinante');
    if (event.target === modal) {
        fecharModal();
    }
};
