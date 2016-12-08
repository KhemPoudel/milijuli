<?php
/* @var $this SalesInvoicesController */
/* @var $model SalesInvoices */

?>

<h1>Manage Sales Invoices</h1>



<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'sales-invoices-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'issue_date',
		'customer_id',
		'total_amount',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
