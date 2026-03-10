# Simulador de Propostas

Aplicação web para simulação e cálculo de propostas de consórcio. O sistema recebe os dados do cliente, calcula o valor da proposta com base no percentual pago sobre o bem e na data de encerramento do grupo, registra cada simulação no banco de dados e exibe o resultado formatado em reais.

## Tecnologias

- **PHP 8.3** com Apache
- **MariaDB 12.2**
- **Docker** e **Docker Compose**
- **HTML5 / CSS3 / JavaScript** (com Moment.js para manipulação de datas)

## Estrutura do projeto

```
calcula-proposta/
├── app/
│   ├── config/
│   │   └── schema.sql
│   ├── docker/
│   │   ├── Dockerfile
│   │   ├── docker-compose.yaml
│   │   └── .env.example
│   └── public/
│       ├── index.html
│       ├── cadSimulacao.php
│       └── assets/
            ├── js
            └── css
```

## Configuração e execução

### Pré-requisitos

- Docker
- Docker Compose

### Passos

**1. Clone o repositório**

```bash
git clone https://github.com/mafpbiaggi/calcula-proposta.git
cd calcula-proposta
```

**2. Configure as variáveis de ambiente**

Copie o arquivo de exemplo e preencha os valores:

```bash
cp app/docker/.env.example app/docker/.env
```

Edite o arquivo `app/docker/.env` com os dados do seu ambiente:

```env
MYSQL_ROOT_PASSWORD=sua_senha_root
MYSQL_DATABASE=db_calcula_proposta # Nome do container de banco de dados
MYSQL_USER=usuario
MYSQL_PASSWORD=senha
MYSQL_PORT=3306
ROOT_DIR=/caminho/absoluto/para/o/projeto
PORT_MAPPING=8080
```

**3. Suba os containers**

```bash
cd app/docker
docker compose up -d --build
```

**4. Crie a tabela no banco de dados**

```bash
docker cp app/config/schema.sql db_calcula_proposta:/var/lib/mysql
docker exec -i db_calcula_proposta bash -c 'mariadb -u$MYSQL_USER -p$MYSQL_PASSWORD $MYSQL_DATABASE < /var/lib/mysql/schema.sql'
```

**5. Acesse a aplicação**

Abra o navegador em `http://localhost:8080` (ou a porta definida em `PORT_MAPPING`).

## Dados da Equipe

**Nome**: Marco Aurélio Biaggi ([@mafpbiaggi](https://github.com/mafpbiaggi))  
**E-mail**: mafpbiaggi@gmail.com

## Licença

Este projeto está licenciado sob a [MIT License](LICENSE).
