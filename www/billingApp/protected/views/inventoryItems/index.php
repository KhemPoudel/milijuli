<?php if(Yii::app()->user->hasFlash('inv_creation')):?>
    <div class="alert alert-success alert-dismissible info" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>Yeah</strong> <?php echo Yii::app()->user->getFlash('inv_creation');?>
    </div>
<?php endif; ?>
<?php if(Yii::app()->user->hasFlash('inv_update')):?>
    <div class="alert alert-success alert-dismissible info" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>Yeah</strong> <?php echo Yii::app()->user->getFlash('inv_update');?>
    </div>
<?php endif; ?>
<?php if(Yii::app()->user->hasFlash('inv_deletion')):?>
    <div class="alert alert-success alert-dismissible info" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>Yeah</strong> <?php echo Yii::app()->user->getFlash('inv_deletion');?>
    </div>
<?php endif; ?>

<table class="table table-striped table-bordered">
    <tr>
        <th colspan="2">
            <?php
            echo 'Inventory Items';
            ?>
        </th>
        <th colspan="2" style="border-left: none">
            <?php
            echo CHtml::link(
                '<button class="btn btn-default btn-sm"><span class="btn-label-style"> Add New Inventory Item</span></button>',
                $this->createAbsoluteUrl('create')
            );
            ?>
        </th>
        <th>
            <form action="<?php echo $this->createAbsoluteUrl('index');?>" method="post">
                <input type="text" id="search_params" name="search_params">
                <button type="submit" class="btn btn-default" style="margin-top: -10px;">Search</button>
            </form>
    </tr>
    <tr>
        <th>
            Edit
        </th>
        <th>
            Item Name
        </th>
        <th>
            Item Identifier
        </th>
        <th>
            Selling Price
        </th>
        <th>
            Cost Price
        </th>
        <th>
            Quantity
        </th>
        <th>
            Total Cost
        </th>
    </tr>
    <?php foreach($models as $model): ?>
    <tr>
        <td>
            <?php echo CHtml::link(
                '<button class="btn btn-default">
                <span class="btn-label-style">Edit</span>
                </button>',
                array(
                    'update',
                    'id'=>$model->id)
            );
            ?>
            <?php echo CHtml::link(
                '<button class="btn btn-default">
                <span class="btn-label-style">Add Stocks</span>
                </button>',
                array(
                    'InventoryItemsManualIncrement/create',
                    'inventoryId'=>$model->id)
            );
            ?>
        </td>
        <td>
            <?php
            echo $model->item_name;
            ?>
        </td>
        <td>
            <?php
            echo $model->item_identifier;
            ?>
        </td>
        <td>
            <?php
            echo $model->sp;
            ?>
        </td>
        <td>
            <?php
            echo $model->cp;
            ?>
        </td>
        <td>
            <?php
            echo CHtml::link($model->qty,$this->createAbsoluteUrl('inventoryItems/viewTransaction',array('id'=>$model->id)));;
            ?>
        </td>
        <td>
            <?php
            echo $model->total_cost;
            ?>
        </td>
    </tr>
    <?php endforeach ?>
</table>
<?php $this->widget('CLinkPager', array(
    'pages' => $pages,
)) ?>
<?php
Yii::app()->clientScript->registerScript(
'myHideEffect',
'$(".info").animate({opacity: 1.0}, 3000).fadeOut("slow");',
CClientScript::POS_READY
);
?>
