<?php
use tomaivanovtomov\slider\widgets\Slider;
?>

<div class="carouser-wrapper">

    <?php if(!empty($slides)) : ?>

        <div class="owl-carousel owl-theme">

            <?php if ($this->beginCache('slide', [
                    'dependency' => Slider::DEPENDENCY,
                    'variations' => [
                        Yii::$app->language
                    ]
            ])) : ?>

                <?php foreach ($slides as $slide) : ?>

                    <div style="position: relative;">
                        <?= $slide->getImage($height) ?>
                        <div class="slider-title"><?= $slide->title ?></div>
                        <div class="slider-description"><?= $slide->description ?></div>
                    </div>

                <?php endforeach; ?>

                <?php $this->endCache(); ?>

            <?php endif; ?>

        </div>

    <?php endif; ?>

</div>

