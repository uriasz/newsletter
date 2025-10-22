// Verifica se está na página de detalhes de uma campanha
const campanhaId = typeof CAMPANHA_ID !== 'undefined' ? CAMPANHA_ID : null;

if (campanhaId) {
    // Página de detalhes de uma campanha específica
    carregarRelatorio(campanhaId);
} else {
    // Página de listagem de campanhas
    carregarListaCampanhas();
}

async function carregarListaCampanhas() {
    try {
        const response = await fetch('../api/campanhas.php');
        const result = await response.json();
        
        if (result.success) {
            exibirListaCampanhas(result.data.filter(c => c.status === 'enviada'));
        }
    } catch (error) {
        console.error('Erro ao carregar campanhas:', error);
    }
}

function exibirListaCampanhas(campanhas) {
    const container = document.getElementById('listaCampanhasRelatorio');
    
    if (campanhas.length === 0) {
        container.innerHTML = '<p class="loading">Nenhuma campanha enviada ainda</p>';
        return;
    }
    
    container.innerHTML = campanhas.map(campanha => `
        <div class="campanha-item">
            <div>
                <h3>${campanha.assunto}</h3>
                <p style="color: #666; font-size: 0.9rem;">Enviada em: ${campanha.data_envio}</p>
            </div>
            <a href="relatorios.php?id=${campanha.id}" class="btn btn-primary">Ver Relatório</a>
        </div>
    `).join('');
}

async function carregarRelatorio(campanhaId) {
    try {
        const response = await fetch(`../api/relatorios.php?campanha_id=${campanhaId}`);
        const result = await response.json();
        
        if (result.success) {
            exibirRelatorio(result.data);
        }
    } catch (error) {
        console.error('Erro ao carregar relatório:', error);
    }
}

function exibirRelatorio(dados) {
    // Atualiza os cards de estatísticas
    document.getElementById('totalEnvios').textContent = dados.total_envios;
    document.getElementById('totalAberturas').textContent = dados.total_aberturas;
    document.getElementById('aberturasUnicas').textContent = dados.aberturas_unicas;
    document.getElementById('taxaAberturaDetalhada').textContent = dados.taxa_abertura + '%';
    
    // Exibe a tabela de aberturas
    const tbody = document.getElementById('tabelaAberturas');
    
    if (dados.aberturas_detalhadas.length === 0) {
        tbody.innerHTML = '<tr><td colspan="4" class="loading">Nenhuma abertura registrada ainda</td></tr>';
        return;
    }
    
    tbody.innerHTML = dados.aberturas_detalhadas.map(abertura => `
        <tr>
            <td>${abertura.nome}</td>
            <td>${abertura.email}</td>
            <td>${abertura.data_abertura}</td>
            <td>${abertura.total_aberturas}</td>
        </tr>
    `).join('');
}

// Inicialização
document.addEventListener('DOMContentLoaded', () => {
    // A função apropriada já foi chamada no início do script
});
