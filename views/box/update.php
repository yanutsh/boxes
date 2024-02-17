<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Box $model */

$this->title = 'Update Box: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Boxes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="box-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'status' => $status,
        'action' => 'update',
    ]) ?>

</div>
