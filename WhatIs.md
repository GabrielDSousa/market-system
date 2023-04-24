# Apresentação do sistema Market

Olá, meu nome é **Gabriel**, sou um *desenvolvedor Full-stack* e gostaria de apresentar o sistema Market que desenvolvi para um desafio técnico em uma vaga de emprego.

O sistema Market é um aplicativo que apresenta uma **arquitetura** de software seguindo o padrão **MVC**, separando o back-end do front-end. Para o **back-end**, utilizei o **banco de dados Postgres** e a linguagem de programação **PHP 8**, que foi escolhida pelo desafio.

O desenvolvimento do projeto foi dividido em várias etapas. Primeiro, comecei definindo a estrutura do projeto, criando uma **API Restful** para a comunicação entre o back-end e o front-end, utilizando **Vue.js 3 como framework para o front-end**.

Para o **back-end, comecei criando a conexão** com o banco de dados de forma **abstrata** seguindo os princípios **SOLID**, **configurável através de um arquivo** para ser possível conectar qualquer banco de dados, dado as configurações e utilizando **PDO e prepared statements para evitar injeção de SQL**. Em seguida, **criei os modelos**, que representam as entidades do banco de dados, **seguindo o conceito de ORM**, **abstraindo** a classe modelo com **comportamentos iguais** entre as instâncias e criando um **interface** para **obrigar comportamentos** que precisavam ser configuradas em cada instância.

Depois, criei os **controllers**, que recebem as requisições HTTP e fazem a comunicação entre os modelos e as respostas HTTP. Também **abstraindo** a classe, mas dessa vez sem nenhum comportamento padrão, mas foi criada no intuito de manter um padrão e **facilitar a manutenção do código**, para adicionar uma validação de requisição comum aos controllers, por exemplo.

As rotas foram criadas seguindo o padrão de projeto do **Laravel**, onde cada rota é um objeto que contém o método HTTP, a URI, o controller e o método do controller. Adicionei **middlewares para autenticações**, protegendo as páginas administrativas e um **validador de requisições** para validar os dados enviados para a requisição, onde as regras de validação podem ser definidas em cada modelo e são aplicadas automaticamente ao tentar modificar os dados do modelo.

Criei um sistema de **autenticação e autorização usando JWT** para proteger a **API Restful** e as páginas administrativas. O sistema permite login e registro de usuários e a criação de vendas, onde o usuário pode escolher os produtos e a quantidade de cada um. O sistema calcula o valor total da compra e o valor total dos impostos, salvando a venda no banco de dados.

O **front-end** foi desenvolvido utilizando **Vue.js 3**, com o **Vue Router** para redirecionar para as páginas e o **Pinia** para gerenciar o estado da aplicação. O sistema de páginas administrativas permite **ver, cadastrar, alterar e deletar produtos e tipos de produtos**.

Apesar de o front-end ter sido afetado pelo tempo, fiz o melhor possível com a ajuda dos templates de páginas CSS fornecidos pela framework **Tailwind**. Outras ferramentas que diminuíram o tempo de desenvolvimento foram o **Vite.js**, que é um servidor de desenvolvimento rápido, a **IDE PhpStorm** que gera comentários de documentação automáticos, **Postman** para testar as rotas e gerar a **documentação** de cada rota e o **Github Copilot**, que é um assistente de código que ajuda a escrever código e revisá-lo. Além de me basear na organização de projeto do **Laravel**, que é um framework PHP muito popular.

O sistema Market é um **exemplo** de como posso aplicar minhas **habilidades como programador Full-stack**, seguindo as **melhores práticas** de desenvolvimento de software e utilizando as **tecnologias mais recentes**. Agradeço a oportunidade de apresentar este projeto e espero que tenha gostado da demonstração.