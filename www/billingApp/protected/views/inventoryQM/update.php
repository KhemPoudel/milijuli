<?php
/* @var $this InventoryQMController */
/* @var $model InventoryQM */

$this->breadcrumbs=array(
	'Inventory Qms'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List InventoryQM', 'url'=>array('index')),
	array('label'=>'Create InventoryQM', 'url'=>array('create')),
	array('label'=>'View InventoryQM', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage InventoryQM', 'url'=>array('admin')),
);
?>

<h1>Update InventoryQM <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>