# Cesauto Center Car

Site institucional da **Cesauto Center Car** — oficina mecânica em Fortaleza/CE.

Objetivo: autoridade local (SEO) e captura de leads via formulário seguro + WhatsApp.

**Repositório:** [github.com/felipesalvim/cesauto-center-car](https://github.com/felipesalvim/cesauto-center-car)

---

## Stack

| Camada | Tecnologia |
|---|---|
| Backend | PHP 8.1+ (PDO) |
| Banco | MySQL 5.7+ / MariaDB |
| Frontend | HTML5, CSS3, Vanilla JS |
| Hospedagem alvo | Apache / XAMPP / cPanel compartilhado |

Sem frameworks de frontend ou Laravel/Symfony.

---

## Requisitos

- PHP 8.1+ (extensões: `pdo_mysql`, `mbstring`, `session`)
- MySQL 5.7+ ou MariaDB
- Apache com `mod_rewrite` (opcional)
- XAMPP (desenvolvimento local)

---

## Estrutura do projeto

```text
bk-autos/
├── assets/                 # CSS, JS e imagens
├── config/
│   ├── .env.example        # Modelo de variáveis (versionado)
│   ├── .env                # Credenciais locais (NÃO versionar)
│   ├── business.php        # Dados da empresa (endereço, horários, reviews)
│   └── config.php
├── public/                 # Document root (apontar o Apache aqui)
│   ├── index.php
│   ├── enviar-lead.php
│   └── politica-privacidade.php
├── sql/
│   ├── create_leads.sql
│   └── alter_leads_v2.sql
└── src/
    ├── bootstrap.php
    ├── controllers/
    ├── helpers/
    ├── models/
    └── views/partials/
```

---

## Setup local (XAMPP)

### 1. Colocar o projeto

Clone ou copie para a pasta do Apache, por exemplo:

```text
C:\xampp2\htdocs\bk-autos
```

### 2. Banco de dados

1. Inicie **Apache** e **MySQL** no XAMPP.
2. Abra o phpMyAdmin.
3. Importe `sql/create_leads.sql`.
4. Se a tabela `leads` já existia em versão antiga, rode também `sql/alter_leads_v2.sql`.

### 3. Variáveis de ambiente

```powershell
copy config\.env.example config\.env
```

Edite `config/.env`:

```env
APP_ENV=local
APP_URL=http://localhost/bk-autos/public

DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=bk_autos
DB_USER=root
DB_PASS=

RATE_LIMIT_MAX=5
RATE_LIMIT_WINDOW=60

WHATSAPP_NUMBER=5585986191000
```

### 4. Acessar

```text
http://localhost/bk-autos/public/
```

Política de privacidade:

```text
http://localhost/bk-autos/public/politica-privacidade.php
```

---

## Dados da empresa

Dados oficiais (nome, endereço, telefone, horários, nota Google e avaliações) ficam em:

```text
config/business.php
```

Altere esse arquivo para atualizar o site sem espalhar mudanças em várias views.

**Dados atuais (resumo):**

| Campo | Valor |
|---|---|
| Nome | Cesauto Center Car |
| Endereço | R. Nogueira Acioli, 981 - Centro, Fortaleza - CE, 60110-140 |
| Telefone / WhatsApp | (85) 98619-1000 |
| Nota Google | 4,25 |
| Horário | Seg–sex 07:30–17:00 · Sáb 07:30–12:00 · Dom fechado |

---

## Logo

Coloque o arquivo em `assets/img/` com um destes nomes:

- `logo.webp` (preferencial)
- `logo.png`
- `logo.svg`
- `logo.jpg`

O header e o footer detectam automaticamente. Enquanto não houver logo, aparece o placeholder “CC”.

---

## Formulário de leads

Endpoint: `public/enviar-lead.php`

Recursos de segurança:

- CSRF token
- Validação server-side
- Consentimento LGPD
- Rate limit (padrão: 5 req/min por IP)
- PDO com prepared statements
- Credenciais fora de `/public`

Campos:

- Nome (obrigatório)
- WhatsApp (obrigatório)
- Serviço de interesse (obrigatório)
- Modelo do carro (opcional)
- E-mail (opcional)

---

## Páginas

| URL | Descrição |
|---|---|
| `public/index.php` | Landing institucional (PAS + serviços + contato) |
| `public/politica-privacidade.php` | Política LGPD completa |
| `public/enviar-lead.php` | POST do formulário |

---

## Preview para cliente

### Temporário (ngrok)

1. XAMPP (Apache + MySQL) ligado  
2. `ngrok http 80`  
3. Enviar: `https://SEU-SUBDOMINIO.ngrok-free.app/bk-autos/public/`

### Produção

Hospedagem PHP/MySQL (cPanel, InfinityFree, etc.):

1. Enviar arquivos  
2. Importar SQL  
3. Configurar `config/.env` com `APP_URL` e credenciais do banco  
4. Document root apontando para `public/` (quando possível)

> GitHub Pages **não** hospeda este projeto (é PHP/MySQL, não site estático).

---

## Segurança

- Nunca versionar `config/.env`
- Manter `config/`, `src/` e `sql/` fora do document root em produção, se a hospedagem permitir
- Revisar permissões de pastas no servidor
- Trocar senha do MySQL em produção

---

## Desenvolvimento

```powershell
git clone https://github.com/felipesalvim/cesauto-center-car.git
cd cesauto-center-car
copy config\.env.example config\.env
```

Branch principal: `master`

---

## Licença / uso

Projeto sob medida para a Cesauto Center Car. Uso e distribuição conforme acordo com o cliente.
