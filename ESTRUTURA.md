# 📊 Estrutura Completa do Projeto

```
newsletter-system/
│
├── 📁 api/                           # Endpoints da API REST
│   ├── assinantes.php               # CRUD de assinantes (GET, POST, PUT, DELETE)
│   ├── campanhas.php                # CRUD de campanhas (GET, POST, PUT, DELETE)
│   ├── dashboard.php                # Estatísticas gerais (GET)
│   ├── listas.php                   # CRUD de listas (GET, POST, PUT, DELETE)
│   ├── login.php                    # Autenticação de usuário (POST)
│   ├── logout.php                   # Encerramento de sessão (GET)
│   └── relatorios.php               # Dados de relatórios de campanhas (GET)
│
├── 📁 assets/                        # Recursos estáticos (CSS, JS, imagens)
│   ├── 📁 css/
│   │   └── style.css                # Estilos globais do sistema
│   │
│   └── 📁 js/
│       ├── assinantes.js            # Lógica de CRUD de assinantes
│       ├── campanhas.js             # Lógica de CRUD de campanhas
│       ├── dashboard.js             # Carregamento de estatísticas
│       ├── listas.js                # Lógica de CRUD de listas
│       ├── login.js                 # Validação de login
│       └── relatorios.js            # Exibição de relatórios
│
├── 📁 data/                          # Armazenamento de dados (arquivos JSON)
│   ├── aberturas_log.json           # Log de todas as aberturas de e-mail
│   ├── assinantes.json              # Dados dos assinantes cadastrados
│   ├── campanhas.json               # Campanhas criadas (rascunhos e enviadas)
│   ├── listas.json                  # Listas de segmentação
│   └── usuarios.json                # Usuários administradores do sistema
│
├── 📁 includes/                      # Arquivos PHP de suporte
│   ├── auth.php                     # Funções de autenticação e sessão
│   ├── config.php                   # Configurações gerais do sistema
│   ├── header.php                   # Navbar/cabeçalho das páginas
│   ├── json_handler.php             # Funções de leitura/escrita JSON com flock()
│   └── mailer.php                   # Sistema de envio de e-mails e rastreamento
│
├── 📁 pages/                         # Páginas principais do sistema
│   ├── assinantes.php               # Interface de gerenciamento de assinantes
│   ├── campanhas.php                # Interface de criação/edição de campanhas
│   ├── listas.php                   # Interface de gerenciamento de listas
│   └── relatorios.php               # Interface de visualização de relatórios
│
├── 📄 .htaccess                      # Configurações do Apache (segurança, cache, etc.)
├── 📄 index.php                      # Dashboard principal (página inicial)
├── 📄 login.php                      # Página de login
├── 📄 tracker.php                    # Pixel de rastreamento de abertura de e-mails
│
├── 📄 API.md                         # Documentação completa da API
├── 📄 INSTALACAO.md                  # Guia de instalação rápida
├── 📄 README.md                      # Documentação principal do projeto
├── 📄 ESTRUTURA.md                   # Este arquivo
└── 📄 template-exemplo.html          # Template de exemplo para newsletters
```

---

## 🔄 Fluxo de Dados

### 1. Autenticação
```
login.php (Frontend)
    ↓ (POST)
api/login.php
    ↓ (verifica credenciais)
data/usuarios.json
    ↓ (cria sessão PHP)
index.php (Dashboard)
```

### 2. Gerenciamento de Assinantes
```
pages/assinantes.php (Interface)
    ↓ (AJAX: GET, POST, PUT, DELETE)
api/assinantes.php
    ↓ (usa json_handler.php com flock())
data/assinantes.json
```

### 3. Criação de Campanha
```
pages/campanhas.php (Formulário)
    ↓ (POST com enviar=true)
api/campanhas.php
    ↓ (chama includes/mailer.php)
enviarCampanha()
    ↓ (busca assinantes das listas)
data/assinantes.json
    ↓ (para cada assinante)
enviarEmailComRastreamento()
    ↓ (adiciona pixel: tracker.php?cid=X&sid=Y)
    ↓ (envia via mail())
📧 E-mail do Assinante
```

