<?php

use app\models\ProductToBox;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\ProductToBoxSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Product To Boxes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-to-box-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Product To Box', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'sku',
            'box_id',
            'title',
            'shipped_qty',
            'received_qty',
            //'price',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, ProductToBox $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'sku' => $model->sku]);
                 }
            ],
        ],
    ]); ?>


</div>
