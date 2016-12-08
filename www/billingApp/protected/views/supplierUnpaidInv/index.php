<div style="padding: 0% 5% 5% 5%;">
    <div style="">
        <table class="">
            <tr style="padding: 2%;">
                <td colspan="2"><b><h4>Unpaid Invoices</h4></b></td>
            </tr>
            <form action="#" method="post">
                <tr>
                    <td><b>Until:</b></td>
                    <td>
                        <?php
                        $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                            'name'=>'datepicker-untilDate',
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
        $date=$_POST['datepicker-untilDate'];
        if(!empty($date)){
            echo '<table class="table table-bordered">
            <tr>
                <th>View</th>
                <th>Supplier</th>
                <th>Unpaid Invoices</th>
                <th>Total</th>
            </tr>';
            $count=array();
            $amount=array();
            $suppliers=Suppliers::model()->findAll();
            foreach($suppliers as $supplier){
                $count[$supplier->id]=0;
                $amount[$supplier->id]=0;
                $models=PurchasesInvoices::model()->findAll(array('condition'=>'supplier_id=:supplier AND issue_date<=:date',
                    'params'=>array(':supplier'=>$supplier->id,':date'=>$date)));
                foreach($models as $model){
                    if($model->balance>$model->credited){
                        $count[$supplier->id]++;
                        $amount[$supplier->id]+=$model->balance-$model->credited;
                    }
                }
                if($count[$supplier->id]>0){
                    ?>
                    <tr>
                        <td><?php  echo CHtml::link(
                                '<button class="btn btn-default btn-sm"><span class="btn-label-style">View</span></button>',
                                $this->createAbsoluteUrl('show',array('supplier'=>$supplier->id,'date'=>$date))
                            ); ?></td>
                        <td><?php echo $supplier->supplierName; ?></td>
                        <td><?php echo $count[$supplier->id]; ?></td>
                        <td><?php echo $amount[$supplier->id]; ?></td>
                    </tr>
                <?php
                }
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
