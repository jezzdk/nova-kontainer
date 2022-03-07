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

> The image data will be saved as json, so you should make the database field text/json. In your model the field can then be cast as an array.

Given the example above, the `image` property will contain the url and the type of the file as a minimum. It may contain several other properties that are passed from Kontainer.

```php
$model->image['url']; // https://myaccount.kontainer.com/cdn/2SP4HV/bm-604549.jpg
$model->image['type']; // 'image'
$model->image['extension']; // 'jpg'
...
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

## Upgrading from v1

From v2 the data will be saved as json in the database. If you have already used v1 for a while, and you already have data, you should migrate your data to the new json format.

In v1 the data was just the url of the image. As a minimum the new json data should contain the `url` and `type`. A simple database query can do this in one go:

```sql
UPDATE my_table SET my_field = REPLACE('{"url":"%url%", "type":"image"}', '%url%', my_field)
```

If you have a mix of images and videos, you might want to add some where clauses to the query, and set the `type` accordingly.

After this you should be able to convert the field type to text or json.
