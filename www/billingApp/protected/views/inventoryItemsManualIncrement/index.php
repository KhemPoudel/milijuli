<?php
/* @var $this InventoryItemsManualIncrementController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Inventory Items Manual Increments',
);

$this->menu=array(
	array('label'=>'Create InventoryItemsManualIncrement', 'url'=>array('create')),
	array('label'=>'Manage InventoryItemsManualIncrement', 'url'=>array('admin')),
);
?>

<h1>Inventory Items Manual Increments</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
