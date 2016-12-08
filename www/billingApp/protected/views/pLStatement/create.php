<?php
/* @var $this PLStatementController */
/* @var $model PLStatement */

$this->breadcrumbs=array(
	'Plstatements'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List PLStatement', 'url'=>array('index')),
	array('label'=>'Manage PLStatement', 'url'=>array('admin')),
);
?>

<h1>Create PLStatement</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>