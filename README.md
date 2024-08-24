<p align="center"><img width="350" src="public/images/full-logo.png" alt="Logótipo KLL"></p>

## ⚡️ O que é o KLL?

Uma rede social desenvolvida para a web, adaptada para qualquer dispositivo. Baseada nas primeiras versões do Reddit, onde as pessoas partilham as suas ideias apenas com texto, sem elementos visuais como imagens ou vídeos, e os utilizadores podem votar positivamente ou negativamente nos posts.

KLL é uma aplicação desenvolvida em ambiente Web com Laravel. No quesito de preservar os dados foi realizado com base de dados MySQL.

> Esta não é um aplicação oficializada no mercado, foi construida no âmbito académico de forma aumentar as nossas capacidades técnicas e interpessoais.

## 💡 Pré-Requisitos

Para comerçar a usar o software localmente na sua máquina, basta instalar o [XAMPP](https://www.apachefriends.org/download.html) e [Composer](https://getcomposer.org/download).

## ⚙️ Instalação

Para colocar o projeto a funcionar localmente na sua máquina basta:

1. Clonar o repositório.

2. Abrir o XAMPP e iniciar o Apache e o MySQL.

3. Abrir o phpMyAdmin e criar uma base de dados com o nome "kll":

    ```sh
    http://localhost/phpmyadmin
    ```

4. Na raiz do projeto, fazer uma cópia do ficheiro ".env.example" e renomeá-lo para ".env".

5. Abrir a linha de comandos.

6. Ir para a pasta do projeto:

    ```sh
    cd "pasta_do_projeto"
    ```

7. Instalar todos os pacotes e dependências necessárias do projeto (apenas necessário uma vez quando se faz clone do repositório):

    ```sh
    composer install
    ```

8. Gerar a chave para aceder à aplicação:

    ```sh
    php artisan key:generate
    ```

9. Criar tabelas da base de dados:

    ```sh
    php artisan migrate
    ```

10. Inserir dados fictícios na base de dados:

    ```sh
    php artisan db:seed --class=DatabaseSeeder
    ```

11. Iniciar o servidor:

    ```sh
    php artisan serve
    ```

12. Abrir a aplicação no browser a funcionar:

    ```sh
    http://localhost:8000
    ```

## 📖 API

É graças à API que o software se comunica com o servidor, possibilitando a interação com a base de dados e o armazenamento de dados.

Aqui você encontrará a lista de todos as funcionalidades disponíveis para efetuar essa comunicação.

| Rota                                  | Método | Descrição                                                                             |
| :------------------------------------ | :----- | :------------------------------------------------------------------------------------ |
| **PÁGINA INICIAL**                    |
| /                                     | GET    | Ir para a página inicial.                                                             |
| /search/{searchText}                  | GET    | Pesquisar posts pelo título na página inicial.                                        |
| **AUTENTICAÇÃO**                      |
| /auth                                 | GET    | Ir para a página de autenticação.                                                     |
| /auth/login                           | POST   | Iniciar sessão de um utilizador.                                                      |
| /auth/signup                          | POST   | Registar um utilizador.                                                               |
| /auth/logout                          | GET    | Terminar sessão de um utilizador.                                                     |
| **UTILIZADORES**                      |
| /profile/{userId}                     | GET    | Ir para a página do perfil do utilizador.                                             |
| /account/{userId}                     | GET    | Ir para a página de definições da conta.                                              |
| /account/edit-data/{userId}           | PATCH  | Atualizar os dados básicos do utilizador com login.                                   |
| /account/edit-password/{userId}       | PATCH  | Atualizar a palavra-passe do utilizador com login.                                    |
| /account/delete/{userId}              | DELETE | Remover o utilizador com login.                                                       |
| **POSTS**                             |
| /posts/{postId}                       | GET    | Ir para a página de um post específico.                                               |
| /posts/create                         | POST   | Criar um novo post.                                                                   |
| /posts/edit/{postId}                  | PATCH  | Atualizar um post.                                                                    |
| /posts/vote/{postId}                  | PATCH  | Atualizar um voto de um post.                                                         |
| /posts/delete/{postId}                | DELETE | Remover um post.                                                                      |
| **COMENTÁRIOS**                       |
| /comments/create/{postId}             | POST   | Criar um novo comentário.                                                             |
| /comments/edit/{commentId}            | PATCH  | Atualizar um comentário.                                                              |
| /comments/vote/{commentId}            | PATCH  | Atualizar um voto de um comentário.                                                   |
| /comments/delete/{commentId}/{postId} | DELETE | Remover um comentário.                                                                |
| **ERRO FATAL**                        |
| /500                                  | GET    | Ir para esta página quando existe uma erro que impede o funcionamento da aplicação.   |
| **PÁGINA NÃO ENCONTRADA**             |
| /404                                  | GET    | Mostrar esta página quando o utilizador tenta aceder uma rota que não foi encontrada. |

## 👍 Contribuições

As contribuições são o que tornam a comunidade de código aberto um lugar incrível para aprender, inspirar e criar. Quaisquer contribuições que você faça são muito apreciadas.

Se você tiver uma sugestão de melhoria, por favor, faça fork do repositório e crie uma pull request. Ou pode simplesmente abrir um issue. Não se esqueça de dar uma estrela ao projeto! Obrigado mais uma vez!

## ⭐️ Colaboradores

-   Francisca Costa
-   Lara Ribeiro
-   Luís Pereira

## ⚠️ Licença

Ao contribuir para este projeto, você concorda com as políticas da licença [MIT](LICENSE).
