# Quake log parser - PHP
### Dando o start no projeto
No php basta acessar o diretório do projeto

    cd PHP/

Depois dar o start com o php serve

    php -S localhost:9999 -t public ./index.php

Irá demorar um pouco por conta da mineração dos dados do arquivo `games.log` 
Para realizar os testes basta executar o seguinte comando

    ./vendor/bin/phpunit 
Caso queira baixar através do git basta executar este comando

    git clone https://github.com/jhonatasfender/quake_log_parser.git 
Depois de ter baixado acesse o diretório e execute o comando para instalar as dependências

	cd quake_log_parser/PHP/	
    composer install

Estou utilizando o micro framework Slim, e a estrutura de pasta ficou dessa forma:

    ├── app
    │   ├── Bootstrap.php
    │   ├── Library
    │   │   ├── Migration.php
    │   │   ├── ReaderLog.php
    │   │   └── Valid.php
    │   ├── log
    │   │   └── games.log
    │   └── Models
    │       ├── Connection.php
    │       ├── Dados.php
    │       ├── Game.php
    │       ├── KillsByMeans.php
    │       ├── Kills.php
    │       └── Players.php
    ├── composer.json
    ├── composer.lock
    ├── CONTRIBUTING.md
    ├── docker-compose.yml
    ├── logs
    │   ├── app.log
    │   └── README.md
    ├── phpunit.xml
    ├── public
    │   ├── index.php
    │   └── stylesheets.css
    │       ├── index.css
    │       └── index.css.map
    ├── README.md
    ├── src
    │   ├── dependencies.php
    │   ├── middleware.php
    │   ├── routes.php
    │   └── settings.php
    ├── templates
    │   ├── index-backup.phtml
    │   └── index.phtml
    ├── tests
         └── Functional
             ├── BaseTestCase.php
             └── HomepageTest.php

<!--stackedit_data:
eyJoaXN0b3J5IjpbMTI2MTg3MjAyOF19
-->