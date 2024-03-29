<?php

use app\models\Box;

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper ;

use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\models\BoxSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Boxes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Box', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    
    <?= $this->render('_search', ['model' => $searchModel,
                        'status'=> $status]); ?>

    <?php Pjax::begin([
        'id'=> 'boxesPjax',
        //'timeout' => 1000000,
        'enablePushState' => false,
    ]); ?> 

    <?= GridView::widget([
        'id' => 'boxesGrid',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'summary' => "Показано коробок - {count} шт.", 
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            ['class' => 'yii\grid\CheckboxColumn'],
            [
                'attribute' => 'id',
                'format' => 'html',
                'value' => function ($data) use ($isEQ) {
                    if($isEQ[$data['id']]) $class='';
                    else $class = 'nonEQ';
                    
                    return '<div class="'.$class.'">' . $data['id'] .  '</div>';
                }, 
            ],    
            'created_at',
            'reference',
            'weight',
            [ 
                'attribute' => 'status_id',
                'value' => function ($data) use ($status){
                    return $status[$data['status_id']]['name'];
                },    
            ],    
            [
                'class' => ActionColumn::className(),
                'buttons' => [
                    'delete' => function ($url, $model, $key) {
                        return Html::a('<img class="gridTrash" src="/web/icons/trash.svg" alt="">', $url, [
                            'title' => Yii::t('yii', 'Delete'),
                            'data-pjax' => 1,
                        ]);
                    },
                ],
                
            ],           
        ],
    ]); ?>

    <?php Pjax::end(); ?>
    
    <?php
        $items = ArrayHelper::map($status,'id','name'); 

        // оставляем только нужные статусы для карточки коробки
        $controller_id = Yii::$app->controller->id;
        $action_id = Yii::$app->controller->action->id;
        if($controller_id == 'box' && $action_id == 'index') {
            $items = array_diff($items, array('Prepared', 'Shipped'));
        }

        $box = new Box();
    ?>
    <div class="col-md-12 fb"> 
        <div class="fb__text" >
            <a href="" id="changeStatus" data-pjax = 1>Изменить статус на:</a>
        </div>
        <div class="fb__status">    
            <?= Html::activeDropDownList($box, 'status', $items, ['class'=>'form-select', 'prompt'=>'Выберите']) ?>
        </div>
        <div class="fb__export">
            <button type="button" class="btn btn-warning">Report</button>
        </div>       
    </div>
</div>

<?php
