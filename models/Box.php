<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "box".
 *
 * @property int $id
 * @property float $weight
 * @property float $width
 * @property float $length
 * @property float $height
 * @property string $reference
 * @property int|null $status_id
 * @property string $created_at
 *
 * @property Product[] $prods
 * @property ProductToBox[] $productToBoxes
 * @property Status $status
 */
class Box extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'box';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['weight', 'width', 'length', 'height', 'reference'], 'required'],
            [['weight', 'width', 'length', 'height'], 'number', 'min'=>'0'],
            [['status_id'], 'integer'],
            [['created_at'], 'safe'],
            [['reference'], 'string', 'max' => 255],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => Status::class, 'targetAttribute' => ['status_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'weight' => 'Weight',
            'width' => 'Width',
            'length' => 'Length',
            'height' => 'Height',
            'reference' => 'Reference',
            'status_id' => 'Status ID',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Prods]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProds()
    {
        return $this->hasMany(Product::class, ['id' => 'prod_id'])->viaTable('product_to_box', ['box_id' => 'id']);
    }

    /**
     * Gets query for [[ProductToBoxes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProductToBoxes()
    {
        return $this->hasMany(ProductToBox::class, ['box_id' => 'id']);
    }

    /**
     * Gets query for [[Status]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(Status::class, ['id' => 'status_id']);
    }
}
