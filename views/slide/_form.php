<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use sadovojav\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model app\models\Slide */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="slide-form">

    <?php $form = ActiveForm::begin(); ?>

    <!--Languages tabs-->
    <?= $this->render('../helpers/lang_tabs') ?>

    <div class="tab-content pt20 pb20">

        <?php foreach (Yii::$app->params['language-information'] as $key => $lang) : ?>

            <?php if(Yii::$app->params['languageDefault'] == $lang['extension']) : ?>

                <div id="BG" class="tab-pane fade in active">
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-12">

                                <?= $form->field($model, 'title')->textInput(['maxLength' => true]) ?>

                            </div>
                            <div class="col-sm-12">

                                <?= $form->field($model, 'description')->widget(CKEditor::className()); ?>

                            </div>
                        </div>
                    </div>
                </div>

            <?php else : ?>

                <div id="<?= $key ?>" class="tab-pane fade">
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-12">

                                <?= $form->field($model, "title_{$lang['extension']}")->textInput(['maxLength' => true]) ?>

                            </div>
                            <div class="col-sm-12">

                                <?= $form->field($model, "description_{$lang['extension']}")->widget(CKEditor::className()); ?>

                            </div>
                        </div>
                    </div>
                </div>

            <?php endif; ?>

        <?php endforeach; ?>

    </div>

    <div class="form-group pt20">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
