# 📤 Guia de Instalação via FTP - Servidor da Prefeitura

## 🎯 Passo a Passo para Upload via FTP

### 1. Preparar o Sistema Localmente

Antes de fazer upload, certifique-se que estes arquivos existem na pasta local:
- ✅ Todos os arquivos PHP
- ✅ Pasta `data/` (vazia, mas criada)
- ✅ Pasta `assets/` com CSS e JS
- ✅ `.htaccess` simplificado
- ✅ `diagnostico.php` (para testar no servidor)

### 2. Conectar via FTP

**Opções de Cliente FTP:**
- FileZilla (recomendado)
- WinSCP
- Cliente FTP do Windows Explorer
- Ou o cliente FTP fornecido pela prefeitura

**Dados de Conexão:**
```
Host: ftp.pjf.mg.gov.br (ou IP fornecido)
Usuário: [seu usuário]
Senha: [sua senha]
Porta: 21 (FTP) ou 22 (SFTP)
```

### 3. Estrutura no Servidor

Faça upload para o diretório correto:
```
/public_html/newsletter/          (ou)
/www/newsletter/                  (ou)
/htdocs/newsletter/               (ou)
/html/newsletter/
```

**⚠️ IMPORTANTE:** Pergunte ao administrador qual é o caminho correto!

### 4. Ordem de Upload (FileZilla)

**Passo 1: Criar estrutura de pastas**
```
📁 newsletter/
  📁 api/
  📁 assets/
    📁 css/
    📁 js/
  📁 data/           ← IMPORTANTE: criar vazia
  📁 includes/
  📁 pages/
```

**Passo 2: Fazer upload dos arquivos**
1. Selecione TODOS os arquivos locais
2. Arraste para o servidor
3. Aguarde conclusão (progresso no rodapé do FileZilla)

**Passo 3: Configurar permissões via FTP**

No FileZilla, clique com botão direito → "Permissões de arquivo":

```
Diretórios:
✅ newsletter/        → 755 (rwxr-xr-x)
✅ newsletter/data/   → 755 (rwxr-xr-x)
✅ newsletter/assets/ → 755 (rwxr-xr-x)

Arquivos PHP:
✅ *.php             → 644 (rw-r--r--)

Arquivos JSON (se já existirem):
✅ data/*.json       → 644 (rw-r--r--)

Especial:
✅ .htaccess         → 644 (rw-r--r--)
```

**Como definir permissões no FileZilla:**
1. Clique com botão direito na pasta/arquivo
2. Escolha "Permissões de arquivo..."
3. Digite o valor numérico (755 ou 644)
4. Marque "Recursivo em subdiretórios" se for pasta
5. Clique OK

### 5. Configurar o SITE_URL

**ANTES de fazer upload**, edite `includes/config.php`:

```php
<?php
// IMPORTANTE: Ajustar conforme o caminho no servidor
define('SITE_URL', 'https://www.pjf.mg.gov.br/newsletter');

// Se estiver em subdiretório diferente, ajuste:
// define('SITE_URL', 'https://www.pjf.mg.gov.br/sistemas/newsletter');
// define('SITE_URL', 'https://intranet.pjf.mg.gov.br/newsletter');

date_default_timezone_set('America/Sao_Paulo');
?>
```

### 6. Testar Instalação

Após upload completo, acesse:

**Teste 1: Diagnóstico**
```
https://www.pjf.mg.gov.br/newsletter/diagnostico.php
```

Este arquivo vai mostrar TODOS os problemas!

**Teste 2: Login direto**
```
https://www.pjf.mg.gov.br/newsletter/login.php
```

### 7. Problemas Comuns via FTP

#### ❌ Erro 500 - Internal Server Error

**Solução 1: Renomear .htaccess**
1. No FileZilla, localize o arquivo `.htaccess`
2. Clique com botão direito → Renomear
3. Renomeie para `.htaccess.old`
4. Teste novamente o site
5. Se funcionar, o problema é o .htaccess

**Solução 2: Usar .htaccess mínimo**
Crie um arquivo novo `.htaccess` com APENAS isto:
```apache
Options -Indexes
```

**Solução 3: Sem .htaccess**
Delete completamente o arquivo e confie na segurança do PHP.

#### ❌ Erro 404 - Not Found

Verifique:
- O arquivo está na pasta correta?
- O caminho no SITE_URL está correto?
- O arquivo foi enviado (não ficou na fila)?

#### ❌ Erro 403 - Forbidden

Causa: Permissões incorretas

**Correção no FileZilla:**
1. Botão direito no arquivo/pasta
2. Permissões de arquivo
3. Diretórios: 755
4. Arquivos: 644

#### ❌ "Página em branco"

Causa: Erro de PHP não exibido

**Teste:**
Acesse: `https://www.pjf.mg.gov.br/newsletter/diagnostico.php`

Ou crie arquivo `teste.php`:
```php
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
echo "PHP funcionando!<br>";
phpinfo();
?>
```

