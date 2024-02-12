<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\BoxSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="box-search container">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <div class="row">    
        <div class="col-md-2">
            <?= $form->field($model, 'id') ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'weight') ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'width') ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'length') ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'height') ?>
        </div>
        <?php // echo $form->field($model, 'reference') ?>

        <?php // echo $form->field($model, 'status_id') ?>

        <?php // echo $form->field($model, 'created_at') ?>
</div>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
