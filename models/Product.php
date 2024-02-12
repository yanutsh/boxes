<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property string $title
 * @property string $sku
 *
 * @property Box[] $boxes
 * @property ProductToBox[] $productToBoxes
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'sku'], 'required'],
            [['title', 'sku'], 'string', 'max' => 255],
            [['sku'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'sku' => 'Sku',
        ];
    }

    /**
     * Gets query for [[Boxes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBoxes()
    {
        return $this->hasMany(Box::class, ['id' => 'box_id'])->viaTable('product_to_box', ['prod_id' => 'id']);
    }

    /**
     * Gets query for [[ProductToBoxes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProductToBoxes()
    {
        return $this->hasMany(ProductToBox::class, ['prod_id' => 'id']);
    }
}
