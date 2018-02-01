# Quake log parser
Ola tudo bem, quero já agradecer pelo o desafio muito legal super divertido.
O desafio foi feito em PHP e NodeJs, fiz nessas duas linguagens para demonstra os meus conhecimento, e que sou capaz de executar os projetos na linguagens solicitada.

----------
## Introdução

O logica executado no PHP é a mesmo feita no NodeJs, nas duas linguagens tentei segui a estrutura MVC.
No PHP foi implementado o framework Slim e o PHPUnit, no NodeJs utilizando Express e não foi implementado o teste unitário no Node, mas como implementei o teste unitário no PHP creio que segui o requisito.
Quis aumentar um pouco da dificuldade e facilitar a utilização do projeto de forma que só baixe e já execute, nas duas linguagens é criado de forma dinâmica, não tem o script de inicialização por que faço isso tudo através das models.
A estilização da pagina utilizei o SASS, note que na estrutura de pasta vai conter:

    ├── node
    ├── PHP
    ├── MER
    ├── sass
    ├── DOC
    ├── README
   No desafio decidir colocar o SASS fora para poder facilitar o meu desenvolvimento sendo que eu iria utilizar duas linguagens com o mesmo template.
O banco de dados utilizado foi o MySQL, como foi solicitado, abaixo irei explicar como decidir criar as tables no banco. 
![enter image description here](https://raw.githubusercontent.com/jhonatasfender/quake_log_parser/master/DOC/img/Screenshot_2018-02-01_15-24-19.png)

Esse modelo foi o que achei mais legível, não se preocupe irei explicar direitinho.
O inicio do modelo e a table game nela vai conter os jogos iniciados e ela tem duas tables que se relacionam com ela kills_by_means e players.
Em kills_by_means foi para poder cumprir com o requisito do [Plus](https://gist.github.com/labmorales/7ebd77411ad51c32179bd4c912096031?short_path=1f8323a#plus) e nessa tabela vai conter todos os tipos de mortes que aconteceram em todas inicializações dos jogos, e a relação dessa tabela com games e de 1:N.
Na tabela players vai conter todos os jogadores por jogo iniciado o relacionamento também é de 1:N.
A próxima tabela vai ser kills, nessa tabela vai conter todos as quantidades que aquele jogador relacionado com a partida iniciada (tabela game) matou durante a partida.
A tabela dados eu criei somente para o meu controle e para ter o visual de quem o jogador matou e lá eu listo a listing que extrair do arquivo.
## Linguagem
Creio que deu para dar uma orientada de como importei o arquivo para o banco, para que esse README não fique muito grande irei colocar os links para que vocês leitores possam ser redirecionados para as devidas paginas

### [PHP](quake_log_parser/DOC/PHP/index.md)
### [NodeJs](https://raw.githubusercontent.com/jhonatasfender/quake_log_parser/master/DOC/NODE/index.md)

Clique nesses dois links que vocês verão como dá o start no projeto.

