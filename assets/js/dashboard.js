// Dashboard Statistics
async function carregarEstatisticas() {
    try {
        const response = await fetch('api/dashboard.php');
        const result = await response.json();
        
        if (result.success) {
            document.getElementById('totalAssinantes').textContent = result.data.total_assinantes;
            document.getElementById('totalListas').textContent = result.data.total_listas;
            document.getElementById('totalCampanhas').textContent = result.data.total_campanhas;
            document.getElementById('taxaAbertura').textContent = result.data.taxa_abertura_media + '%';
        }
    } catch (error) {
        console.error('Erro ao carregar estatísticas:', error);
    }
}

// Carregar notícias recentes
async function carregarNoticiasRecentes() {
    try {
        const response = await fetch('api/noticias.php?palavra_chave=&limite=6');
        const result = await response.json();
        
        const grid = document.getElementById('gridNoticias');
        
        if (result.success && result.data.length > 0) {
            grid.innerHTML = result.data.map(noticia => `
                <div class="box-noticia">
                    <a href="${noticia.link}" target="_blank">
                        <img class="imagem-noticia" src="${noticia.imagem_url}" alt="${noticia.assunto}">
                        <div class="data-noticia">
                            <p><strong>DATA:</strong> ${noticia.data}</p>
                        </div>
                        <div class="assunto-noticia">
                            <p>${noticia.assunto}</p>
                        </div>
                    </a>
                </div>
            `).join('');
        } else {
            grid.innerHTML = '<p style="text-align: center; color: rgba(255,255,255,0.7);">Nenhuma notícia disponível</p>';
        }
    } catch (error) {
        console.error('Erro ao carregar notícias:', error);
        const grid = document.getElementById('gridNoticias');
        grid.innerHTML = '<p style="text-align: center; color: rgba(255,255,255,0.7);">Erro ao carregar notícias</p>';
    }
}

// Carrega ao iniciar a página
document.addEventListener('DOMContentLoaded', () => {
    carregarEstatisticas();
    carregarNoticiasRecentes();
});
