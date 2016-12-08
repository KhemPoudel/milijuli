<div style="padding: 0% 5% 5% 5%;">
    <div style="">
        <table class="">
            <tr style="padding: 2%;">
                <td colspan="2"><b><h4>Supplier Transactions</h4></b></td>
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
                <th>Supplier</th>
                <th>Transactions</th>
                <th>Balance</th>
            </tr>';
            $count=array();
            $amount=array();
            $suppliers=Suppliers::model()->findAll();
            foreach($suppliers as $supplier){
                $count[$supplier->id]=0;
                $amount[$supplier->id]=0;
                $models=PurchasesInvoices::model()->findAll(array('condition'=>'supplier_id=:supplier AND issue_date<=:toDate AND issue_date>=:fromDate',
                    'params'=>array(':supplier'=>$supplier->id,':toDate'=>$toDate,':fromDate'=>$fromDate)));
                $cash_models=MoneyPaid::model()->findAll(array('condition'=>'supplier_id=:supplier AND paid_date<=:toDate AND paid_date>=:fromDate',
                    'params'=>array(':supplier'=>$supplier->id,':toDate'=>$toDate,':fromDate'=>$fromDate)));
                $count[$supplier->id]=sizeof($models)+sizeof($cash_models);
                $invoices=PurchasesInvoices::model()->findAll(array('condition'=>'supplier_id=:supplier','params'=>array(':supplier'=>$supplier->id)));
                $cashes=MoneyPaid::model()->findAll(array('condition'=>'supplier_id=:supplier','params'=>array(':supplier'=>$supplier->id)));
                foreach($invoices as $invoice)
                    $amount[$supplier->id]+=$invoice->total_amount;
                foreach($cashes as $cash)
                    $amount[$supplier->id]-=$cash->amount;
                ?>
                <?php
                if($count[$supplier->id]>0){
                ?>
                    <tr>
                        <td><?php  echo CHtml::link(
                                '<button class="btn btn-default btn-sm"><span class="btn-label-style">View</span></button>',
                                $this->createAbsoluteUrl('show',array('supplier'=>$supplier->id,'toDate'=>$toDate,'fromDate'=>$fromDate))
                            ); ?></td>
                        <td><?php echo $supplier->supplierName; ?></td>
                        <td><?php echo $count[$supplier->id]; ?></td>
                        <td><?php echo $amount[$supplier->id]; ?></td>
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
