<?php
/* @var $this InventoryPMController */
/* @var $model InventoryPM */

$this->breadcrumbs=array(
	'Inventory Pms'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List InventoryPM', 'url'=>array('index')),
	array('label'=>'Manage InventoryPM', 'url'=>array('admin')),
);
?>

<h1>Create InventoryPM</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>