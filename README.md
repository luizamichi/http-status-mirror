# HTTP Status Mirror

![Versão do PHP](https://img.shields.io/static/v1?label=PHP&message=8.4&color=18181B&labelColor=5354FD)

O **HTTP Status Mirror** é uma aplicação simples em PHP que simula respostas HTTP personalizáveis. \
Ideal para testes de APIs ou validação de clientes HTTP.


## Recursos

- **Status Code Dinâmico**: Retorna um código HTTP baseado na URI (`/200`, `/400`, etc.).
- **Delay Configurável**: Adicione um atraso na resposta usando o parâmetro `timeDelay`.
- **Body Reflexivo**: Caso seja enviado um JSON no body da requisição, ele será devolvido como resposta.
- **Log Detalhado**: Todas as requisições são registradas, incluindo o host, código de status, delay e body.


## Estrutura do Projeto

```
/http-status-mirror
├── .htaccess      # Arquivo de configuração do Apache
├── functions.php  # Arquivo com as funções
├── index.php      # Arquivo principal
├── nginx.conf     # Arquivo de configuração do nginx
├── postman.json   # Collection do Postman para testes
├── README.md      # Este arquivo
└── requests.log   # Arquivo com os logs das requisições
```


## Instalação e Uso

1. Clone este repositório:
``` bash
git clone https://github.com/luizamichi/http-status-mirror.git
cd http-mirror
```

2. Inicie um servidor PHP local:
``` bash
php -S localhost:8080 index.php
```

3. Faça requisições para o servidor:

Retornar status 200:
``` bash
curl -i http://localhost:8080
```

Retornar status 400:
``` bash
curl -i http://localhost:8080/400
```

Configurar delay (em segundos):
``` bash
curl -i "http://localhost:8080/200?timeDelay=2"
```

Enviar body JSON:
``` bash
curl -i -X POST -H "Content-Type: application/json" -d "{\"key\": \"value\"}" http://localhost:8080/200
```


## Logs
As requisições são registradas no arquivo `requests.log`. Exemplo:

``` yaml
[2024-11-21 11:05:30] Host: 192.168.1.100 | Method: GET | Status: 200 | Delay: 0.00s | Uptime: 0.00s | Type: null | Body: null
[2024-11-21 11:05:35] Host: 192.168.1.101 | Method: GET | Status: 400 | Delay: 0.00s | Uptime: 0.00s | Type: null | Body: null
[2024-11-21 11:05:40] Host: 192.168.1.102 | Method: GET | Status: 200 | Delay: 2.00s | Uptime: 0.00s | Type: null | Body: null
[2024-11-21 11:05:45] Host: 192.168.1.103 | Method: POST | Status: 200 | Delay: 0.00s | Uptime: 0.00s | Type: application/json | Body: {"key":"value"}
```
