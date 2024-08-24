# Discord APP

<hr>
Criação de uma Aplicação de Chat em tempo real (discord clone), que permite usuários criar Guilds (servers) Channels (canais de chat) e enviar mensagens. Com um sistema de roles que diferencia "Admin" - que permite gerenciar Guilds, Channels e deletar mensagens de outros usuários - e "Member" - que permite acessar guilds e channels, enviar e deletar mensagens.

<hr>

## Stack
Laravel, MySQL  e Laravel Reverb para implementação dos Websockets.

<hr>

## Como rodar o projeto:
1. Clone o repositório:
```
git clone https://github.com/matheusmrqs4/laravel-disc-app
```

2. Entre no diretório:

```
cd your-repo
```

3. Instale as dependências:
```
composer install

npm run build
```

4. Crie um arquivo .env e preencha os dados:
```
cp .env.example .env
```

5. Gere uma nova chave da aplicação:
```
php artisan key:generate
```

6. Inicie o servidor:
```
php artisan serve
```

7. Inicie o Laravel Reverb:
```
php artisan reverb:start
```
