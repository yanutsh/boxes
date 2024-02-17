<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper ;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Box $model */
/** @var yii\widgets\ActiveForm $form */
?>

<?php 
    if($action === 'create') $disabled = true;
?>
<div class="box-form container">
    <?php 
    //debug($action);
    if($action == 'create') $action = Url::to(['create']);
    if($action == 'update') $action = Url::to(['update', 'id'=>$model->id]);
    if($action == 'view') $action = Url::to(['update', 'id'=>$model->id]);
    
    $form = ActiveForm::begin([
            'action'=> $action,
            ]) ?>

        <?= $form->field($model, 'id')->hiddenInput(['value'=>$model->id, 'maxlength' => true])->label(false) ?>        
        <div class="row">    
            <div class="col-md-2">
                <?= $form->field($model, 'weight')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-2">
                <?= $form->field($model, 'width')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-2">
                <?= $form->field($model, 'length')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-2">
                <?= $form->field($model, 'height')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-2">
                <?= $form->field($model, 'reference')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-2" style="display:">
                <?php
                    //debug($status);
                    $items = ArrayHelper::map($status,'id','name');
                    // оставляем только нужные статусы для карточки коробки
                    $controller_id = Yii::$app->controller->id;
                    $action_id = Yii::$app->controller->action->id;
                    if( $action_id == 'view') {
                        $items = array_diff($items, array('At warehouse', 'Expected'));
                    }

                    if($action_id == 'create'){
                         $items = [];
                        echo $form->field($model, 'status_id')->dropDownList($items, ['class'=>"form-select", 'disabled'=> true]);
                    } else     
                        echo $form->field($model, 'status_id')->dropDownList($items, ['class'=>"form-select"]);
                ?>
            </div>
                
        </div>
        <div class="form-group">
            <?= Html::submitButton('Save Box', ['class' => 'btn btn-success']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>
