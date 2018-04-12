# STILL IN DEVELOPMENT!!!!
# yii2-slider
Yii2 slider is an extension with Owl Carousel 2.3.3 slider in it.<br />
The slider has multilingual behaviour for title and description on each slide.
<h2>Installation</h2>

```bash
composer require tomaivanovtomov/yii2-revolution "^1.0.2"
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
php yii migrate/up --migrationPath=@vendor/tomaivanovtomov/yii2-slider/migrations
```

<h2>Register assets</h2>
Register revolution assets on top of your `layout\main.php`

```php
\tomaivanovtomov\revolution\Assets::register($this);
```

Image path is set to `www.example.com/frontend/web` .

<h2>Usage</h2>

Call the widget and set the preferable options.<br />
`height` - height of the slider.<br />
`slides` - Images like an array of objects.

```php

    public static function getSliderImages()
    {
        return \tomaivanovtomov\slider\models\Slide::find()
            ->joinWith('translation')
            ->select(['slide.id', 'slideLang.title', 'slide.filename'])
            ->where('slideLang.language=:lang', [':lang' => Yii::$app->language])
            ->all();
    } 
```

```php
    echo \tomaivanovtomov\slider\widgets\Slider::widget([
        'slides' => \tomaivanovtomov\slider\models\Slide::getSliderImages(),
        'height' => 400,
        'options' => [
            'items' => 1
        ]
    ]);

```

All slider options can be seen at 

```bash
https://owlcarousel2.github.io/OwlCarousel2/docs/api-options.html
```

<h2>Multilingual part</h2>
Copy these line in `params.php`:

```php
'languages' => [
    'bg' => 'bg',
    'en' => 'en',
],
'languageDefault' => 'bg',
'availableImageExtensions' => ['jpeg','jpg','png','gif']
```

This portion of code is linked with the multilingual model functionality. You can override the model and adapt it to your needs. 