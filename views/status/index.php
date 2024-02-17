<?php

use app\models\Status;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\models\StatusSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Statuses';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="status-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Status', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin([
        'id'=> 'statusList',
        //'timeout' => 1000000,
        'enablePushState' => false,
    ]); ?>

    <?= GridView::widget([
        'id' => 'statusGrid',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
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

</div>

<?php