### 4. Rastreamento de Abertura
```
Assinante abre o e-mail
    ↓ (cliente de e-mail carrega imagem)
tracker.php?cid=campanha_xyz&sid=123
    ↓ (chama includes/mailer.php)
registrarAbertura()
    ↓ (usa json_handler.php com flock())
data/aberturas_log.json
    ↓ (adiciona registro)
[{
  "campanha_id": "campanha_xyz",
  "assinante_id": 123,
  "data_abertura": "2025-10-22 11:05:30",
  "ip": "192.168.1.100",
  "user_agent": "Mozilla/5.0..."
}]
    ↓ (retorna)
🖼️ GIF transparente de 1x1 pixel
```

### 5. Visualização de Relatórios
```
pages/relatorios.php?id=campanha_xyz
    ↓ (GET)
api/relatorios.php?campanha_id=campanha_xyz
    ↓ (lê dados)
data/campanhas.json (busca campanha)
data/assinantes.json (busca destinatários)
data/aberturas_log.json (filtra aberturas)
    ↓ (processa e agrupa)
{
  "total_envios": 10,
  "total_aberturas": 15,
  "aberturas_unicas": 8,
  "taxa_abertura": 80.0,
  "aberturas_detalhadas": [...]
}
    ↓ (exibe)
📊 Tabela com nome, e-mail, data e quantidade de aberturas
```

---

## 🔐 Sistema de Segurança

### Proteção de Arquivos JSON
```
.htaccess
├── Bloqueia acesso direto a *.json
├── Nega listagem de diretórios
└── Protege diretório includes/

└─> Acesso apenas via PHP com autenticação
```

### Controle de Sessão
```
includes/config.php
├── Inicia sessão PHP
└── Define nome de sessão personalizado

includes/auth.php
├── estaLogado() - Verifica autenticação
├── requererLogin() - Força login em páginas protegidas
└── fazerLogout() - Encerra sessão

Todas as páginas protegidas chamam:
requererLogin();
```

### File Locking (flock)
```
includes/json_handler.php

lerJSON($arquivo)
├── fopen($arquivo, 'r')
├── flock($file, LOCK_SH)  ← Trava compartilhada
├── fread()
├── flock($file, LOCK_UN)  ← Libera trava
└── fclose()

escreverJSON($arquivo, $dados)
├── fopen($arquivo, 'w')
├── flock($file, LOCK_EX)  ← Trava exclusiva
├── fwrite()
├── flock($file, LOCK_UN)  ← Libera trava
└── fclose()
```

---

## 📦 Estrutura de Dados JSON

### data/usuarios.json
```json
[
  {
    "id": 1,
    "nome": "Admin",
    "email": "admin@example.com",
    "senha": "$2y$10$..." // password_hash()
  }
]
```

### data/listas.json
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

### data/assinantes.json
```json
[
  {
    "id": 1,
    "nome": "João Silva",
    "email": "joao@email.com",
    "listas": [1, 2], // IDs das listas
    "data_cadastro": "2025-10-22 10:30:00"
  }
]
```

### data/campanhas.json
```json
[
  {
    "id": "camp_abc123_xyz789",
    "assunto": "Newsletter Semanal",
    "conteudo_html": "<html>...</html>",
    "listas": [1, 2],
    "status": "enviada", // ou "rascunho"
    "data_criacao": "2025-10-22 10:00:00",
    "data_envio": "2025-10-22 11:00:00"
  }
]
```

### data/aberturas_log.json
```json
[
  {
    "campanha_id": "camp_abc123_xyz789",
    "assinante_id": 1,
    "data_abertura": "2025-10-22 11:05:30",
    "ip": "192.168.1.100",
    "user_agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64)..."
  }
]
```

---

## 🎯 Pontos de Entrada

