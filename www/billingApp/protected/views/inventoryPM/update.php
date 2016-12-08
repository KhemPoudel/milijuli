<?php
/* @var $this InventoryPMController */
/* @var $model InventoryPM */

$this->breadcrumbs=array(
	'Inventory Pms'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List InventoryPM', 'url'=>array('index')),
	array('label'=>'Create InventoryPM', 'url'=>array('create')),
	array('label'=>'View InventoryPM', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage InventoryPM', 'url'=>array('admin')),
);
?>

<h1>Update InventoryPM <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>