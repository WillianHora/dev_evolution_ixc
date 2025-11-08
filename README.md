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

Para executar este projeto, você precisará de **uma** das seguintes configurações:

1.  **Para rodar com Docker (Recomendado):**

      * **Docker Engine** (O "motor" do Docker)
      * **Docker Compose (Plugin V2)** (O orquestrador que lê o `docker-compose.yml`)

2.  **Para rodar Localmente (Sem Docker):**

      * **PHP 8.2** (ou superior)
      * **Extensão `php-sqlite3`** (ex: `sudo apt install php8.2-sqlite3`)
      * **Composer**

-----

## 🚀 Como Executar o Projeto

Você pode executar o projeto de três formas diferentes.

### \#\#\# 1. Com Docker Compose (Recomendado)

Este método constrói a imagem a partir dos arquivos do projeto (`Dockerfile`) e usa o `docker-compose.yml` para gerenciar tudo.

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
    O projeto estará rodando na porta **8080** do seu computador.

      * **Loja:** [http://localhost:8080/loja.php](https://www.google.com/search?q=http://localhost:8080/loja.php)
      * **Login:** [http://localhost:8080/index.php](https://www.google.com/search?q=http://localhost:8080/index.php)

-----

### \#\#\# 2. Diretamente do Docker Hub (Pull & Run)

Este método não usa os arquivos do projeto. Ele baixa a imagem pronta (`willianhora/dev_evolution-app`) do Docker Hub e a executa manualmente.

1.  **Puxe (Baixe) a Imagem**
    Este comando baixa a imagem de 510MB do Docker Hub.

    ```bash
    docker pull willianhora/dev_evolution-app
    ```

2.  **Execute (Run) a Imagem**
    Este comando inicia um contêiner a partir da imagem que você baixou.

    ```bash
    docker run -d -p 8080:80 -v app_db_data:/var/www/html/src/database --name meu-projeto-loja willianhora/dev_evolution-app:latest
    ```

> **O que esse comando faz?**
>
>   * `-d`: Roda em modo "detached" (segundo plano).
>   * `-p 8080:80`: Mapeia a porta **8080** do seu PC para a porta **80** do contêiner.
>   * `-v app_db_data:/...`: Conecta o volume `app_db_data` à pasta do banco de dados (para **persistir os dados**).
>   * `--name meu-projeto-loja`: Dá um nome fácil de lembrar ao contêiner.
>   * `willianhora/dev_evolution-app:latest`: A imagem que deve ser usada.

3.  **Acesse a Aplicação**
    O projeto estará rodando em [http://localhost:8080/loja.php](https://www.google.com/search?q=http://localhost:8080/loja.php).

-----

### \#\#\# 3. Sem Docker (Para Desenvolvimento)

Este método usa o servidor embutido do PHP, sem o Apache ou Docker.

1.  **Navegue até o Projeto**

    ```bash
    cd dev_evolution/
    ```

2.  **Instale as Dependências**
    (Se houver alguma definida no `composer.json`)

    ```bash
    composer install
    ```

3.  **Inicie o Servidor**
    Você deve iniciar o servidor de dentro da pasta `public/`, que é a raiz do site.

    ```bash
    cd public
    php -S localhost:8080
    ```

4.  **Acesse a Aplicação**
    O projeto estará rodando em [http://localhost:8080/loja.php](https://www.google.com/search?q=http://localhost:8080/loja.php).

-----

### Comandos Úteis do Docker (Para Métodos 1 e 2)

  * **Para Iniciar (depois da primeira vez - Método 1):**

    ```bash
    docker compose up -d
    ```

  * **Para Parar o Contêiner (Método 1):**
    (Isso para E remove o contêiner, mas os dados no volume `app_db_data` são mantidos)

    ```bash
    docker compose down
    ```

  * **Para Parar o Contêiner (Método 2):**

    ```bash
    docker stop meu-projeto-loja
    docker rm meu-projeto-loja
    ```

  * **Para Reconstruir a Imagem (Método 1):**
    (Se você mudar o `Dockerfile`)

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

Tanto o `docker-compose.yml` (Método 1) quanto o comando `docker run` (Método 2) criam/usam um **volume nomeado** (`app_db_data`). Isso garante que seu arquivo `sqlite.db` (com todos os produtos, vendas e usuários) seja salvo fora do contêiner.

**Isso significa que você pode parar, remover ou recriar o contêiner sem perder nenhum dado do banco.**
