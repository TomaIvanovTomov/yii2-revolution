<?php

use yii\widgets\ListView;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

\kartik\file\FileInputAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SlideSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Slides');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">

    <div class="col-sm-12">
        <div class="form-group">
            <button type="button" class="btn btn-primary" onclick="addSlideModel()"><?= Yii::t('app', 'Add slide') ?></button>
        </div>
    </div>

    <?php

        $form = ActiveForm::begin([
            'options' => [
                'multipart/form-data'
            ],
            'action' =>  [
                'slide/create'
            ],
            'id' => 'slides'
        ]);

        //Hidden model to enable UploadFile class
        echo $form->field($hidden, 'image[]')->fileInput(['class' => 'display-n'])->label(false);

        echo ListView::widget([
            'dataProvider' => $dataProvider,
            'layout' => "{items}",
            'itemView' => function( $model, $key, $index, $widget ){
                return $this->render("_slideImage", [
                    'model' => $model,
                    'index' => $index
                ]);
            },
        ]);

    ?>

    <div class="col-sm-12">
        <div class="form-group mt20">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php
         ActiveForm::end();
    ?>

</div>

<script>

    function addSlideModel(){
        let index = $('.slide-image-counter').length;
        if(index < 6){
            $.ajax({
                method: "POST",
                url: location.href.split('/slider')[0]+"/slider/slide/add-slide",
                data: {
                    index: index
                },
                success: function ( data ) {
                    $('.list-view').append( data );
                    $('.empty').remove();
                }
            })
        }

    }

</script>