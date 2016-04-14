## Mmails

[![Total Downloads](https://poser.pugx.org/mixdinternet/mmails/d/total.svg)](https://packagist.org/packages/mixdinternet/mmails)
[![Latest Stable Version](https://poser.pugx.org/mixdinternet/mmails/v/stable.svg)](https://packagist.org/packages/mixdinternet/mmails)
[![License](https://poser.pugx.org/mixdinternet/mmails/license.svg)](https://packagist.org/packages/mixdinternet/mmails)

![Área administrativa](http://mixd.com.br/github/bcc28579938d4ddbeefcff289b456319.png "Área administrativa")

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

## Publicando os arquivos

```
$ php artisan vendor:publish --provider="Mixdinternet\Mmails\Providers\MmailsServiceProvider" --tags="migrations"
```

