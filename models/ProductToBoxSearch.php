<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ProductToBox;

/**
 * ProductToBoxSearch represents the model behind the search form of `app\models\ProductToBox`.
 */
class ProductToBoxSearch extends ProductToBox
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sku', 'title'], 'safe'],
            [['box_id', 'shipped_qty', 'received_qty'], 'integer'],
            [['price'], 'number'],
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
        $query = ProductToBox::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'box_id' => $this->box_id,
            'shipped_qty' => $this->shipped_qty,
            'received_qty' => $this->received_qty,
            'price' => $this->price,
        ]);

        $query->andFilterWhere(['like', 'sku', $this->sku])
            ->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}
