<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%order}}".
 *
 * @property int $id
 * @property int $customer_id
 * @property int $automobile_id
 * @property int $stage_id
 * @property string|null $date
 * @property float|null $tax
 * @property string|null $notes
 * @property float|null $amount_paid
 * @property int|null $paid_in_full
 *
 * @property Labor[] $labors
 * @property Part[] $parts
 * @property Automobile $automobile
 * @property Customer $customer
 */
class Order extends \yii\db\ActiveRecord
{
    public $make;
    public $model;
    public static $stages =
    [
        1 => 'Created',
        2 => 'In Progress',
        3 => 'Completed',
        4 => 'Paid',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%order}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customer_id', 'automobile_id', 'odometer_reading', 'stage', 'date'], 'required'],
            [['customer_id', 'automobile_id', 'paid_in_full'], 'integer'],
            [['date'], 'safe'],
            [['tax', 'amount_paid', 'odometer_reading'], 'number'],
            [['automobile_id'], 'exist', 'skipOnError' => true, 'targetClass' => Automobile::class, 'targetAttribute' => ['automobile_id' => 'id']],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::class, 'targetAttribute' => ['customer_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'customer_id' => Yii::t('app', 'Customer ID'),
            'automobile_id' => Yii::t('app', 'Automobile ID'),
            'date' => Yii::t('app', 'Date'),
            'tax' => Yii::t('app', 'Tax'),
            'make' => Yii::t('app', 'Make'),
            'order_notes' => Yii::t('app', 'Order Notes'),
            'amount_paid' => Yii::t('app', 'Amount Paid'),
            'paid_in_full' => Yii::t('app', 'Paid In Full'),
        ];
    }

    public function beforeSave($insert)
    {
        // Need to do this logic to convert jui/date object to sql datetime object
        $this->date = date('Y-m-d', strtotime($this->date));
        return true;
    }

    /**
     * Gets query for [[Labors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLabors()
    {
        return $this->hasMany(Labor::class, ['order_id' => 'id']);
    }

    /**
     * Gets query for [[Parts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParts()
    {
        return $this->hasMany(Part::class, ['order_id' => 'id']);
    }

    /**
     * Gets query for [[Automobile]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAutomobile()
    {
        return $this->hasOne(Automobile::class, ['id' => 'automobile_id']);
    }

    /**
     * Gets query for [[Customer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::class, ['id' => 'customer_id']);
    }

    /**
     * Gets query for [[Stage]].
     *
     * @return string
     */
    public function getStageName()
    {
        return self::$stages[$this->stage];
    }

    public function getFullName()
    {
        return $this->customer->first_name . ' ' . $this->customer->last_name;
    }

    public function getNotes()
    {
        return $this->hasMany(Note::class, ['order_id' => 'id']);
    }

    public function getTaxAmount()
    {
        $total_parts = 0;
        foreach ($this->parts as $part) {
            $part_with_margin = $part->price + ($part->price * ($part->margin / 100));
            $total_parts += $part_with_margin*$part->quantity;
        }
        return $total_parts * $this->tax;
    }

    public function getSubtotal()
    {
        $subtotal = 0;
        foreach ($this->parts as $part) {
            $part_with_margin = $part->price + ($part->price * ($part->margin / 100));
            $subtotal += $part_with_margin*$part->quantity;
        }
        foreach ($this->labors as $labor) {
            $subtotal += $labor->price;
        }
        return round($subtotal, 2);
    }

    public function getTotal()
    {
        $total = $this->subtotal + $this->taxAmount;
        return round($total, 2);
    }

    public function getPartTotal()
    {
        $total = 0;
        foreach ($this->parts as $part) {
            $total += ($part->price + ($part->price * ($part->margin / 100))) * $part->quantity;
        }
        return round($total, 2);
    }

    public function getLaborTotal()
    {
        $total = 0;
        foreach ($this->labors as $labor) {
            $total += $labor->price;
        }
        return round($total, 2);
    }

    public function canChangeStage($increment)
    {
        if (($this->stage + $increment > count(self::$stages)) || ($this->stage + $increment < 1)) {
            return false;
        }
        return true;
    }
}
