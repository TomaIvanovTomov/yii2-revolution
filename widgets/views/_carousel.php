<?php
use tomaivanovtomov\slider\widgets\Slider;
?>

<div class="carouser-wrapper">

    <?php if(!empty($slides)) : ?>

        <div class="owl-carousel owl-theme">

            <?php if ($this->beginCache('slide', ['dependency' => Slider::DEPENDENCY])) : ?>

                <?php foreach ($slides as $slide) : ?>

                    <div>
                        <?= $slide->getImage($height) ?>
                    </div>

                <?php endforeach; ?>

                <?php $this->endCache(); ?>

            <?php endif; ?>

        </div>

    <?php endif; ?>

</div>

