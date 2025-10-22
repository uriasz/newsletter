# 📧 Sistema de Newsletter com Armazenamento JSON

Sistema completo de gerenciamento e envio de newsletters, utilizando arquivos JSON como banco de dados. O sistema possui rastreamento de abertura de e-mails, gestão de assinantes, listas de segmentação e relatórios detalhados.

## ⚠️ AVISOS IMPORTANTES

### Limitações e Considerações

**Este sistema utiliza arquivos JSON para armazenamento de dados**, o que apresenta as seguintes limitações:

1. **Desempenho**: À medida que os arquivos crescem, as operações de leitura/escrita ficam mais lentas
2. **Concorrência**: Mesmo com `flock()`, múltiplos acessos simultâneos podem causar lentidão
3. **Escalabilidade**: Não recomendado para grandes volumes (>1000 assinantes ou >100 campanhas)
4. **Uso Recomendado**: Protótipos, projetos pessoais ou aplicações com baixo volume

**Para produção ou sistemas maiores**, considere migrar para:
- SQLite (banco baseado em arquivo, mas muito mais robusto)
- MySQL/PostgreSQL (bancos relacionais completos)
- MongoDB (NoSQL para maior flexibilidade)

## 🚀 Funcionalidades

- ✅ Sistema de autenticação com sessões PHP
- ✅ CRUD completo de assinantes
- ✅ CRUD completo de listas de segmentação
- ✅ Criação e edição de campanhas de e-mail
- ✅ Envio de e-mails com HTML personalizado
- ✅ **Rastreamento de abertura de e-mails** via pixel invisível
- ✅ Relatórios detalhados mostrando quem abriu cada campanha
- ✅ Dashboard com estatísticas gerais
- ✅ Interface responsiva e moderna

## 📁 Estrutura do Projeto

```
newsletter-system/
├── api/                        # Endpoints da API
│   ├── assinantes.php         # CRUD de assinantes
│   ├── campanhas.php          # CRUD de campanhas
│   ├── dashboard.php          # Estatísticas gerais
│   ├── listas.php             # CRUD de listas
│   ├── login.php              # Autenticação
│   ├── logout.php             # Encerrar sessão
│   └── relatorios.php         # Dados de relatórios
├── assets/
│   ├── css/
│   │   └── style.css          # Estilos globais
│   └── js/
│       ├── assinantes.js      # Lógica da página de assinantes
│       ├── campanhas.js       # Lógica da página de campanhas
│       ├── dashboard.js       # Lógica do dashboard
│       ├── listas.js          # Lógica da página de listas
│       ├── login.js           # Lógica de login
│       └── relatorios.js      # Lógica de relatórios
├── data/                       # Arquivos JSON (banco de dados)
│   ├── aberturas_log.json     # Log de aberturas de e-mail
│   ├── assinantes.json        # Dados dos assinantes
│   ├── campanhas.json         # Campanhas criadas
│   ├── listas.json            # Listas de segmentação
│   └── usuarios.json          # Usuários administradores
├── includes/                   # Arquivos PHP auxiliares
│   ├── auth.php               # Funções de autenticação
│   ├── config.php             # Configurações gerais
│   ├── header.php             # Navbar do sistema
│   ├── json_handler.php       # Funções de leitura/escrita JSON
│   └── mailer.php             # Sistema de envio de e-mails
├── pages/                      # Páginas do sistema
│   ├── assinantes.php         # Gerenciar assinantes
│   ├── campanhas.php          # Gerenciar campanhas
│   ├── listas.php             # Gerenciar listas
│   └── relatorios.php         # Ver relatórios
├── index.php                   # Dashboard principal
├── login.php                   # Página de login
├── tracker.php                 # Pixel de rastreamento
└── README.md                   # Este arquivo
```

## 🔧 Requisitos

- PHP 7.4 ou superior
- Servidor web (Apache, Nginx ou similar)
- Função `mail()` do PHP configurada no servidor
  - **OU** SMTP configurado (requer modificação do código)

## 📦 Instalação

### 1. Configuração do Servidor Web

**Para Apache com XAMPP/WAMP:**
```
1. Copie a pasta 'newsletter-system' para o diretório htdocs/
2. Acesse: http://localhost/newsletter-system/
```

**Para PHP Built-in Server (desenvolvimento):**
```bash
cd newsletter-system
php -S localhost:8000
```

### 2. Ajustar Configurações

Edite o arquivo `includes/config.php`:

```php
// Altere a URL base do sistema
define('SITE_URL', 'http://localhost/newsletter-system');

// Configure o e-mail de envio
define('MAIL_FROM', 'seu-email@dominio.com');
define('MAIL_FROM_NAME', 'Nome da sua Newsletter');
```

### 3. Configurar Permissões

