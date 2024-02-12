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
    $form = ActiveForm::begin([
            'action'=> Url::to(['update', 'id'=>$model->id]), ]) ?>

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
            <div class="col-md-2">
                <?php
                    $items = ArrayHelper::map($status,'id','name');

                    // оставляем только нужные статусы для карточки коробки
                    $controller_id = Yii::$app->controller->id;
                    $action_id = Yii::$app->controller->action->id;
                    if($controller_id == 'box' && $action_id == 'view') {
                        $items = array_diff($items, array('At warehouse', 'Expected'));
                    }
                  

                    // $params = [
                    //     //'prompt' => 'Выберите статус...',
                    //     'options'=>[
                    //          '1' => ['disabled' => false],
                    //          '2' => ['disabled' => false],
                    //          '3' => ['disabled' => false],
                    //          '4' => ['disabled' => false],
                    //     ],
                    // ];
                    
                    echo $form->field($model, 'status_id')->dropDownList($items, ['class'=>"form-select" ]);
                ?>
            </div>
                
        </div>
        <div class="form-group">
            <?= Html::submitButton('Save Box', ['class' => 'btn btn-success']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>
