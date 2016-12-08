<?php
$modelSalesInfos=$model->salesInfos;
?>
    <div class="btn-group" style="margin-top: -2%;">
        <?php echo CHtml::link('<button class="btn btn-default"><span class="btn-label-style">Edit</span></button>',array('update','id'=>$model->id)); ?>
        <button type="button" id="print-btn" class="btn">Print</button>
        <?php echo CHtml::link('<button class="btn btn-default"><span class="btn-label-style">Receive Money</span></button>',array('moneyReceived/create','id'=>$model->id)); ?>
    </div>
<div id="printable_view" style="background-color: #ffffff;width: 50%;height: 75%;margin-left: 25%;">
    <div style="font-family: 'Baskerville';text-align: center;">
        <h3>MILIJULI COPY UDHYOG</h3>
        <h4 style="margin-top: -1%;">Srijana Chowk, Pokhara</h4>
        <h5 style="margin-top: -0.5%;">Contact: 9812345670</h5>
    </div>
    <div style="float: right;font-family: calibri;margin-right: 5%;">
        <b>Issue Date</b><br>
        <span><?php echo $model->issue_date;?></span><br><br>
        <b>Invoice Number</b><br>
        <span><?php echo $model->id;?></span>
    </div>
    <br>
    <div style="font-family: calibri;line-break: loose;">
        <b>To:</b>
        <span><?php $customerModel= Customers::model()->findByPk($model->customer_id);
            echo $customerModel->customerName;
            ?></span><br>
        <b>PAN Address:</b> <span><?php echo $customerModel->PANNumber;?></span><br>
        <b>Billing Address:</b><span><?php echo $customerModel->BillingAddress;?></span>
    </div>
    <br>
    <br>
    <div>
        <table>
            <tr>
                <td width='150' class="left-bordered bottom-bordered top-bordered">
                   <b>S.No.</b>
                </td>
                <td width='150' class="left-bordered bottom-bordered top-bordered">
                    <b>Items</b>
                </td>
                <td width='150' class="left-bordered bottom-bordered top-bordered">
                    <b>Unit Price</b>
                </td>
                <td width='150' class="left-bordered bottom-bordered top-bordered">
                    <b>Qty</b>
                </td>
                <td width='150' class="left-bordered bottom-bordered top-bordered right-bordered">
                    <b>Amount</b>
                </td>
            </tr>
            <?php foreach($modelSalesInfos as $serialNum=>$indModel)
            {
                ?>
                <tr>
                    <td class="left-bordered bottom-bordered">
                        <?php echo ++$serialNum;?>
                    </td>
                    <td class="left-bordered bottom-bordered">
                        <?php echo InventoryItems::model()->findByPk($indModel->item_id)->item_name;?>
                    </td>
                    <td class="left-bordered bottom-bordered">
                        <?php echo $indModel->unit_price;?>
                    </td>
                    <td class="left-bordered bottom-bordered">
                        <?php echo $indModel->qty;?>
                    </td>
                    <td class="left-bordered bottom-bordered right-bordered">
                        <?php echo $indModel->total_amount;?>
                    </td>
                </tr>
            <?php
            }
            ?>
            <tr></tr>
            <tr>
                <td colspan="3"></td>
                <td class="left-bordered bottom-bordered right-bordered">Total Amount</td>
                <td class="left-bordered bottom-bordered right-bordered"><?php echo $model->total_amount;?></td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td class="left-bordered bottom-bordered right-bordered">Amount Accredited</td>
                <td class="left-bordered bottom-bordered right-bordered"><?php echo $model->total_amount+$model->credited-$model->balance; ?></td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td class="left-bordered bottom-bordered right-bordered">Balance Due</td>
                <td class="left-bordered bottom-bordered right-bordered"><?php echo $model->balance-$model->credited; ?></td>
            </tr>
        </table>
    </div>
    <div>
        <table style="float: right;border-left:2px inset #000000;border-right:2px inset #000000;border-bottom:2px inset #000000;">


        </table>
    </div>

</div>
<script type="text/javascript">
    $('#print-btn').click(function(){
        var divToPrint = document.getElementById('printable_view');
        var htmlToPrint = '' +
            '<style type="text/css">' +
        '.left-bordered{'+
            'border-left:2px inset #000000;'+
        '}'+
        '.bottom-bordered{'+
            'border-bottom: 2px inset #000000;'+
        '}'+
        '.right-bordered{'+
            'border-right: 2px inset #000000;'+
        '}'+
        '.top-bordered{'+
            'border-top: 2px inset #000000;'+
        '}'+
            '</style>';
        htmlToPrint += divToPrint.innerHTML;
        var newWin = window.open("");
        newWin.document.write(htmlToPrint);
        newWin.print();
        newWin.close();
    });
</script>
