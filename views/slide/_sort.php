<?php

use kartik\sortable\Sortable;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$form = ActiveForm::begin();

?>

<div class="col-sm-12">
    <h3><?= Yii::t('app', 'Sort') ?></h3>
</div>

<div class="col-sm-12">

    <?php

        echo Sortable::widget([
            'type' => Sortable::TYPE_GRID,
            'items' => $result
        ]);

    ?>

</div>

<div class="col-sm-12">
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>
</div>

<?php

    ActiveForm::end();

?>


