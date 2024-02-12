<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\ProductToBox $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Product To Boxes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="product-to-box-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'sku' => $model->sku], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'sku' => $model->sku], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'sku',
            'box_id',
            'title',
            'shipped_qty',
            'received_qty',
            'price',
        ],
    ]) ?>

</div>
