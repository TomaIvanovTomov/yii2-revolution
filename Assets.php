<?php

namespace tomaivanovtomov\revolution;

use yii\web\AssetBundle;

class Assets extends AssetBundle
{
    public $sourcePath = "@vendor/tomaivanovtomov/yii2-revolution/src";
    public $css = [
        'fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css',
        'css/settings.css',
        'css/layers.css',
        'css/navigation.css'
    ];
    public $js = [
        'js/jquery.themepunch.tools.min.js',
        'js/jquery.themepunch.revolution.min.js',
        'js/revolution-custom.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}