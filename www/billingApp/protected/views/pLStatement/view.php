<?php
?>

<div id="" style="background-color: #ffffff;width: 50%;height: 75%;margin-left: 25%;">
    <div id="printable_view">
        <div style="font-family: 'Baskerville';text-align: center;">
            <h3>MILIJULI PAPER HOUSE</h3>
            <h4 style="margin-top: -1%;">Pokhara-8, Simalchour</h4>
            <h5 style="margin-top: -0.5%;">Contact: 9804106301</h5>
        </div>
        <div style="float: right;font-family: calibri;margin-right: 5%;">
            <b>Issue Date</b><br>
            <span><?php echo date('Y-m-d');?></span><br><br>
        </div>
        <br>

        <div style="font-family: calibri;line-break: loose;">
            <h3>Profit Loss Statement</h3>
            <?php echo '<h4>For the Period of ',$model->from_date,' to',$model->to_date,'</h4>';?>
            <br>
            <br>
            <div>
                <table>
                    <?php

                    $all_purchases=0;
                    $all_sales=0;
                    $items=InventoryItems::model()->findAll();
                    foreach($items as $item){
                        $salesTotal=0;
                        $sales_qty=0;
                        $purchasesTotal=0;
                        $purchases_qty=0;
                        $manualTotal=0;
                        $manual_qty=0;

                        $opening_purchases_models=PurchasesInvoices::model()->findAll(array('condition'=>'issue_date<=:toDate',
                            'params'=>array(':toDate'=>$model->to_date)));
                        foreach($opening_purchases_models as $opening_purchases_model){
                            foreach($opening_purchases_model->purchasesInfos as $purchasesInfos){
                                if($purchasesInfos->item_id==$item->id){
                                    $purchases_qty+=$purchasesInfos->qty;
                                    $purchasesTotal+=$purchasesInfos->total_amount;
                                }
                            }
                        }

                        $opening_manual_increments=InventoryItemsManualIncrement::model()->findAll(array('condition'=>'issue_date<=:toDate',
                            'params'=>array(':toDate'=>$model->from_date)));
                        foreach($opening_manual_increments as $manuals){
                            if($manuals->inventory_id==$item->id){
                                $manual_qty+=$manuals->qty;
                                $manualTotal+=$manuals->total_cost;
                            }
                        }

                        $opening_sales_models=SalesInvoices::model()->findAll(array('condition'=>'issue_date>=:fromDate AND issue_date<=:toDate',
                            'params'=>array(':fromDate'=>$model->from_date,':toDate'=>$model->to_date)));
                        foreach($opening_sales_models as $opening_sales_model)
                        {
                            foreach($opening_sales_model->salesInfos as $salesInfos){
                                if($salesInfos->item_id==$item->id){
                                    $sales_qty+=$salesInfos->qty;
                                    $salesTotal+=$salesInfos->total_amount;
                                }
                            }
                        }
                        if($purchases_qty!=0 || $manual_qty!=0){
                            $purchase_rate=($purchasesTotal+$manualTotal)/($purchases_qty+$manual_qty);
                            $purchase_amount_of_item_sold=$sales_qty*$purchase_rate;
                        }
                        else{
                            $purchase_amount_of_item_sold=0;
                        }
                        $all_purchases+=$purchase_amount_of_item_sold;
                        $all_sales+=$salesTotal;
                        ?>

                    <?php
                    }?>
                    <tr>
                        <td colspan="3" width='150' class="">
                            <b>Income</b>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" width='150' class="">
                            Sales Of Inventory Items
                        </td>
                        <td width='150' class="">
                            <?php echo $all_sales;?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" width='150' class="bottom-bordered top-bordered left-bordered">
                            <b>Total Income</b>
                        </td>
                        <td width='150' class="bottom-bordered top-bordered right-bordered">
                            <?php echo $all_sales;?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" width='150' class="">
                            <b>Expenses</b>
                        </td>
                    </tr>

                    <tr>
                        <td width='150' class="">
                            Purchases
                        </td>
                        <td></td>
                        <td width='150' class="">
                            <?php echo $all_purchases;?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" width='150' class="bottom-bordered top-bordered left-bordered">
                            <b>Total Expenses</b>
                        </td>
                        <td width='150' class="bottom-bordered top-bordered right-bordered">
                            <?php echo $all_purchases;?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" width='150' class="">
                            <b>Net Profit(Loss)</b>
                        </td>
                        <td width='150' class="">
                            <?php if(($all_sales-$all_purchases)>=0){
                                echo $all_sales-$all_purchases;
                            }
                            else
                                echo '(',$all_purchases-$all_sales,')';
                            ;?>
                        </td>
                    </tr>



                </table>
            </div>
        </div>


    </div>
    <div class="" style="margin-left: 88%;">
        <button type="button" id="print-btn" class="btn-large btn-info">Print</button>
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
