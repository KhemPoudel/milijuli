<?php
/* @var $this InventoryVMController */
/* @var $model InventoryVM */

$this->breadcrumbs=array(
	'Inventory Vms'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List InventoryVM', 'url'=>array('index')),
	array('label'=>'Create InventoryVM', 'url'=>array('create')),
	array('label'=>'View InventoryVM', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage InventoryVM', 'url'=>array('admin')),
);
?>

<h1>Update InventoryVM <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>