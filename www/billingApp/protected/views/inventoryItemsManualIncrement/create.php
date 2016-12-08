<?php
/* @var $this InventoryItemsManualIncrementController */
/* @var $model InventoryItemsManualIncrement */

$this->breadcrumbs=array(
	'Inventory Items Manual Increments'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List InventoryItemsManualIncrement', 'url'=>array('index')),
	array('label'=>'Manage InventoryItemsManualIncrement', 'url'=>array('admin')),
);
?>

<h2>Add Stocks for <span style="color: #1f6377;" ><?php echo  $inventoryModel->item_name; ?></span></h2>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>