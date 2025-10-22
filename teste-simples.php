<?php
/**
 * Teste Simples - Verifique se o PHP está funcionando
 * Acesse: http://seu-servidor/newsletter/teste-simples.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Teste Rápido PHP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 10px 0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .success { border-left: 5px solid #10b981; }
        .info { border-left: 5px solid #3b82f6; }
        h1 { color: #ec4899; }
        pre { background: #f9fafb; padding: 10px; border-radius: 5px; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>✓ PHP está funcionando!</h1>
    
    <div class="box success">
        <h2>Informações Básicas</h2>
        <p><strong>Versão do PHP:</strong> <?php echo PHP_VERSION; ?></p>
        <p><strong>Sistema:</strong> <?php echo PHP_OS; ?></p>
        <p><strong>Servidor:</strong> <?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Desconhecido'; ?></p>
        <p><strong>Diretório atual:</strong> <?php echo __DIR__; ?></p>
        <p><strong>Document Root:</strong> <?php echo $_SERVER['DOCUMENT_ROOT'] ?? 'N/A'; ?></p>
    </div>

    <div class="box info">
        <h2>Teste de Arquivos</h2>
        <?php
        $arquivos = [
            'includes/config.php',
            'includes/json_handler.php',
            'login.php',
            'index.php'
        ];
        
        echo "<ul>";
        foreach ($arquivos as $arquivo) {
            $existe = file_exists($arquivo);
            $status = $existe ? '✓ Encontrado' : '✗ Não encontrado';
            $cor = $existe ? 'green' : 'red';
            echo "<li style='color: $cor;'><strong>$arquivo:</strong> $status</li>";
        }
        echo "</ul>";
        ?>
    </div>

    <div class="box info">
        <h2>Teste de Diretório data/</h2>
        <?php
        if (is_dir('data')) {
            echo "<p style='color: green;'>✓ Diretório 'data' existe</p>";
            echo "<p>Permissão de leitura: " . (is_readable('data') ? '✓ SIM' : '✗ NÃO') . "</p>";
            echo "<p>Permissão de escrita: " . (is_writable('data') ? '✓ SIM' : '✗ NÃO') . "</p>";
        } else {
            echo "<p style='color: red;'>✗ Diretório 'data' não existe!</p>";
            echo "<p>Execute: mkdir data && chmod 755 data</p>";
        }
        ?>
    </div>

    <div class="box info">
        <h2>Extensões PHP</h2>
        <?php
        $ext_necessarias = ['json', 'session', 'mysqli', 'mbstring'];
        echo "<ul>";
        foreach ($ext_necessarias as $ext) {
            $carregada = extension_loaded($ext);
            $status = $carregada ? '✓ Instalada' : '✗ Faltando';
            $cor = $carregada ? 'green' : 'orange';
            echo "<li style='color: $cor;'><strong>$ext:</strong> $status</li>";
        }
        echo "</ul>";
        ?>
    </div>

    <div class="box info">
        <h2>Teste de Sessão</h2>
        <?php
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            echo "<p style='color: green;'>✓ Sessão iniciada com sucesso!</p>";
            echo "<p>Session ID: " . session_id() . "</p>";
        } catch (Exception $e) {
            echo "<p style='color: red;'>✗ Erro ao iniciar sessão: " . $e->getMessage() . "</p>";
        }
        ?>
    </div>

    <div class="box success">
        <h2>Próximos Passos</h2>
        <ol>
            <li>Se tudo está OK acima, acesse: <a href="diagnostico.php">diagnostico.php</a> (diagnóstico completo)</li>
            <li>Depois acesse: <a href="login.php">login.php</a> (sistema)</li>
            <li>Login padrão: <strong>admin@example.com</strong> / <strong>password</strong></li>
            <li><strong style="color: red;">IMPORTANTE:</strong> Delete este arquivo após o teste!</li>
        </ol>
    </div>

    <div class="box" style="background: #fef3c7; border-left: 5px solid #f59e0b;">
        <p><strong>⚠️ SEGURANÇA:</strong> Após confirmar que tudo funciona, delete os arquivos de teste:</p>
        <ul>
            <li>teste-simples.php (este arquivo)</li>
            <li>diagnostico.php</li>
        </ul>
    </div>
</body>
</html>
