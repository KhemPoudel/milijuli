<?php
/* @var $this InventoryItemsManualIncrementController */
/* @var $model InventoryItemsManualIncrement */

$this->breadcrumbs=array(
	'Inventory Items Manual Increments'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List InventoryItemsManualIncrement', 'url'=>array('index')),
	array('label'=>'Create InventoryItemsManualIncrement', 'url'=>array('create')),
	array('label'=>'Update InventoryItemsManualIncrement', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete InventoryItemsManualIncrement', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage InventoryItemsManualIncrement', 'url'=>array('admin')),
);
?>

<h1>View InventoryItemsManualIncrement #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'issue_date',
		'inventory_id',
		'item_name',
		'item_identifier',
		'description',
		'sp',
		'cp',
		'qty',
		'total_cost',
	),
)); ?>
