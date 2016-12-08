<?php
/* @var $this PLStatementController */
/* @var $model PLStatement */

$this->breadcrumbs=array(
	'Plstatements'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List PLStatement', 'url'=>array('index')),
	array('label'=>'Create PLStatement', 'url'=>array('create')),
	array('label'=>'View PLStatement', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage PLStatement', 'url'=>array('admin')),
);
?>

<h1>Update PLStatement <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>