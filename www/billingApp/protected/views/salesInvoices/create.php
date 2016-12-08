
<?php
/* @var $this SalesInvoicesController */
/* @var $model SalesInvoices */

?>

<h3 style="background-color: #9d9d9d;margin: -1% 0 -0.0% 0;padding: 1% 0 1% 1%;border: 1px inset #e1e1e1;">Sales Invoices</h3>
<?php if(Yii::app()->user->hasFlash('item_less')):?>
    <div class="alert alert-warning alert-dismissible info" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>Yeah</strong> <?php echo Yii::app()->user->getFlash('item_less');?>
    </div>
<?php endif; ?>
<?php $this->renderPartial('_form', array('model1'=>$model1,'model2'=>$model2,'receivedModel'=>$receivedModel,'errors'=>$errors,'mode'=>$mode)); ?>
<?php
Yii::app()->clientScript->registerScript(
    'myHideEffect',
    '$(".info").animate({opacity: 1.0}, 3000).fadeOut("slow");',
    CClientScript::POS_READY
);
?>