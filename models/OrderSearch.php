<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Order;
use DateTime;

/**
 * OrderSearch represents the model behind the search form of `app\models\Order`.
 */
class OrderSearch extends Order
{
    public $fullName;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'automobile_id', 'paid_in_full', 'stage'], 'integer'],
            [['date',  'customer_id','make','model', 'fullName', 'stage'], 'safe'],
            [[ 'tax', 'amount_paid'], 'number'],
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
        $query = Order::find();
        $query->leftJoin('customer', 'customer.id=order.customer_id');
        $query->leftJoin('automobile', 'automobile.id=order.automobile_id');
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        // $this->load($params);
        $dataProvider->setSort([ //merge array
            'attributes' => [
                //'id',
                'fullName' => [
                    'asc' => ['customer.first_name' => SORT_ASC, 'customer.last_name' => SORT_ASC],
                    'desc' => ['customer.first_name' => SORT_DESC, 'customer.last_name' => SORT_DESC],
                    'label' => 'Full Name',
                    'default' => SORT_ASC
                ],
                'date',
            ]
        ]);
        if (!($this->load($params) && $this->validate())) {
            // uncomment the following line if you do not want to return any records when validation fails
            return $dataProvider;
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            //'customer_id' => $this->customer_id,
            //'fullName' => $this->fullName,
            'automobile_id' => $this->automobile_id,
            //'date' => $this->date,
            //'subtotal' => $this->subtotal,
            'stage' => $this->stage,
            'tax' => $this->tax,
            'amount_paid' => $this->amount_paid,
            'paid_in_full' => $this->paid_in_full,
        ]);

        $query->andFilterWhere(['like', 'automobile.make', $this->make])
            ->andFilterWhere(['like', 'automobile.model', $this->model])
            //->andFilterWhere(['between', 'date', $this->date, $newDate]);
            //->andFilterWhere(['<', 'date', $this->date]);
            ->andFilterWhere(['Date(date)' => $this->date]);
        //\Yii::debug($this->fullName, 'dev');
        $query->andWhere('customer.first_name LIKE "%' . $this->fullName . '%" ' .
        'OR customer.last_name LIKE "%' . $this->fullName . '%"'. 'OR Concat(customer.first_name, " ", customer.last_name) LIKE "%' . $this->fullName . '%"' );

        return $dataProvider;
    }
}
