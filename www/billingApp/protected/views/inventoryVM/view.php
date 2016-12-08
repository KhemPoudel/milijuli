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
            <h2>Inventory Value Movement</h2>
        <br>
        <br>
        <div>
            <table>
                <tr>
                    <td width='150' class="left-bordered bottom-bordered top-bordered">
                        <b>Inventory Item</b>
                    </td>
                    <td width='150' class="left-bordered bottom-bordered top-bordered">
                        <b>Opening Balance</b>
                    </td>
                    <td width='150' class="left-bordered bottom-bordered top-bordered">
                        <b>Purchases</b>
                    </td>
                    <td width='150' class="left-bordered bottom-bordered top-bordered right-bordered">
                        <b>Manual Increment</b>
                    </td>
                    <td width='150' class="left-bordered bottom-bordered top-bordered right-bordered">
                        <b>Sales</b>
                    </td>
                    <td width='150' class="left-bordered bottom-bordered top-bordered right-bordered">
                        <b>Closing Balance</b>
                    </td>
                </tr>
                <?php

                $items=InventoryItems::model()->findAll();
                foreach($items as $item){
                    $salesTotal=0;
                    $sales_qty=0;
                    $purchasesTotal=0;
                    $purchases_qty=0;
                    $manualTotal=0;
                    $manual_qty=0;
                    $sales=0;
                    $sales_qty1=0;
                    $purchases=0;
                    $purchases_qty1=0;
                    $manual=0;
                    $manual_qty1=0;

                    $opening_purchases_models=PurchasesInvoices::model()->findAll(array('condition'=>'issue_date<:fromDate',
                        'params'=>array(':fromDate'=>$model->from_date)));
                    foreach($opening_purchases_models as $opening_purchases_model){
                        foreach($opening_purchases_model->purchasesInfos as $purchasesInfos){
                            if($purchasesInfos->item_id==$item->id){
                                $purchases_qty+=$purchasesInfos->qty;
                                $purchasesTotal+=$purchasesInfos->total_amount;
                            }
                        }
                    }

                    $opening_manual_increments=InventoryItemsManualIncrement::model()->findAll(array('condition'=>'issue_date<:fromDate',
                        'params'=>array(':fromDate'=>$model->from_date)));
                    foreach($opening_manual_increments as $manuals){
                        if($manuals->inventory_id==$item->id){
                            $manual_qty+=$manuals->qty;
                            $manualTotal+=$manuals->total_cost;
                        }
                    }
                    $opening_sales_models=SalesInvoices::model()->findAll(array('condition'=>'issue_date<:fromDate',
                        'params'=>array(':fromDate'=>$model->from_date)));
                    foreach($opening_sales_models as $opening_sales_model)
                    {
                        foreach($opening_sales_model->salesInfos as $salesInfos){
                            if($salesInfos->item_id==$item->id){
                                if($purchases_qty!=0 || $manual_qty!=0){
                                    $rate=($purchasesTotal+$manualTotal)/($purchases_qty+$manual_qty);
                                    $salesTotal+=$salesInfos->qty*$rate;
                                }
                                else
                                    $salesTotal+=0;
                            }
                        }
                    }

                    $opening_purchases_models=PurchasesInvoices::model()->findAll(array('condition'=>'issue_date>=:fromDate AND issue_date<=:toDate',
                        'params'=>array(':fromDate'=>$model->from_date,':toDate'=>$model->to_date)));
                    foreach($opening_purchases_models as $opening_purchases_model){
                        foreach($opening_purchases_model->purchasesInfos as $purchasesInfos){
                            if($purchasesInfos->item_id==$item->id){
                                $purchases_qty1+=$purchasesInfos->qty;
                                $purchases+=$purchasesInfos->total_amount;
                            }
                        }
                    }

                    $opening_manual_increments=InventoryItemsManualIncrement::model()->findAll(array('condition'=>'issue_date>=:fromDate AND issue_date<=:toDate',
                        'params'=>array(':fromDate'=>$model->from_date,':toDate'=>$model->to_date)));
                    foreach($opening_manual_increments as $manuals){
                        if($manuals->inventory_id==$item->id){
                            $manual_qty1+=$manuals->qty;
                            $manual+=$manuals->total_cost;
                        }
                    }

                    $opening_sales_models=SalesInvoices::model()->findAll(array('condition'=>'issue_date>=:fromDate AND issue_date<=:toDate',
                        'params'=>array(':fromDate'=>$model->from_date,':toDate'=>$model->to_date)));
                    foreach($opening_sales_models as $opening_sales_model)
                    {
                        foreach($opening_sales_model->salesInfos as $salesInfos){
                            if($salesInfos->item_id==$item->id){
                                if($purchases_qty1!=0 || $manual_qty1!=0){
                                    $rate=($purchases+$manual)/($purchases_qty1+$manual_qty1);
                                    $sales+=$salesInfos->qty*$rate;
                                }
                                else{
                                    $sales+=0;
                                }
                            }
                        }
                    }


                    ?>

                    <tr>
                        <td width='150' class="left-bordered bottom-bordered">
                            <?php echo $item->item_name;?>
                        </td>
                        <td width='150' class="left-bordered bottom-bordered">
                            <?php echo $manualTotal+$purchasesTotal-$salesTotal;?>
                        </td>
                        <td width='150' class="left-bordered bottom-bordered">
                            <?php echo $purchases;?>
                        </td>
                        <td width='150' class="left-bordered bottom-bordered">
                            <?php echo $manual;?>
                        </td>
                        <td width='150' class="left-bordered bottom-bordered">
                            <?php echo $sales;?>
                        </td>
                        <td width='150' class="left-bordered bottom-bordered right-bordered">
                            <?php echo $manualTotal+$purchasesTotal-$salesTotal+$purchases+$manual-$sales;?>
                        </td>
                    </tr>
                     <?php
                }?>




            </table>
        </div>
    </div>

    <div class="" style="margin-left: 88%;">
        <button type="button" id="print-btn" class="btn-large btn-info">Print</button>
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
