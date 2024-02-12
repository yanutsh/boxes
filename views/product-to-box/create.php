<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ProductToBox $model */

$this->title = 'Create Product To Box'.$model['box_id'];
$this->params['breadcrumbs'][] = ['label' => 'Product To Boxes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-to-box-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
