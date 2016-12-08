<div style="padding: 0% 5% 5% 5%;">
    <div style="">
        <table class="">
            <tr style="padding: 2%;">
                <td colspan="2"><b><h4>Customer Transactions</h4></b></td>
            </tr>
            <form action="#" method="post">
                <tr>
                    <td><b>From:</b></td>
                    <td>
                        <?php
                        $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                            'name'=>'datepicker-fromDate',
                            'options'=>array(
                                'dateFormat' => 'yy-mm-dd',
                                'showAnim'=>'slideDown',//'slide','fold','slideDown','fadeIn','blind','bounce','clip','drop'
                                'showButtonPanel'=>true,
                            ),
                            'htmlOptions'=>array(
                                'id'=>'date-input',

                            ),
                        ));
                        ?>
                    </td>
                </tr>
                <tr>
                    <td><b>To:</b></td>
                    <td>
                        <?php
                        $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                            'name'=>'datepicker-toDate',
                            'options'=>array(
                                'dateFormat' => 'yy-mm-dd',
                                'showAnim'=>'slideDown',//'slide','fold','slideDown','fadeIn','blind','bounce','clip','drop'
                                'showButtonPanel'=>true,
                            ),
                            'htmlOptions'=>array(

                            ),
                        ));
                        ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <button name="submit" type="submit" id="generate-btn" class="btn btn-default">Generate Report</button>
                    </td>
                </tr>
            </form>
        </table>

    </div>



    <?php
    if(isset($_POST['submit']))
    {
        $fromDate=$_POST['datepicker-fromDate'];
        $toDate=$_POST['datepicker-toDate'];
        if(!empty($fromDate) && !empty($toDate)){
            echo '<table class="table table-bordered">
            <tr>
                <th>View</th>
                <th>Customer</th>
                <th>Transactions</th>
                <th>Balance</th>
            </tr>';
            $count=array();
            $amount=array();
            $customers=Customers::model()->findAll();
            foreach($customers as $customer){
                $count[$customer->id]=0;
                $amount[$customer->id]=0;
                $models=SalesInvoices::model()->findAll(array('condition'=>'customer_id=:customer AND issue_date<=:toDate AND issue_date>=:fromDate',
                    'params'=>array(':customer'=>$customer->id,':toDate'=>$toDate,':fromDate'=>$fromDate)));
                $cash_models=MoneyReceived::model()->findAll(array('condition'=>'customer_id=:customer AND received_date<=:toDate AND received_date>=:fromDate',
                    'params'=>array(':customer'=>$customer->id,':toDate'=>$toDate,':fromDate'=>$fromDate)));
                $count[$customer->id]=sizeof($models)+sizeof($cash_models);
                $invoices=SalesInvoices::model()->findAll(array('condition'=>'customer_id=:customer','params'=>array(':customer'=>$customer->id)));
                $cashes=MoneyReceived::model()->findAll(array('condition'=>'customer_id=:customer','params'=>array(':customer'=>$customer->id)));
                foreach($invoices as $invoice)
                    $amount[$customer->id]+=$invoice->total_amount;
                foreach($cashes as $cash)
                    $amount[$customer->id]-=$cash->amount;
                    ?>
                <?php
                if($count[$customer->id]>0){
                  ?>
                    <tr>
                        <td><?php  echo CHtml::link(
                                '<button class="btn btn-default btn-sm"><span class="btn-label-style">View</span></button>',
                                $this->createAbsoluteUrl('show',array('customer'=>$customer->id,'toDate'=>$toDate,'fromDate'=>$fromDate))
                            ); ?></td>
                        <td><?php echo $customer->customerName; ?></td>
                        <td><?php echo $count[$customer->id]; ?></td>
                        <td><?php echo $amount[$customer->id]; ?></td>
                    </tr>
                <?php
                }
                ?>

                <?php

            } ?>
            </table>
        <?php
        }
        else
        {
            echo 'Please enter date';
        }
        ?>
    <?php
    }
    ?>
</div>
