<?php

namespace backend\models;

use Yii;
use backend\models\Product;
use backend\models\Member;
/**
 * This is the model class for table "invoice".
 *
 * @property integer $order_id
 * @property integer $pid
 * @property integer $uid
 * @property string $name
 * @property integer $phone
 * @property string $address
 * @property integer $status
 * @property string $invoice_no
 * @property integer $deliver_at
 * @property integer $over_at
 * @property integer $created_at
 */
class Invoice extends \yii\db\ActiveRecord
{
	const STATUS_fuyi = -1; 
	const STATUS_0 = 0;
	const STATUS_1 = 1;
	const STATUS_2 = 2;

	private $_statusLabel;
	 
    public static function tableName()
    {
        return 'invoice';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'pid', 'uid', 'status', 'deliver_at', 'over_at', 'created_at'], 'integer'],
            [['name', 'address', 'invoice_no'], 'safe'],
			[['phone', 'wuliu'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_id' => 'ID',
            'pid' => '项目名',
            'uid' => '项目发起人',
            'name' => '收货人姓名',
            'phone' => '收货人联系方式',
            'address' => '收货地址',
            'status' => '订单状态',
            'invoice_no' => '物流单号',
            'deliver_at' => '发货时间',
            'over_at' => '收货时间',
            'created_at' => '添加时间',
        ];
    }
	
	public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'pid']);
    }
	
	public function getMember()
    {
        return $this->hasOne(Member::className(), ['id' => 'uid']);
    }
	
    public static function getArrayStatus()
    {
        return [
	self::STATUS_fuyi => Yii::t('app','进行中'),
            self::STATUS_0 => Yii::t('app', '待发货'),
            self::STATUS_1 => Yii::t('app', '待收货'),
            self::STATUS_2 => Yii::t('app', '已完成'),
        ];
    }
    public function getStatusLabel()
    {
        if ($this->_statusLabel === null) {
            $statuses = self::getArrayStatus();
            $this->_statusLabel = $statuses[$this->status];
        }
        return $this->_statusLabel;
    }
}
