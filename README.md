# Descrição

Sistema para simular um fluxo de transações entre usuários.

# Tecnologias utilizadas

- PHP >= 8.1
- Lumen 10
- Docker 

# Como iniciar

1. Executar comando "docker-compose build";

2. Executar comando "docker-compose up -d".

3. Com o ambiente do docker em execução, acessar a pasta app na raiz do projeto

4. rodar o comando "php artisan migrate" para criar as tabelas do projeto

5. Rodar o comando "php artisan db:seed" para popular as tabelas

# Urls

Api - http://localhost:8000/