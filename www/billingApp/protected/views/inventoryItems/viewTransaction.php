<table class="table table-bordered">
    <tr>
        <th>Edit</th>
        <th>View</th>
        <th>Date</th>
        <th>Transacation</th>
        <th>#</th>
        <th>Description</th>
        <th>Contact</th>
        <th>Amount</th>
        <th>Balance</th>
    </tr>

        <?php
        $balance=0;
        $PurchasesInfos=PurchasesInfo::model()->findAll(array('condition'=>'item_id=:item','params'=>array(':item'=>$id)));
        foreach($PurchasesInfos as $PurchasesInfo){
            ?>
            <tr>
            <td><?php
                echo CHtml::link(
                    '<button class="btn btn-default btn-sm"><span class="btn-label-style">Edit</span></button>',
                    $this->createAbsoluteUrl('purchasesInvoices/update',array('id'=>$PurchasesInfo->purchasesInvoice->id))
                );
                ?>
            </td>
            <td><?php
                echo CHtml::link(
                    '<button class="btn btn-default btn-sm"><span class="btn-label-style">View</span></button>',
                    $this->createAbsoluteUrl('purchasesInvoices/view',array('id'=>$PurchasesInfo->purchasesInvoice->id))
                );
                ?>
            </td>
            <td>
                <?php echo $PurchasesInfo->purchasesInvoice->issue_date?>
            </td>
            <td>
                Purchases Invoice
            </td>
            <td>
                <?php echo $PurchasesInfo->purchasesInvoice->id;?>
            </td>
            <td>

            </td>
            <td>
                <?php echo $PurchasesInfo->purchasesInvoice->supplier_id;?>
            </td>
            <td>
                <?php echo $PurchasesInfo->qty;?>
            </td>
            <td>
                <?php
                $balance+=$PurchasesInfo->qty;
                echo $balance;
                ?>
            </td>
                </tr>
        <?php
        }
    $manualIncrements=InventoryItemsManualIncrement::model()->findAll(array('condition'=>'inventory_id=:inventory','params'=>array(':inventory'=>$id)));
    foreach($manualIncrements as $indIncrements){
        ?>
        <tr>
            <td><?php
                echo CHtml::link(
                    '<button class="btn btn-default btn-sm"><span class="btn-label-style">Edit</span></button>',
                    $this->createAbsoluteUrl('inventoryItemsManualIncrement/update',array('id'=>$indIncrements->id))
                );
                ?>
            </td>
            <td><?php
                echo CHtml::link(
                    '<button class="btn btn-default btn-sm"><span class="btn-label-style">View</span></button>',
                    $this->createAbsoluteUrl('inventoryItemsManualIncrement/view',array('id'=>$indIncrements->id))
                );
                ?>
            </td>
            <td>
                <?php echo $indIncrements->issue_date?>
            </td>
            <td>
                Manual Increment
            </td>
            <td>
                <?php echo $indIncrements->id;?>
            </td>
            <td>

            </td>
            <td>
                <?php echo '##';?>
            </td>
            <td>
                <?php echo $indIncrements->qty;?>
            </td>
            <td>
                <?php
                $balance+=$indIncrements->qty;
                echo $balance;
                ?>
            </td>
        </tr>
    <?php
    }

    $SalesInfos=SalesInfo::model()->findAll(array('condition'=>'item_id=:item','params'=>array(':item'=>$id)));
    foreach($SalesInfos as $SalesInfo){
        ?>
        <tr>
            <td><?php
                echo CHtml::link(
                    '<button class="btn btn-default btn-sm"><span class="btn-label-style">Edit</span></button>',
                    $this->createAbsoluteUrl('salesInvoices/update',array('id'=>$SalesInfo->salesInvoice->id))
                );
                ?>
            </td>
            <td><?php
                echo CHtml::link(
                    '<button class="btn btn-default btn-sm"><span class="btn-label-style">View</span></button>',
                    $this->createAbsoluteUrl('SalesInvoices/view',array('id'=>$SalesInfo->salesInvoice->id))
                );
                ?>
            </td>
            <td>
                <?php echo $SalesInfo->salesInvoice->issue_date?>
            </td>
            <td>
                Sales Invoice
            </td>
            <td>
                <?php echo $SalesInfo->salesInvoice->id;?>
            </td>
            <td>

            </td>
            <td>
                <?php echo $SalesInfo->salesInvoice->customer_id;?>
            </td>
            <td style="color: red;">
                <?php echo '-'.$SalesInfo->qty;?>
            </td>
            <td>
                <?php
                $balance-=$SalesInfo->qty;
                if($balance<0){
            ?>
                <span style="color: red;"><?php echo $balance;}?></span>
                <?php
                if($balance>=0)
                    echo $balance;
                ?>
            </td>
        </tr>
    <?php
    }
    ?>

</table>