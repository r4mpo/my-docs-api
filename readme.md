
***API completa desenvolvida com Laravel 10.35.0.***

A api conta com:

- Autenticação / Registro de usuários *
- CRUD de Tipos de Documentos e documentos *
- Tratamento para armazenamento de arquivos no servidor *

A api conta com aspectos técnicos como:

- FormRequests *
- MVC *
- JWT *
- Interfaces *
- Services *
- Swagger *
- Migrations *
- Relations MySQL *

---

*A api conta com o sistema de autenticação por meio do Json Web Tokens e suas rotas são protegidas pelo middleware do Laravel.*

- Tentando efetuar requisição enquanto não-logado:

<p align="center">
    <img width="400" height="300" src="/public/readme-docs/nao-identificado.gif">
</p>

- Criando um novo usuário, logando na API e efetuando requisição:

<p align="center">
    <img width="400" height="300" src="/public/readme-docs/novo-user.gif">
</p>

---

*Grupo de rotas relacionadas aos endpoints dos Tipos de Documentos*

- Capturando todos tipos de documentos e, também, capturando por um id específico:

<p align="center">
    <img width="400" height="300" src="/public/readme-docs/capturando-tipos-documentos.gif">
</p>

- Criando um novo tipo de documento:

<p align="center">
    <img width="400" height="300" src="/public/readme-docs/novo-tipo-documento.gif">
</p>

- Atualizando um tipo de documento:

<p align="center">
    <img width="400" height="300" src="/public/readme-docs/editando-tipo-documento.gif">
</p>

- Excluindo um tipo de documento:

<p align="center">
    <img width="400" height="300" src="/public/readme-docs/excluindo-tipo-documento.gif">
</p>

---

*Grupo de rotas relacionadas aos endpoints dos de Documentos*

*Lembrando que os documentos só podem ser manipulados por seus respectivos usuários*
