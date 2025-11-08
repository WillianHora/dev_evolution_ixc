# 📦 Projeto de Loja PHP/SQLite (Dockerizado)

Este é um sistema simples de gerenciamento de produtos e vendas, totalmente containerizado com Docker.

A aplicação possui uma vitrine pública (`loja.php`) onde clientes anônimos podem comprar produtos usando um carrinho, e um painel de administração (`adm.php`) onde "vendedores" (usuários) podem cadastrar seus produtos e ver o histórico de vendas.

## 💻 Pilha de Tecnologia

  * **Backend:** PHP 8.2
  * **Servidor:** Apache
  * **Banco de Dados:** SQLite 3
  * **Containerização:** Docker & Docker Compose (Plugin V2)
  * **Dependências:** Composer

## ⚙️ Pré-requisitos

Para executar este projeto, você precisa ter instalados em sua máquina:

1.  **Docker Engine** (O "motor" do Docker)
2.  **Docker Compose (Plugin V2)** (O orquestrador que lê o `docker-compose.yml`)

## 🚀 Como Executar o Projeto

Com o Docker e o Docker Compose instalados, siga os passos abaixo.

1.  **Clone o Repositório**
    (Ou apenas certifique-se de que você está na pasta raiz do projeto, `dev_evolution/`).

2.  **Construa (Build) e Inicie o Contêiner**
    Abra seu terminal na pasta raiz do projeto e execute o seguinte comando:

    ```bash
    # O comando usa "docker compose" (com espaço)
    #
    # -d: Roda o contêiner em modo "detached" (em segundo plano)
    # --build: Força o Docker a construir a imagem do zero (necessário na primeira vez)

    docker compose up -d --build
    ```

3.  **Acesse a Aplicação**
    Após o comando terminar, o contêiner estará rodando. O projeto é mapeado para a porta **8080** do seu computador.

      * **Login (Vendedor):** [http://localhost:8080/index.php](https://www.google.com/search?q=http://localhost:8080/index.php)
      * **Loja (Vitrine Pública):** [http://localhost:8080/loja.php](https://www.google.com/search?q=http://localhost:8080/loja.php)


4. **Rodando fora de container**
   ```bash
    cd dev_evolution_ixc
    cd public
    php -S localhost:8080
    ```
-----

### Comandos Úteis do Docker

  * **Para Iniciar (depois da primeira vez):**

    ```bash
    docker compose up -d
    ```

  * **Para Parar o Contêiner:**

    ```bash
    docker compose down
    ```

  * **Para Reconstruir a Imagem (se você mudar o `Dockerfile`):**

    ```bash
    docker compose up -d --build
    ```

## 📁 Estrutura de Pastas

```
/
├── public/                # O DocumentRoot do Apache. Única pasta visível para o usuário.
│   ├── index.php          # Página de Login
│   ├── loja.php           # Vitrine pública
│   ├── adm.php            # Painel do Vendedor
│   ├── minhas_vendas.php  # Histórico de Vendas
│   ├── carrinho.php       # Página do Carrinho
│   └── ...                # Outros arquivos PHP e CSS
│
├── src/                   # Lógica de backend e arquivos do banco.
│   ├── Database.php       # Classe de conexão com o PDO.
│   ├── database/
│   │   └── sqlite.db      # O arquivo do banco de dados (persistido por um volume).
│   └── ...                # Outros scripts de lógica (registrar_venda.php, etc.)
│
├── vendor/                # Dependências do Composer (ignorado pelo .dockerignore)
│
├── Dockerfile             # A "receita" para construir a imagem (PHP, Apache, SQLite, Composer).
├── docker-compose.yml     # O orquestrador. Define o serviço e o volume do banco.
├── 000-default.conf       # Configuração do Apache (aponta para a pasta /public).
├── .dockerignore          # Impede que 'vendor' local seja copiado para a imagem.
└── composer.json          # Lista de dependências do PHP.
```

### 💾 Persistência do Banco de Dados

O arquivo `docker-compose.yml` cria um **volume nomeado** (`app_db_data`). Isso garante que seu arquivo `sqlite.db` (com todos os produtos, vendas e usuários) seja salvo fora do contêiner.

**Isso significa que você pode parar, remover ou recriar o contêiner (`docker compose down`) sem perder nenhum dado do banco.**




