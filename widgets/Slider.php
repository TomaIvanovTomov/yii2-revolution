<?php

namespace tomaivanovtomov\slider\widgets;

use yii\base\Widget;

class Slider extends Widget
{
    /**
     * Slide objects
     *
     * @var array
     */
    public $slides = [];

    /**
     * Image height
     *
     * @var
     */
    public $height;

    /**
     * Carousel slider options
     *
     * @var array
     */
    public $options = [];

    public function init()
    {
        parent::init();

        if($this->height === null){
            $this->height = 400;
        }

        if(empty($this->options) || $this->options === null){
            $this->options = [
                'items' => 1,
                'autoplay' => true,
                'autoplayTimeout' => 5000,
                'loop' => true
            ];
        }

    }

    public function run(){

        $clientOptions = json_encode($this->options);

        $this->view->registerJs("
            $(function () {
                $('.owl-carousel').owlCarousel($clientOptions);
            });
        ");

        return $this->render('_carousel', [
            'slides' => $this->slides,
            'height' => $this->height,
        ]);
    }
}