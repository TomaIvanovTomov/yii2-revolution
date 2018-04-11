<?php

namespace tomaivanovtomov\slider;

use yii\web\AssetBundle;

class Assets extends AssetBundle
{
    public $sourcePath = "@vendor/tomaivanovtomov/yii2-slider/src";
    public $css = [
        'dist/assets/owl.carousel.min.css',
        'dist/assets/owl.theme.default.min.css',
        'custom.css',
    ];
    public $js = [
        'dist/owl.carousel.min.js',
        'custom.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}