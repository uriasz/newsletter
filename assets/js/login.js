// Login Form Handler
document.getElementById('loginForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const mensagem = document.getElementById('mensagem');
    
    try {
        const response = await fetch('api/login.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            mensagem.className = 'success';
            mensagem.textContent = result.message;
            mensagem.style.display = 'block';
            
            // Redireciona após 1 segundo
            setTimeout(() => {
                window.location.href = 'index.php';
            }, 1000);
        } else {
            mensagem.className = 'error';
            mensagem.textContent = result.message;
            mensagem.style.display = 'block';
        }
    } catch (error) {
        mensagem.className = 'error';
        mensagem.textContent = 'Erro ao fazer login: ' + error.message;
        mensagem.style.display = 'block';
    }
});
