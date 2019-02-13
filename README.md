# KnaBEMBundle

[![Build Status](https://travis-ci.org/knaydenov/bem-bundle.svg?branch=master)](https://travis-ci.org/knaydenov/bem-bundle)

This bundle provides helpers for BEM classes generation.

## Installation

```
composer require kna/bem-bundle
```

## Configuring

```
kna_bem:
    block_function_name: b #default
```

## Usage

```
{# templates/base.html.twig #}

<div class="{{ b('card').m('color', 'red') }}">
    <div class="{{ b('card').e('title') }}">Title</div>
    <div class="{{ b('card').e('content').m('hidden').addClass('no-js') }}">Some content</div>
</div>
```

## Running tests

```
docker-compose run --rm php ./vendor/bin/simple-phpunit
 
```