O diretório `data/` precisa ter permissões de escrita:

**Linux/Mac:**
```bash
chmod -R 775 data/
```

**Windows:** Geralmente não é necessário, mas verifique se o servidor web tem permissão de escrita.

### 4. Credenciais Padrão

- **E-mail:** admin@example.com
- **Senha:** password

**⚠️ IMPORTANTE:** Após o primeiro acesso, altere a senha no arquivo `data/usuarios.json` usando `password_hash()`.

## 📚 Como Usar

### 1. Login
Acesse a página inicial e faça login com as credenciais padrão.

### 2. Criar Listas de Segmentação
1. Acesse **Listas** no menu
2. Clique em **+ Adicionar Lista**
3. Defina um nome (ex: "Clientes", "Blog", "Promoções")

### 3. Adicionar Assinantes
1. Acesse **Assinantes** no menu
2. Clique em **+ Adicionar Assinante**
3. Preencha nome, e-mail e selecione as listas
4. Salve

### 4. Criar uma Campanha
1. Acesse **Campanhas** no menu
2. Clique em **+ Nova Campanha**
3. Preencha:
   - **Assunto** do e-mail
   - Selecione as **listas** que receberão
   - **Conteúdo HTML** do e-mail
4. Escolha:
   - **Salvar Rascunho**: Salva sem enviar
   - **Enviar Agora**: Envia imediatamente para todos os assinantes

### 5. Ver Relatórios
1. Acesse **Relatórios** no menu
2. Selecione uma campanha enviada
3. Visualize:
   - Total de envios
   - Total de aberturas
   - Taxa de abertura
   - Lista de quem abriu (nome, e-mail, data)

## 🔍 Como Funciona o Rastreamento

### Pixel de Rastreamento

Ao enviar um e-mail, o sistema automaticamente adiciona uma **imagem invisível de 1x1 pixel** no final do HTML:

```html
<img src="http://seusite.com/tracker.php?cid=campanha_xyz&sid=123" width="1" height="1" />
```

**Parâmetros:**
- `cid`: ID da campanha
- `sid`: ID do assinante

### Fluxo de Rastreamento

1. Assinante abre o e-mail
2. Cliente de e-mail carrega a imagem
3. `tracker.php` é chamado
4. Script registra a abertura no arquivo `aberturas_log.json` usando `flock()`
5. Retorna uma imagem GIF transparente de 1x1

### Arquivo tracker.php

```php
<?php
require_once __DIR__ . '/includes/mailer.php';

$campanhaId = $_GET['cid'] ?? '';
$assinanteId = $_GET['sid'] ?? 0;

if (!empty($campanhaId) && !empty($assinanteId)) {
    registrarAbertura($campanhaId, $assinanteId); // Usa flock() internamente
}

// Retorna GIF transparente
header('Content-Type: image/gif');
echo base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
exit;
```

## 🔒 Segurança com flock()

Todas as operações de escrita em JSON utilizam **file locking** para prevenir corrupção:

```php
function escreverJSON($arquivo, $dados) {
    $file = fopen($arquivo, 'w');
    
    if (flock($file, LOCK_EX)) {  // Trava exclusiva
        $json = json_encode($dados, JSON_PRETTY_PRINT);
        fwrite($file, $json);
        flock($file, LOCK_UN);     // Libera trava
        fclose($file);
        return true;
    }
    
    fclose($file);
    return false;
}
```

**Tipos de trava:**
- `LOCK_SH`: Trava compartilhada (leitura)
- `LOCK_EX`: Trava exclusiva (escrita)
- `LOCK_UN`: Libera trava

## 🎨 Personalização de E-mails

### Placeholder Disponível

Use `[NOME]` no conteúdo HTML para inserir o nome do assinante:

```html
<h1>Olá, [NOME]!</h1>
<p>Confira nossas novidades...</p>
```

### Exemplo de Template HTML

```html
<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { max-width: 600px; margin: 0 auto; }
        .header { background: #4a90e2; color: white; padding: 20px; }
        .content { padding: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Newsletter Semanal</h1>
        </div>
        <div class="content">
            <h2>Olá, [NOME]!</h2>
            <p>Confira as novidades desta semana...</p>
        </div>
    </div>
</body>
</html>
```

## 🐛 Solução de Problemas

### E-mails não estão sendo enviados

**Causa:** Função `mail()` não configurada no servidor.

