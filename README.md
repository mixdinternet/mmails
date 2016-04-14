## Mconfig

[![Total Downloads](https://poser.pugx.org/mixdinternet/mconfig/d/total.svg)](https://packagist.org/packages/mixdinternet/mconfig)
[![Latest Stable Version](https://poser.pugx.org/mixdinternet/mconfig/v/stable.svg)](https://packagist.org/packages/mixdinternet/mconfig)
[![License](https://poser.pugx.org/mixdinternet/mconfig/license.svg)](https://packagist.org/packages/mixdinternet/mconfig)

![Área administrativa](http://mixd.com.br/github/1a1944e9d3d545eee7b3929295fabb37.png "Área administrativa")

Agrupa os módulos de configuração do Admix.

## Instalação

Adicione no seu composer.json

```js
  "require": {
    "mixdinternet/mconfig": "0.1.*"
  }
```

ou

```js
  composer require mixdinternet/mconfig
```

## Service Provider

Abra o arquivo `config/app.php` e adicione

`Mixdinternet\Mconfig\Providers\MconfigServiceProvider::class`

Veja a configuração de cada módulo

[Mcache](https://github.com/mixdinternet/mcache)

[Maudit](https://github.com/mixdinternet/maudit)