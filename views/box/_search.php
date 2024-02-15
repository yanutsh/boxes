<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper ;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\BoxSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="box-search container">

    <?php $form = ActiveForm::begin([
        'id'=> 'boxSearch',
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
        <div class="col-md-3">
            <?= $form->field($model, 'reference') ?>
        </div>
       
        <div class="col-md-2">
                <?php
                    $items = ArrayHelper::map($status,'id','name');
                    $params = [
                        'prompt' => 'All values'               
                    ];    
                    echo $form->field($model, 'status_id')->dropDownList($items, $params);
                ?>
            </div>
        <div class="col-md-2">
            <?= $form->field($model, 'sku') ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'title') ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'day_from')->textInput(['type'=>'date']) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'day_to')->textInput(['type'=>'date']) ?>
        </div>        
       
</div>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
