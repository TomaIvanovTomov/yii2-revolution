# STILL IN DEVELOPMENT!!!!
# DO NOT DOWNLOAD!!!!
# yii2-revolution
Yii2 revolution is an extension with revolution slider in it.
<h2>Installation</h2>

```bash
composer require tomaivanovtomov/yii2-revolution "^1.0.0"
```

<h2>Configuration</h2>
Add the Module class to `config.php`:

```php
'modules' => [
    ....
    'user' => [
        'class' => 'tomaivanovtomov\slider\Module',
    ],
    ....
],
```

<h2>Add migrations</h2>
Create the two tables - `slide` and `slideLang`

```bash
php yii migrate/up --migrationPath=@vendor/tomaivanovtomov/yii2-revolution/migrations
```

<h2>Register assets</h2>
Register revolution assets on top of your `layout\main.php`

```php
\tomaivanovtomov\revolution\Assets::register($this);
```
