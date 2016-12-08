<?php
/* @var $this InventoryQMController */
/* @var $model InventoryQM */

$this->breadcrumbs=array(
	'Inventory Qms'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List InventoryQM', 'url'=>array('index')),
	array('label'=>'Manage InventoryQM', 'url'=>array('admin')),
);
?>

<h1>Create InventoryQM</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>