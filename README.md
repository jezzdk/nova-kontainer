# Nova Kontainer

> Nova Kontainer is a Laravel Nove component that adds a file picker for Kontainer

## Features

Select any image or video from Kontainer and have the url stored in Laravel Nova.

> You must have a Kontainer account in order to use this plugin.
> Read more and create an account on [the official website](https://kontainer.com/).

## How to Install

Run the following command from your project root:

``` bash
composer require jezzdk/nova-kontainer
```

## Usage

```php
public function fields()
{
    return [ 
        NovaKontainer::make(__('Image')),
     ];
}
```

## Config

Set the URL to your Kontainer account with `kontainerUrl()`:

```php
public function fields()
{
    return [ 
        NovaKontainer::make(__('Image'))
            ->kontainerUrl('https://my-account.kontainer.com'),
     ];
}
```
