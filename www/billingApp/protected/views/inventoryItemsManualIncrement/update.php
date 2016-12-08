<?php
/* @var $this InventoryItemsManualIncrementController */
/* @var $model InventoryItemsManualIncrement */

$this->breadcrumbs=array(
	'Inventory Items Manual Increments'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List InventoryItemsManualIncrement', 'url'=>array('index')),
	array('label'=>'Create InventoryItemsManualIncrement', 'url'=>array('create')),
	array('label'=>'View InventoryItemsManualIncrement', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage InventoryItemsManualIncrement', 'url'=>array('admin')),
);
?>

<h1>Update InventoryItemsManualIncrement <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>