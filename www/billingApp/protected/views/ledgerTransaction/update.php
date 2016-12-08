<?php
/* @var $this LedgerTransactionController */
/* @var $model LedgerTransaction */

$this->breadcrumbs=array(
	'Ledger Transactions'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List LedgerTransaction', 'url'=>array('index')),
	array('label'=>'Create LedgerTransaction', 'url'=>array('create')),
	array('label'=>'View LedgerTransaction', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage LedgerTransaction', 'url'=>array('admin')),
);
?>

<h1>Update LedgerTransaction <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>