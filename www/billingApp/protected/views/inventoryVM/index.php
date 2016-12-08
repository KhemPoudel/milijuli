<table class="table table-bordered">
    <tr>
        <th colspan="2">
            Inventory Value Movement
        </th>


        <th colspan="2" style="border-left: none;">
            <?php
            echo CHtml::link(
                '<button class="btn btn-default btn-sm"><span class="btn-label-style"> Add New Report</span></button>',
                $this->createAbsoluteUrl('create')
            );
            ?>
        </th>
        <th colspan="4" style="border-left: none;">
            <?php
            echo CHtml::link(
                '<input type="text" ><span class="btn-label-style">Search</span></input>',
                $this->createAbsoluteUrl('admin')
            );
            ?>
        </th>
    </tr>
    <tr>

        <th>
            Edit
        </th>
        <th>
            View
        </th>
        <th>
            From Date
        </th>
        <th>
            To Date
        </th>
        <th colspan="2">
            Description
        </th>


    </tr>
    <?php
    foreach(InventoryVM::model()->findAll() as $report){
        ?>
        <tr>
            <td>
                <?php echo CHtml::link(
                    '<button class="btn btn-default">
                    <span class="btn-label-style">Edit</span>
                    </button>',
                    array(
                        'update',
                        'id'=>$report->id)
                );
                ?>
            </td>
            <td>
                <?php echo CHtml::link(
                    '<button class="btn btn-default">
                    <span class="btn-label-style">View</span>
                    </button>',
                    array(
                        'view',
                        'id'=>$report->id)
                );
                ?>
            </td>
            <td>
                <?php echo
                $report->from_date;
                ?>
            </td>
            <td>
                <?php echo
                $report->to_date;
                ?>
            </td>
            <td>
                <?php echo
                $report->description;
                ?>
            </td>
        </tr>
    <?php }
    ?>
</table>