### 8. Checklist de Upload FTP

Antes de testar, confirme:

- [ ] Pasta `data/` foi criada no servidor
- [ ] Todos os arquivos PHP foram enviados
- [ ] Pastas assets/css/ e assets/js/ estão completas
- [ ] Arquivo `.htaccess` foi enviado (ou removido se causar erro)
- [ ] Permissões 755 nas pastas
- [ ] Permissões 644 nos arquivos
- [ ] `includes/config.php` tem SITE_URL correto
- [ ] Upload foi 100% concluído (sem erros na fila)

### 9. Estrutura Completa para Verificar

Após upload, sua estrutura deve estar assim no servidor:

```
📁 newsletter/
  📄 .htaccess
  📄 index.php
  📄 login.php
  📄 tracker.php
  📄 diagnostico.php          ← Arquivo de teste
  📄 template-exemplo.html
  📄 README.md
  📄 INSTALACAO.md
  📄 API.md
  📄 ESTRUTURA.md
  📄 NOTICIAS.md
  📄 SOLUCAO_ERRO_500.md
  
  📁 api/
    📄 assinantes.php
    📄 campanhas.php
    📄 dashboard.php
    📄 listas.php
    📄 login.php
    📄 logout.php
    📄 noticias.php
    📄 relatorios.php
  
  📁 assets/
    📁 css/
      📄 style.css
    📁 js/
      📄 assinantes.js
      📄 campanhas.js
      📄 dashboard.js
      📄 listas.js
      📄 login.js
      📄 relatorios.js
  
  📁 data/                     ← VAZIA inicialmente
    (arquivos JSON serão criados automaticamente)
  
  📁 includes/
    📄 auth.php
    📄 config.php              ← AJUSTAR SITE_URL!
    📄 db_noticias.php
    📄 header.php
    📄 json_handler.php
    📄 mailer.php
  
  📁 pages/
    📄 assinantes.php
    📄 campanhas.php
    📄 listas.php
    📄 relatorios.php
```

### 10. Configuração do Banco de Notícias

Se o sistema de notícias já existe no servidor, edite `includes/db_noticias.php`:

```php
<?php
// Configuração do banco de dados de notícias
$db_host = 'localhost';           // ou IP do servidor MySQL
$db_user = 'usuario_noticias';    // usuário do banco
$db_pass = 'senha_aqui';          // senha do banco
$db_name = 'noticias_db';         // nome do banco

// Conexão
$conn_noticias = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn_noticias->connect_error) {
    die("Erro na conexão: " . $conn_noticias->connect_error);
}

$conn_noticias->set_charset("utf8");
?>
```

### 11. Primeiro Acesso

Após confirmar que está tudo OK:

1. **Acesse:** `https://www.pjf.mg.gov.br/newsletter/login.php`

2. **Login padrão:**
   ```
   E-mail: admin@example.com
   Senha: password
   ```

3. **IMPORTANTE:** Após primeiro login, vá em "Configurações" e:
   - ✅ Altere a senha do admin
   - ✅ Cadastre seu e-mail real
   - ✅ Delete o usuário padrão

### 12. Limpeza Pós-Instalação

Após confirmar que tudo funciona, DELETE via FTP:

```
❌ diagnostico.php        ← APAGAR por segurança!
❌ teste.php             ← Se você criou
❌ .htaccess.old         ← Se você renomeou
```

### 13. Suporte da Prefeitura

Se precisar falar com TI, informe:

```
Prezados,

Instalei um sistema de newsletter PHP no servidor via FTP.
Localização: /newsletter/

Necessito verificar:
1. PHP 7.4+ está instalado?
2. Extensões ativas: json, session, mysqli, mbstring
3. Diretório 'data' precisa ter permissão de escrita (755)
4. O .htaccess está causando erro 500?

Para diagnóstico, criei o arquivo:
https://www.pjf.mg.gov.br/newsletter/diagnostico.php

Aguardo retorno.
```

### 14. Backup via FTP

**Para fazer backup:**
1. No FileZilla, selecione a pasta `newsletter/`
2. Botão direito → Download
3. Salve em local seguro
4. Faça backup especialmente da pasta `data/` (tem todos os cadastros!)

**Frequência recomendada:**
- Diário: pasta `data/` (pequena, rápido)
- Semanal: sistema completo

---

## 🎯 RESUMO RÁPIDO

1. ✅ Ajustar SITE_URL em `includes/config.php`
2. ✅ Upload via FTP de toda a pasta
3. ✅ Criar pasta `data/` com permissão 755
4. ✅ Acessar `diagnostico.php` para verificar
5. ✅ Testar `login.php` (admin@example.com / password)
6. ✅ Apagar `diagnostico.php` após confirmar

**Dúvidas?** Consulte o arquivo `SOLUCAO_ERRO_500.md`

---

**⚠️ Lembre-se: DELETE o arquivo `diagnostico.php` após a instalação!**
