<?php

/**
 * This is the model class for table "tbl_inventory_items_manual_increment".
 *
 * The followings are the available columns in table 'tbl_inventory_items_manual_increment':
 * @property integer $id
 * @property string $issue_date
 * @property integer $inventory_id
 * @property string $item_name
 * @property string $item_identifier
 * @property string $description
 * @property double $sp
 * @property double $cp
 * @property integer $qty
 * @property double $total_cost
 *
 * The followings are the available model relations:
 * @property InventoryItems $inventory
 */
class InventoryItemsManualIncrement extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_inventory_items_manual_increment';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('inventory_id, qty', 'numerical', 'integerOnly'=>true),
			array('sp, cp, total_cost', 'numerical'),
			array('item_name, description', 'length', 'max'=>50),
			array('item_identifier', 'length', 'max'=>20),
			array('issue_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, issue_date, inventory_id, item_name, item_identifier, description, sp, cp, qty, total_cost', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'inventory' => array(self::BELONGS_TO, 'InventoryItems', 'inventory_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'issue_date' => 'Issue Date',
			'inventory_id' => 'Inventory',
			'item_name' => 'Item Name',
			'item_identifier' => 'Item Identifier',
			'description' => 'Description',
			'sp' => 'Sp',
			'cp' => 'Cp',
			'qty' => 'Qty',
			'total_cost' => 'Total Cost',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('issue_date',$this->issue_date,true);
		$criteria->compare('inventory_id',$this->inventory_id);
		$criteria->compare('item_name',$this->item_name,true);
		$criteria->compare('item_identifier',$this->item_identifier,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('sp',$this->sp);
		$criteria->compare('cp',$this->cp);
		$criteria->compare('qty',$this->qty);
		$criteria->compare('total_cost',$this->total_cost);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return InventoryItemsManualIncrement the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
