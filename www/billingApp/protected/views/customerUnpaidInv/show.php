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
                        <b>SalesInvoice</b>
                    </td>
                    <td width='150' class="left-bordered bottom-bordered top-bordered">
                        <b>Invoice Total</b>
                    </td>
                    <td width='150' class="left-bordered bottom-bordered top-bordered right-bordered">
                        <b>Balance due</b>
                    </td>
                </tr>
                <?php
                $unpaid_invoices=SalesInvoices::model()->findAll(array('condition'=>'customer_id=:customer AND issue_date<=:date',
                    'params'=>array(':customer'=>$customer,':date'=>$date)));
                $total=0;
                foreach($unpaid_invoices as $unpaid_invoice)
                {
                    if($unpaid_invoice->balance>$unpaid_invoice->credited){
                        ?>
                        <tr>
                            <td class="left-bordered bottom-bordered">
                                <?php echo $unpaid_invoice->issue_date;?>
                            </td>
                            <td class="left-bordered bottom-bordered">
                                <?php echo $unpaid_invoice->id;?>
                            </td>
                            <td class="left-bordered bottom-bordered">
                                <?php echo $unpaid_invoice->total_amount;?>
                            </td>
                            <td class="left-bordered bottom-bordered right-bordered">
                                <?php echo $unpaid_invoice->balance-$unpaid_invoice->credited;?>
                            </td>

                        </tr>
                        <?php
                        $total+=($unpaid_invoice->balance-$unpaid_invoice->credited);
                    }
                }
                ?>
                <tr>
                    <td colspan="2"></td>
                    <td class="left-bordered bottom-bordered right-bordered">Total Due</td>
                    <td class="left-bordered bottom-bordered right-bordered"><?php echo $total;?></td>
                </tr>

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
