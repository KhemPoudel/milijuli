<?php
/* @var $this InventoryVMController */
/* @var $model InventoryVM */

$this->breadcrumbs=array(
	'Inventory Vms'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List InventoryVM', 'url'=>array('index')),
	array('label'=>'Manage InventoryVM', 'url'=>array('admin')),
);
?>

<h1>Create InventoryVM</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>