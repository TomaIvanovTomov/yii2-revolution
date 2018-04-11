
<div class="carouser-wrapper">

    <?php if(!empty($slides)) : ?>

        <div class="owl-carousel owl-theme">

            <?php foreach ($slides as $slide) : ?>

                <div>
                    <?= $slide->getImage($height) ?>
                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</div>