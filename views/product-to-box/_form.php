<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ProductToBox $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="product-to-box-form container">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'box_id')->hiddenInput()->label(false) ?>
        <div class="row">    
            <div class="col-md-2">
                <?= $form->field($model, 'sku')->textInput(['maxlength' => true]) ?>
            </div>

            <div class="col-md-2">
                <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
            </div>

            <div class="col-md-2">    
                <?= $form->field($model, 'shipped_qty')->textInput() ?>
            </div>
                
             <div class="col-md-2">
                 <?= $form->field($model, 'received_qty')->textInput() ?>
            </div>     

            <div class="col-md-2">
                <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>
            </div> 
        </div>       
        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            <?= Html::Button('Match', ['id'=>'matchButton', 'class' => 'btn btn-danger']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>
