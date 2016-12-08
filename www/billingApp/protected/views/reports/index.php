
<div style="border: 1px inset #808080;">
    <table class="table">
        <tr>
            <td>
                <div style="" class="text-left">
                    <div style="">
                        <table class="table table-bordered" id="tbl-transaction">
                            <tr>
                                <th style="background-color: #eeeddd;"><b>General Ledger</b></th>
                            </tr>
                            <tr>
                                <td><?php echo CHtml::link('General Ledger Transaction',$this->createAbsoluteUrl('LedgerTransaction/index')); ?></td>
                            </tr>
                            <tr>
                                <td>General Ledger Summary</td>
                            </tr>
                        </table>
                    </div>
                    <div style="">
                        <table class="table table-bordered" id="tbl-transaction">
                            <tr>
                                <th style="background-color: #eeeddd;"><b>Accounts Receivables</b></th>
                            </tr>
                            <tr>
                                <td><?php echo CHtml::link('Customer Statements(Unpaid Invoices)',$this->createAbsoluteUrl('CustomerUnpaidInv/index'));?></td>
                            </tr>
                            <tr>
                                <td><?php echo CHtml::link('Customer Statements(Transactions)',$this->createAbsoluteUrl('CustomerTransactions/index'));?></td>
                            </tr>
                        </table>
                    </div>
                    <div style="">
                        <table class="table table-bordered" id="tbl-transaction">
                            <tr>
                                <th style="background-color: #eeeddd;"><b>Financial Statements</b></th>
                            </tr>
                            <tr>
                                <td><?php echo CHtml::link('Profit Loss Statement',$this->createAbsoluteUrl('PLStatement/index'));?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </td>
            <td>
                <div style="" class="text-right">
                    <div style="">
                        <table class="table table-bordered" id="tbl-transaction">
                            <tr>
                                <th style="background-color: #eeeddd;"><b>Accounts Payables</b></th>
                            </tr>
                            <tr>
                                <td><?php echo CHtml::link('Supplier Statements(Unpaid Invoices)',$this->createAbsoluteUrl('supplierUnpaidInv/index'));?></td>
                            </tr>
                            <tr>
                                <td><?php echo CHtml::link('Supplier Statements(Transactions)',$this->createAbsoluteUrl('supplierTransactions/index'));?></td>
                            </tr>
                        </table>
                    </div>
                    <div style="width: ;">
                        <table class="table table-bordered" id="tbl-transaction">
                            <tr>
                                <th style="background-color: #eeeddd;"><b>Inventory Movements</b></th>
                            </tr>
                            <tr>
                                <td><?php echo CHtml::link('Inventory Value Movement',$this->createAbsoluteUrl('inventoryVM/index'));?></td>
                            </tr>
                            <tr>
                                <td><?php echo CHtml::link('Inventory Quantity Movement',$this->createAbsoluteUrl('inventoryQM/index'));?></td>
                            </tr>
                            <tr>
                                <td><?php echo CHtml::link('Inventory Profit Margin',$this->createAbsoluteUrl('inventoryPM/index'));?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </td>
        </tr>
    </table>
</div>


<script type="text/javascript">

</script>