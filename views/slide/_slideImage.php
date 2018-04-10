<?php

use kartik\file\FileInput;
use yii\helpers\Html;
use tomaivanovtomov\revolution\models\Slide;

\kartik\file\FileInputAsset::register($this);

?>

<div class="col-sm-4 col-xs-12 slide-image-counter mt20">

    <div class="row">

        <div class="form-group">

            <div class="col-sm-12">
                <?=
                    FileInput::widget([
                        'model' => $model,
                        'attribute' => "image[$index]",
                        'showMessage' => true,
                        'pluginOptions' => [
                              'initialPreview'=>[
                                Html::img(Yii::getAlias('@images') . "/backend_images/".Slide::FOLDER_SLIDER."/" . $model->filename)
                            ],
                            'overwriteInitial'=>true
                        ],
                    ])
                ?>
            </div>

            <div class="col-sm-12 mt20 mb20">
                <ul class="nav nav-tabs">

                    <?php foreach (Yii::$app->params['language-information'] as $id => $language) : ?>

                        <li class="<?= ($id == "BG") ? "active" : "" ?>">
                            <a data-toggle="tab" href="#<?= $id ?>_<?= $index ?>"><?= $language['title'] ?></a>
                        </li>

                    <?php endforeach; ?>

                </ul>
            </div>

            <div class="tab-content pt20 pb20">

                <?php foreach (Yii::$app->params['language-information'] as $key => $lang) : ?>

                    <?php if(Yii::$app->params['languageDefault'] == $lang['extension']) : ?>

                        <div id="BG_<?= $index ?>" class="tab-pane fade in active">

                            <div class="col-sm-12">

                                <?= Html::activeLabel($model, "title[$index]")?>

                                <?= Html::activeInput('text', $model, "title[$index]", [
                                    'maxLength' => true,
                                    'class' => 'form-control',
                                    'value' => isset($model->title) ? $model->title : ""
                                ]) ?>

                            </div>

                            <div class="col-sm-12">

                                <?= Html::activeLabel($model, "description[$index]")?>

                                <?= Html::activeTextarea($model, "description[$index]", [
                                    'maxLength' => true,
                                    'rows' => '3',
                                    'class' => 'form-control',
                                    'value' => isset($model->description) ? $model->description : ""
                                ]) ?>

                            </div>

                        </div>

                    <?php else : ?>

                        <div id="<?= $key ?>_<?= $index ?>" class="tab-pane fade">

                            <div class="col-sm-12">

                                <?php $attr_title = "title_" . $lang['extension'] ?>

                                <?= Html::activeLabel($model, "title_" . $lang['extension'] . "[$index]")?>

                                <?= Html::activeInput('text', $model, "title_" . $lang['extension'] . "[$index]", [
                                    'maxLength' => true,
                                    'class' => 'form-control',
                                    'value' => isset($model->$attr_title) ? $model->$attr_title : ""
                                ]) ?>

                            </div>

                            <div class="col-sm-12">

                                <?php $attr_desc = "description_" . $lang['extension'] ?>

                                <?= Html::activeLabel($model, "description_" . $lang['extension'] . "[$index]")?>

                                <?= Html::activeTextarea($model, "description_" . $lang['extension'] . "[$index]", [
                                    'maxLength' => true,
                                    'rows' => '3',
                                    'class' => 'form-control',
                                    'value' => isset($model->$attr_desc) ? $model->$attr_desc : ""
                                ]) ?>

                            </div>

                        </div>

                    <?php endif; ?>

                <?php endforeach; ?>

            </div>

        </div>

    </div>

    <!--Check if it is a new record-->
    <?= Html::input('hidden', "Slide[is_new][$index]", isset($model->id) ? 0 : 1 )?>

    <!--Send model ID if it is an old record-->
    <?= Html::input('hidden', "Slide[model_id][$index]", isset($model->id) ? $model->id : 0 )?>

</div>
