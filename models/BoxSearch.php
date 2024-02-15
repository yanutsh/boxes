<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Box;

/**
 * BoxSearch represents the model behind the search form of `app\models\Box`.
 */
class BoxSearch extends Box
{
    public $title;
    public $sku;
    public $day_from, $day_to;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status_id'], 'integer'],
            [['weight', 'width', 'length', 'height'], 'number'],
            [['reference', 'created_at'], 'safe'],
            [['title', 'sku'], 'string', 'max'=>255],
            [['day_from', 'day_to'], 'date', 'format'=>'php:d.m.Y'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        // вместе с продуктами в коробке
        $query = Box::find()->joinWith('productToBoxes');
              
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        // if (!$this->validate()) {
        //     // uncomment the following line if you do not want to return any records when validation fails
        //     $query->where('0=1');
        //     //debug('Валидация не прошла');
        //     return $dataProvider;
        // }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'weight' => $this->weight,
            'width' => $this->width,
            'length' => $this->length,
            'height' => $this->height,
            'status_id' => $this->status_id,
            'created_at' => $this->created_at,
            'product_to_box.sku' => $this->sku,
        ]);

        $day_to_db = Date('Y-m-d', strtotime($this->day_to)+24*60*60);
        $query->andFilterWhere(['like', 'reference', $this->reference]);
        $query->andFilterWhere(['like', 'title', $this->title]);
        $query->andFilterWhere(['between', 'created_at', $this->day_from, $day_to_db]);
      

        return $dataProvider;
    }
}
