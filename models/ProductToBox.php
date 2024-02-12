<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product_to_box".
 *
 * @property string $sku
 * @property int $box_id
 * @property string $title
 * @property int $shipped_qty
 * @property int $received_qty
 * @property float $price
 */
class ProductToBox extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_to_box';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sku', 'box_id', 'title', 'shipped_qty'], 'required'],
            [['box_id', 'shipped_qty', 'received_qty'], 'integer'],
            [['price','shipped_qty','received_qty' ], 'number', 'min'=> '0'],
            [['sku', 'title'], 'string', 'max' => 255],
            [['sku'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'sku' => 'Sku',
            'box_id' => 'Box ID',
            'title' => 'Title',
            'shipped_qty' => 'Shipped Qty',
            'received_qty' => 'Received Qty',
            'price' => 'Price',
        ];
    }
}
