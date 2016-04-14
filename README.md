## Mmails

[![Total Downloads](https://poser.pugx.org/mixdinternet/mmails/d/total.svg)](https://packagist.org/packages/mixdinternet/mmails)
[![Latest Stable Version](https://poser.pugx.org/mixdinternet/mmails/v/stable.svg)](https://packagist.org/packages/mixdinternet/mmails)
[![License](https://poser.pugx.org/mixdinternet/mmails/license.svg)](https://packagist.org/packages/mixdinternet/mmails)

![Área administrativa](http://mixd.com.br/github/1a1944e9d3d545eee7b3929295fabb37.png "Área administrativa")

Agrupa os módulos de configuração do Admix.

## Instalação

Adicione no seu composer.json

```js
  "require": {
    "mixdinternet/mmails": "0.1.*"
  }
```

ou

```js
  composer require mixdinternet/mmails
```

## Service Provider

Abra o arquivo `config/app.php` e adicione

`Mixdinternet\Mmails\Providers\MmailsServiceProvider::class`

## Facade

Abra o arquivo `config/app.php` e adicione

`'Mmail' => Mixdinternet\Mmails\Facade\MmailFacade::class`

## Publicando os arquivos

```
$ php artisan vendor:publish --provider="Mixdinternet\Mmails\Providers\MmailsServiceProvider" --tags="migrations"
```

