<?php
/* @var $this InventoryItemsManualIncrementController */
/* @var $model InventoryItemsManualIncrement */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'inventory-items-manual-increment-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model,'issue_date'); ?>
        <?php

        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            'name'=>'InventoryItemsManualIncrement[issue_date]',
            'model'=>$model,
            'attribute'=>'issue_date',

            'options'=>array(
                'showAnim'=>'fold',
                'dateFormat'=>'yy-mm-dd',

            ),
            'htmlOptions'=>array(
                'style'=>'height:20px;',
                'value'=>CTimestamp::formatDate('Y-m-d'),
            ),
        ));
        ?>

        <?php echo $form->error($model,'issue_date'); ?>
    </div>


	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textField($model,'description',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'cp'); ?>
		<?php echo $form->textField($model,'cp'); ?>
		<?php echo $form->error($model,'cp'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'qty'); ?>
		<?php echo $form->textField($model,'qty'); ?>
		<?php echo $form->error($model,'qty'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'total_cost'); ?>
        <?php echo $form->textField($model,'total_cost',array('readOnly'=>true)); ?>
		<?php echo $form->error($model,'total_cost'); ?>
	</div>

    <div class="row buttons">
        <?php echo CHtml::submitButton(
            $model->isNewRecord ? 'Create' : 'Update',
            array(
                'class'=>'btn btn-success'
            )
        ); ?>

        <?php
        if(!$model->isNewRecord)
            echo CHtml::button('Delete',
                array(
                    'submit'=>array(
                        'delete',
                        'id'=>$model->id
                    ),
                    'class'=>'btn btn-danger',
                    'confirm'=>'Are you sure you want to delete this inventory item??'
                )
            );
        ?>

    </div>
<?php $this->endWidget(); ?>

</div><!-- form -->
<script type="text/javascript">
    $(document).ready(function(){
        $('#InventoryItemsManualIncrement_cp').focus(function(){
            startCalc();
        });
        $('#InventoryItemsManualIncrement_qty').focus(function(){
            startCalc();
        });
        $('#InventoryItemsManualIncrement_cp').blur(function(){
            stopCalc();
        });
        $('#InventoryItemsManualIncrement_qty').blur(function(){
            stopCalc();
        });
        function startCalc()
        {
            interval=setInterval(function(){
                var qty=$('#InventoryItemsManualIncrement_qty').val();
                var cp=$('#InventoryItemsManualIncrement_cp').val();
                $('#InventoryItemsManualIncrement_total_cost').val(qty*cp);
            },1);
        }
        function stopCalc()
        {
            clearInterval(interval);
        }
    });
</script>