**Solução:**
1. **Desenvolvimento local:** Use ferramentas como [MailHog](https://github.com/mailhog/MailHog) ou [Mailtrap](https://mailtrap.io/)
2. **Produção:** Configure SMTP usando PHPMailer:

```bash
composer require phpmailer/phpmailer
```

Modifique `includes/mailer.php` para usar SMTP.

### Rastreamento não funciona

**Causas possíveis:**
1. Cliente de e-mail bloqueia imagens (Gmail, Outlook)
2. URL do `SITE_URL` incorreta no `config.php`
3. Arquivo `tracker.php` não acessível

**Solução:**
- Verifique se `SITE_URL` está correto
- Teste acessando diretamente: `http://seusite.com/tracker.php?cid=teste&sid=1`

### Erro de permissões

**Erro:** "Permission denied" ao salvar dados

**Solução:**
```bash
chmod -R 775 data/
chown -R www-data:www-data data/  # Linux
```

### Dados corrompidos no JSON

**Causa:** Escrita simultânea sem `flock()` (não deveria acontecer neste projeto)

**Solução:**
1. Backup dos arquivos em `data/`
2. Corrija manualmente o JSON inválido
3. Verifique se todas as funções usam `escreverJSON()`

## 📊 Estrutura dos Dados JSON

### usuarios.json
```json
[
  {
    "id": 1,
    "nome": "Admin",
    "email": "admin@example.com",
    "senha": "$2y$10$..."
  }
]
```

### assinantes.json
```json
[
  {
    "id": 1,
    "nome": "João Silva",
    "email": "joao@email.com",
    "listas": [1, 2],
    "data_cadastro": "2025-10-22 10:30:00"
  }
]
```

### listas.json
```json
[
  {
    "id": 1,
    "nome": "Clientes",
    "descricao": "Lista de clientes ativos",
    "data_criacao": "2025-10-22 09:00:00"
  }
]
```

### campanhas.json
```json
[
  {
    "id": "camp_abc123",
    "assunto": "Newsletter Semanal",
    "conteudo_html": "<html>...</html>",
    "listas": [1, 2],
    "status": "enviada",
    "data_criacao": "2025-10-22 10:00:00",
    "data_envio": "2025-10-22 11:00:00"
  }
]
```

### aberturas_log.json
```json
[
  {
    "campanha_id": "camp_abc123",
    "assinante_id": 1,
    "data_abertura": "2025-10-22 11:05:30",
    "ip": "192.168.1.100",
    "user_agent": "Mozilla/5.0..."
  }
]
```

## 🔄 Migração para Banco de Dados

Se o projeto crescer, migre para SQL:

### Estrutura sugerida (MySQL)

```sql
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    senha VARCHAR(255)
);

CREATE TABLE listas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100),
    descricao TEXT,
    data_criacao DATETIME
);

CREATE TABLE assinantes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    data_cadastro DATETIME
);

CREATE TABLE assinante_lista (
    assinante_id INT,
    lista_id INT,
    FOREIGN KEY (assinante_id) REFERENCES assinantes(id),
    FOREIGN KEY (lista_id) REFERENCES listas(id),
    PRIMARY KEY (assinante_id, lista_id)
);

CREATE TABLE campanhas (
    id VARCHAR(50) PRIMARY KEY,
    assunto VARCHAR(200),
    conteudo_html TEXT,
    status ENUM('rascunho', 'enviada'),
    data_criacao DATETIME,
    data_envio DATETIME
);

CREATE TABLE campanha_lista (
    campanha_id VARCHAR(50),
    lista_id INT,
    FOREIGN KEY (campanha_id) REFERENCES campanhas(id),
    FOREIGN KEY (lista_id) REFERENCES listas(id)
);

CREATE TABLE aberturas_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    campanha_id VARCHAR(50),
    assinante_id INT,
    data_abertura DATETIME,
    ip VARCHAR(45),
    user_agent TEXT,
    FOREIGN KEY (campanha_id) REFERENCES campanhas(id),
    FOREIGN KEY (assinante_id) REFERENCES assinantes(id),
    INDEX idx_campanha (campanha_id),
    INDEX idx_assinante (assinante_id)
);
```

## 📝 Licença

Este projeto é fornecido "como está", sem garantias. Sinta-se livre para usar, modificar e distribuir.

## 🤝 Contribuição

Sugestões de melhorias:
1. Implementar sistema de templates de e-mail
2. Adicionar editor WYSIWYG para criação de campanhas
3. Suporte a anexos em e-mails
4. Agendamento de campanhas
5. API RESTful completa
6. Autenticação de dois fatores
7. Log de atividades do sistema
8. Exportação de relatórios em PDF/CSV

## 📧 Suporte

Para dúvidas ou problemas, verifique:
1. Todas as configurações no `config.php`
2. Permissões do diretório `data/`
3. Logs de erro do PHP (`error_log`)
4. Console do navegador (F12) para erros JavaScript

---

**Desenvolvido com PHP, HTML, CSS e JavaScript**

**Versão:** 1.0.0
**Data:** Outubro 2025
