<?php if(Yii::app()->user->hasFlash('invoice_deletion')):?>
    <div class="alert alert-warning alert-dismissible info" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>Yeah</strong> <?php echo Yii::app()->user->getFlash('invoice_deletion');?>
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
                '<button class="btn btn-default btn-sm"><span class="btn-label-style"> Add New Invoice</span></button>',
                $this->createAbsoluteUrl('create')
            );
            ?>
        </th>


        <th>
            <form action="<?php echo $this->createAbsoluteUrl('index');?>" method="post">
                <input type="text" id="search_params" name="search_params">
                <button type="submit" class="btn btn-default" style="margin-top: -10px;">Search</button>
            </form>
        </th>
    </tr>
    <tr>
        <th>
            Edit
        </th>
        <th>
            View
        </th>
        <th>
            Issue date
        </th>
        <th>
            #
        </th>
        <th>
            Customer
        </th>
        <th>
            Invoice total
        </th>
        <th>
            Balance Due
        </th>
        <th>
            Status
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
            </td>
            <td>
                <?php echo CHtml::link(
                    '<button class="btn btn-default">
                    <span class="btn-label-style">View</span>
                    </button>',
                    array(
                        'view',
                        'id'=>$model->id)
                );
                ?>
            </td>
            <td>
                <?php
                echo $model->issue_date;
                ?>
            </td>
            <td>
                <?php
                echo $model->id;
                ?>
            </td>
            <td>
                <?php
                $customer=$model->customer;
                echo $customer->customerName;
                ?>
            </td>
            <td>
                <?php
                echo $model->total_amount;
                ?>
            </td>
            <td>
                <?php
                echo CHtml::link($model->balance-$model->credited,$this->createAbsoluteUrl('salesInvoices/viewTransaction',array('id'=>$model->id)));
                ?>
            </td>
            <td>
                <?php
                    if(($model->balance-$model->credited)==0){

                        ?>
                        <span class="label label-success">Paid in full</span>
                    <?php }

                    else{
                        ?>
                        <span class="label label-warning">Due Remaining</span>
                    <?php
                    }

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
