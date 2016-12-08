<?php
/* @var $this LedgerTransactionController */
/* @var $model LedgerTransaction */

$this->breadcrumbs=array(
	'Ledger Transactions'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List LedgerTransaction', 'url'=>array('index')),
	array('label'=>'Manage LedgerTransaction', 'url'=>array('admin')),
);
?>

<h1>Create LedgerTransaction</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>