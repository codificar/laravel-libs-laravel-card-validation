# laravel-card-validation


# Observações
file} | Api retornará os arquivos de tradução do Laravel para serem usados dentro do vue.js |


# Estrutura
 ![alt text](https://i.imgur.com/PsahJHb.jpg)


# Instalação

- Adiciona o projeto no composer.json (direto do gitlab)

```

"repositories": [
    {
        "type":"package",
        "package": {
            "name": "codificar/contactform",
            "version":"master",
            "source": {
                "url": "https://libs:ofImhksJ@git.codificar.com.br/laravel-libs/laravel-generic-lib.git",
                "type": "git",
                "reference":"master"
            }
        }
    }
],

// ...

"require": {
    // ADD this
    "codificar/generic": "dev-master",
},

```

- Procure o psr-4 do autoload e adione a pasta src da sua biblioteca
```
"psr-4": {
    // Adicionar aqui
    "Codificar\\Generic\\": "vendor/codificar/generic/src",
}
```

- Agora, precisamos adicionar o novo Service Provider no arquivo `config/app.php` dentro do array `providers`:

```
'providers' => [
         ...,
            // The new package class
            Codificar\Generic\GenericServiceProvider::class,
        ],
```
- Precisamos copiar os arquivos da pasta public da biblioteca para a pasta public do projeto. Para isso adicione dentro do composer.json, no objeto `"scripts": {`. Repare que especificamos a tag. Nesse caso é public_vuejs_libs. Essa tag é a mesma que fica no arquivo GenericServiceProvider.php da biblioteca. Não tem problema várias bibliotecas utilizarem a mesma tag. Inclusive é bom que todos os componentes utilizem a mesma tag, para não ter que ficar adicionando isso a cada novo projeto que precisar da sua lib.
```
"post-autoload-dump": [
	"@php artisan vendor:publish --tag=public_vuejs_libs --force"
]
```

- Dump o composer autoloader

```
composer dump-autoload -o
```

- Rode as migrations

```
php artisan migrate
```

Por fim, teste se tudo está ok e acesse as rotas de exemplo:

```
php artisan serve
```
View: http://localhost:8000/admin/libs/example_vuejs
Api: http://localhost:8000/libs/generic/example