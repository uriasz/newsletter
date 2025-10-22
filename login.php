<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Newsletter</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-box">
            <h1>Sistema de Newsletter</h1>
            <h2>Login</h2>
            
            <div id="mensagem"></div>
            
            <form id="loginForm">
                <div class="form-group">
                    <label for="email">E-mail:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="senha">Senha:</label>
                    <input type="password" id="senha" name="senha" required>
                </div>
                
                <button type="submit" class="btn btn-primary">Entrar</button>
            </form>
            
            <p class="login-info">
                <strong>Credenciais padrão:</strong><br>
                E-mail: admin@example.com<br>
                Senha: password
            </p>
        </div>
    </div>
    
    <script src="assets/js/login.js"></script>
</body>
</html>
