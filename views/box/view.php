<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Box $model */

$this->title = 'Карточка коробки №'. $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Boxes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="box-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update Box', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete Box', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <span>Этот блок можно не включать или сделать нередактируемым</span>
    </p>

    <?= $this->render('_form', compact('model','status')) ?>
   
    <h3>Список продуктов в коробке <?= $model['id'] ?></h3>
    <div class="productInBox">
        <a href="<?= '/product-to-box/create?id='.$model['id'] ?>" id="addProduct" type="button" class="btn btn-primary" data-toggle="modal" data-target="#addProductModal">Add product</a>

        <!-- таблица продуктов в коробке-->
        <table class="table">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Title</th>
              <th scope="col">SKU</th>
              <th scope="col">Shipped Qty</th>
              <th scope="col">Received Qty</th>
              <th scope="col">Price</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
                $i=1; 
                foreach($product_to_box as $ptb) { ?>
                    <tr>
                      <td><?= $i ?></td>
                      <td><?= $ptb['title'] ?></td>
                      <td><?= $ptb['sku'] ?></td>
                      <td><?= $ptb['shipped_qty'] ?></td>
                      <td><?= $ptb['received_qty'] ?></td>
                      <td><?= $ptb['price'] ?></td>
                      <td>
                        <a href="<?= '/product-to-box/update?sku='.$ptb['sku']?>">
                            <img src="/web/icons/pencil30.png" alt="" title="Редактировать продукт">
                        </a>
                        <a href="<?= '/product-to-box/delete?sku='.$ptb['sku']?>">
                            <img src="/web/icons/trash.png" alt="" title="Удалить продукт">
                        </a>
                      </td>
                    </tr>
                <?php  $i++; 
                } ?>
          </tbody>
        </table>
        <div class="resume"><b>Всего товаров <span id='summaKol'>0</span>, на сумму <span id="summaPrice"> </span> ₽.</b></div>
    </div>

  
</div>
