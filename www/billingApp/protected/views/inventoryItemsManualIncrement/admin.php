<?php
/* @var $this InventoryItemsManualIncrementController */
/* @var $model InventoryItemsManualIncrement */

$this->breadcrumbs=array(
	'Inventory Items Manual Increments'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List InventoryItemsManualIncrement', 'url'=>array('index')),
	array('label'=>'Create InventoryItemsManualIncrement', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#inventory-items-manual-increment-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Inventory Items Manual Increments</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'inventory-items-manual-increment-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'issue_date',
		'inventory_id',
		'item_name',
		'item_identifier',
		'description',
		/*
		'sp',
		'cp',
		'qty',
		'total_cost',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
