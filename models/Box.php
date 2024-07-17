<?php

namespace app\models;

use Yii;
use app\traits\EventTrait;
use app\dispatchers\BoxEventDispatcher;


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
    use EventTrait; 

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
            [['reference'], 'required'],
            [['weight', 'width', 'length', 'height', 'volume', 'total_summ'], 'number', 'min'=>'0'],
            [['weight', 'width', 'length', 'height', 'volume', 'total_summ'], 'default', 'value'=>'0'],
            [['status_id'], 'integer'],
            [['created_at', 'iseq'], 'safe'],
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
            'weight' => 'Weight, kg.',
            'width' => 'Width, cm.',
            'length' => 'Length, cm.',
            'height' => 'Height, cm.',
            'volume' => 'Volume, m3.',
            'reference' => 'Reference',
            'status_id' => 'Status ID',
            'created_at' => 'Created At',
        ];
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

    // расчет объема коробки
    public static function updateBoxVolume($box_id) {
        $box = Box::findOne($box_id);
        $volume = ($box->width * $box->length * $box->height)/1000000;
        $box->volume = $volume;
        return $box->save();        
    }

    // расчет суммы товаров в коробке и проверка совпадений количеств
    public static function updateBoxPrice($box_id) {
        $box = Box::find()->where(['id'=>$box_id])->with('productToBoxes')->one();
        //debug($box);
        $summa = 0;
        $isEQ = true;
        foreach($box['productToBoxes'] as $product) {
            $summa += $product['price'];
            if($product['shipped_qty'] <> $product['received_qty']) $isEQ = $isEQ && false;
        }
             
        $box->total_summ = $summa;
        $box->iseq =  $isEQ;
        return $box->save();        
    }

}
