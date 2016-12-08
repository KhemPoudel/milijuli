<?php
$customer_model=Customers::model()->findByPk($customer);
?>

<div id="" style="background-color: #ffffff;width: 50%;height: 75%;margin-left: 25%;">
    <div id="printable_view">
        <div style="font-family: 'Baskerville';text-align: center;">
            <h3>MILIJULI COPY UDHYOG</h3>
            <h4 style="margin-top: -1%;">Srijana Chowk, Pokhara</h4>
            <h5 style="margin-top: -0.5%;">Contact: 9812345670</h5>
        </div>
        <div style="float: right;font-family: calibri;margin-right: 5%;">
            <b>Issue Date</b><br>
            <span><?php echo date("Y-m-d");?></span><br><br>
        </div>
        <br>

        <div style="font-family: calibri;line-break: loose;">
            <h2>Statement</h2>
            <b>To:</b>
        <span><?php echo $customer;
            ?></span><br>
            <b>PAN Address:</b> <span><?php echo $customer_model->PANNumber;?></span><br>
            <b>Billing Address:</b><span><?php echo $customer_model->BillingAddress;?></span>
        </div>
        <br>
        <br>
        <div>
            <table>
                <tr>
                    <td width='150' class="left-bordered bottom-bordered top-bordered">
                        <b>Date</b>
                    </td>
                    <td width='150' class="left-bordered bottom-bordered top-bordered">
                        <b>Description</b>
                    </td>
                    <td width='150' class="left-bordered bottom-bordered top-bordered">
                        <b>Debit</b>
                    </td>
                    <td width='150' class="left-bordered bottom-bordered top-bordered right-bordered">
                        <b>Credit</b>
                    </td>
                    <td width='150' class="left-bordered bottom-bordered top-bordered right-bordered">
                        <b>Balance</b>
                    </td>
                </tr>
                <?php
                $opening_models=SalesInvoices::model()->findAll(array('condition'=>'customer_id=:customer AND issue_date<:fromDate',
                    'params'=>array(':customer'=>$customer,':fromDate'=>$fromDate)));
                $opening_cash_models=MoneyReceived::model()->findAll(array('condition'=>'customer_id=:customer AND received_date<:fromDate',
                    'params'=>array(':customer'=>$customer,':fromDate'=>$fromDate)));
                $opening_balance=0;
                foreach($opening_models as $opening_model)
                {
                    $opening_balance+=$opening_model->total_amount;
                }
                foreach($opening_cash_models as $opening_cash_model)
                {
                    $opening_balance-=$opening_cash_model->amount;
                }
                if($opening_balance!=0)
                { ?>
                    <tr>
                        <td class="left-bordered bottom-bordered">
                            <?php echo $fromDate;?>
                        </td>
                        <td class="left-bordered bottom-bordered">
                            Opening Balance
                        </td>
                        <td class="left-bordered bottom-bordered">
                            <?php
                            if($opening_balance>0){
                                echo $opening_balance;
                            }
                            ?>
                        </td>

                        <td class="left-bordered bottom-bordered">
                            <?php
                            if($opening_balance<0){
                                echo -1*$opening_balance;
                            }
                            ?>
                        </td>
                        <td class="left-bordered bottom-bordered right-bordered">
                            <?php
                            if($opening_balance<0){
                                echo -1*$opening_balance.'Cr';
                            }
                            else
                                echo $opening_balance.'Dr';
                            ?>
                        </td>
                    </tr>
                <?php }
                $models=SalesInvoices::model()->findAll(array('condition'=>'customer_id=:customer AND issue_date<=:toDate AND issue_date>=:fromDate',
                    'params'=>array(':customer'=>$customer,':toDate'=>$toDate,':fromDate'=>$fromDate)));
                $cash_models=MoneyReceived::model()->findAll(array('condition'=>'customer_id=:customer AND received_date<=:toDate AND received_date>=:fromDate',
                    'params'=>array(':customer'=>$customer,':toDate'=>$toDate,':fromDate'=>$fromDate)));
                $total=$opening_balance;
                foreach($models as $model)
                {
                    ?>
                    <tr>
                        <td class="left-bordered bottom-bordered">
                            <?php echo $model->issue_date;?>
                        </td>
                        <td class="left-bordered bottom-bordered">
                            <?php echo 'Invoice '.$model->id;?>
                        </td>
                        <td class="left-bordered bottom-bordered">
                            <?php echo $model->total_amount;?>
                        </td>
                        <td class="left-bordered bottom-bordered">
                        </td>
                        <?php $total+=$model->total_amount; ?>
                        <td class="left-bordered bottom-bordered right-bordered">
                            <?php
                            if($total>0)
                                echo $total.'Dr';
                            elseif($total<0)
                                echo -1*$total.'Cr';
                            else
                                echo $total;
                            ?>
                        </td>

                    </tr>
                <?php
                }

                ?>
                <?php
                foreach($cash_models as $model)
                {
                    ?>
                    <tr>
                        <td class="left-bordered bottom-bordered">
                            <?php echo $model->received_date;?>
                        </td>
                        <td class="left-bordered bottom-bordered">
                            <?php echo 'Receipt '.$model->id;?>
                        </td>
                        <td class="left-bordered bottom-bordered">
                        </td>
                        <td class="left-bordered bottom-bordered">
                            <?php echo $model->amount;?>
                        </td>

                        <?php $total-=$model->amount; ?>
                        <td class="left-bordered bottom-bordered right-bordered">
                            <?php
                            if($total>0)
                                echo $total.'Dr';
                            elseif($total<0)
                                echo -1*$total.'Cr';
                            else
                                echo $total;
                            ?>
                        </td>
                    </tr>
                <?php
                }

                ?>
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

