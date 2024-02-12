<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ProductToBox $model */

$this->title = 'Update Product To Box: ' . $model->box_id;
$this->params['breadcrumbs'][] = ['label' => 'Product To Boxes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'sku' => $model->sku]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="product-to-box-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