### Para Usuários
```
http://seusite.com/newsletter-system/
├── login.php                 # Ponto de entrada inicial
├── index.php                 # Dashboard (requer login)
├── pages/assinantes.php      # Gerenciar assinantes
├── pages/listas.php          # Gerenciar listas
├── pages/campanhas.php       # Criar/enviar campanhas
└── pages/relatorios.php      # Ver estatísticas
```

### Para API/Integração
```
http://seusite.com/newsletter-system/api/
├── login.php                 # POST: Autenticar
├── assinantes.php            # GET/POST/PUT/DELETE
├── listas.php                # GET/POST/PUT/DELETE
├── campanhas.php             # GET/POST/PUT/DELETE
├── dashboard.php             # GET: Estatísticas gerais
└── relatorios.php            # GET: Dados de campanha
```

### Para Rastreamento
```
http://seusite.com/newsletter-system/tracker.php?cid=X&sid=Y
└── Chamado automaticamente pelos clientes de e-mail
```

---

## 🚀 Ordem de Desenvolvimento

Se você estiver criando algo similar, siga esta ordem:

1. **Configuração e Fundação**
   - `includes/config.php`
   - `includes/json_handler.php` (com flock!)
   - `data/*.json` (arquivos iniciais)

2. **Autenticação**
   - `includes/auth.php`
   - `login.php`
   - `api/login.php`

3. **CRUD Básico**
   - `api/listas.php` + `pages/listas.php`
   - `api/assinantes.php` + `pages/assinantes.php`

4. **Sistema de E-mail**
   - `includes/mailer.php`
   - `api/campanhas.php` + `pages/campanhas.php`

5. **Rastreamento**
   - `tracker.php`
   - Modificar `includes/mailer.php` para adicionar pixel

6. **Relatórios**
   - `api/relatorios.php`
   - `pages/relatorios.php`

7. **Dashboard**
   - `api/dashboard.php`
   - `index.php`

8. **Frontend**
   - `assets/css/style.css`
   - `assets/js/*.js`

---

## 📊 Métricas e Estatísticas

### Dashboard Principal
```
Total de Assinantes
├── COUNT(assinantes.json)

Total de Listas
├── COUNT(listas.json)

Campanhas Enviadas
├── COUNT(campanhas.json WHERE status='enviada')

Taxa de Abertura Média
├── Para cada campanha enviada:
│   ├── Destinatários = assinantes nas listas da campanha
│   ├── Aberturas únicas = COUNT(DISTINCT assinante_id em aberturas_log)
│   └── Taxa = (Aberturas únicas / Destinatários) * 100
└── Média de todas as taxas
```

### Relatório de Campanha
```
Total de Envios
├── COUNT(assinantes WHERE listas IN campanha.listas)

Total de Aberturas
├── COUNT(aberturas_log WHERE campanha_id = X)

Aberturas Únicas
├── COUNT(DISTINCT assinante_id em aberturas_log WHERE campanha_id = X)

Taxa de Abertura
├── (Aberturas Únicas / Total de Envios) * 100

Lista Detalhada
├── Para cada abertura única:
│   ├── Nome do assinante
│   ├── E-mail do assinante
│   ├── Data da primeira abertura
│   └── Total de vezes que abriu
```

---

## 🔧 Funções Principais

### includes/json_handler.php
```
lerJSON($arquivo)              → array
escreverJSON($arquivo, $dados) → bool
gerarNovoID($dados)            → int
gerarIDUnico($prefixo)         → string
buscarPorID($dados, $id)       → array|null
removerPorID($dados, $id)      → array
atualizarPorID($dados, $id)    → array
```

### includes/auth.php
```
estaLogado()                   → bool
fazerLogin($email, $senha)     → bool|string
fazerLogout()                  → void
requererLogin()                → void (redireciona se não logado)
criarUsuario($nome, $email)    → bool|string
```

### includes/mailer.php
```
enviarCampanha($campanhaId)                          → array
enviarEmailComRastreamento($email, $nome, ...)       → bool
registrarAbertura($campanhaId, $assinanteId)         → bool
```

---

**Este arquivo documenta toda a arquitetura do sistema.**  
**Use como referência para entender o projeto completo.**
