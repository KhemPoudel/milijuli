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
                <th>Customer</th>
                <th>Unpaid Invoices</th>
                <th>Total</th>
            </tr>';
            $count=array();
            $amount=array();
            $customers=Customers::model()->findAll();
            foreach($customers as $customer){
                $count[$customer->id]=0;
                $amount[$customer->id]=0;
                $models=SalesInvoices::model()->findAll(array('condition'=>'customer_id=:customer AND issue_date<=:date',
                    'params'=>array(':customer'=>$customer->id,':date'=>$date)));
                foreach($models as $model){
                    if($model->balance>$model->credited){
                        $count[$customer->id]++;
                        $amount[$customer->id]+=$model->balance-$model->credited;
                    }
                }
                if($count[$customer->id]>0){
                    ?>
                    <tr>
                        <td><?php  echo CHtml::link(
                                '<button class="btn btn-default btn-sm"><span class="btn-label-style">View</span></button>',
                                $this->createAbsoluteUrl('show',array('customer'=>$customer->id,'date'=>$date))
                            ); ?></td>
                        <td><?php echo $customer->customerName; ?></td>
                        <td><?php echo $count[$customer->id]; ?></td>
                        <td><?php echo $amount[$customer->id]; ?></td>
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
