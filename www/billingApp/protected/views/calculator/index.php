<div id="calculator">
    <div style="height: 60px;padding: 10px;background-color: darkgrey;">
        <h2 style="">Welcome to your Calculator</h2>
    </div>
    <table class="table-striped">
        <tr>
            <td colspan="4">

            </td>
            <td colspan="2">
                <b>FOR A COPY PRODUCT<b>
            </td>
        </tr>
        <tr>
            <th>
                <label>Enter Length: </label>
            </th>
            <td>
                <input type="text" id="length" class="input-data" placeholder="0">
            </td>
            <th>
                <label>No. of Pages</label>
            </th>
            <td>
                <input type="text" id="noOfPages" class="input-data" placeholder="Enter number greater than 0">
            </td>
            <td>
                <label>Enter No Of Pages In the Copy</label>
            </td>
            <td>
                <input type="text" id="copyPages" class="input-data" placeholder="0">
            </td>
        </tr>
        <tr>
            <th>
                <label>Enter Width: </label>
            </th>
            <td>
                <input type="text" id="width" class="input-data" placeholder="0">
            </td>
            <th>
                <label>Price Per KG</label>
            </th>
            <td>
                <input type="text" id="pricePerKg" class="input-data" placeholder="0">
            </td>
            <td>
                <label>Enter Price Of Cover</label>
            </td>
            <td>
                <input type="text" id="coverPrice" class="input-data" placeholder="0">
            </td>

        </tr>
        <tr>
            <th>
                <label>Enter weight: </label>
            </th>
            <td colspan="3">
                <input type="text" id="weight" class="input-data" placeholder="0">
            </td>
            <td>
                <label>Enter unit labor price of each page</label>

            </td>
            <td>
                <input type="text" id="laborPrice" class="input-data" placeholder="0">
            </td>

        </tr>
        <tr>
            <td colspan="4">

            </td>
            <td>
                <label>Enter Miscellaneous Price</label>

            </td>
            <td>
                <input type="text" id="miscPrice" class="input-data" placeholder="0">
            </td>

        </tr>
        <tr>
            <td colspan="6">
                <b>OUTPUT</b>
            </td>
        </tr>
        <tr>
            <th>
                <label>Total weight: </label>
            </th>
            <td>
                <input type="text" id="total_weight" placeholder="0">
            </td>
            <th>
                <label>Unit price for a paper</label>

            </th>
            <td>
                <input type="text" id="up" placeholder="0">
            </td>
            <td>
                <label>Final price of a copy</label>

            </td>
            <td>
                <input type="text" id="finalPrice" placeholder="0">
            </td>
        </tr>
    </table>



</div>

<script type="text/javascript">
    $(document).ready(function(){
       $('#calculator').on('focus','#length',function(){
           startCalc();
       });
        $('#calculator').on('focus','#width',function(){
            startCalc();
        });
        $('#calculator').on('focus','#weight',function(){
            startCalc();
        });
        $('#calculator').on('focus','#pricePerKg',function(){
            startCalc();
        });
        $('#calculator').on('focus','#noOfPages',function(){
            startCalc();
        });
        $('#calculator').on('focus','#copyPages',function(){
            startCalc();
        });
        $('#calculator').on('focus','#coverPrice',function(){
            startCalc();
        });
        $('#calculator').on('focus','#laborPrice',function(){
            startCalc();
        });
        $('#calculator').on('focus','#miscPrice',function(){
            startCalc();
        });
        $('#calculator').on('blur','#length',function(){
            stopCalc();
        });
        $('#calculator').on('blur','#width',function(){
            stopCalc();
        });
        $('#calculator').on('blur','#weight',function(){
            stopCalc();
        });
        $('#calculator').on('blur','#pricePerKg',function(){
            stopCalc();
        });
        $('#calculator').on('blur','#noOfPages',function(){
            stopCalc();
        });
        $('#calculator').on('blur','#copyPages',function(){
            stopCalc();
        });
        $('#calculator').on('blur','#coverPrice',function(){
            stopCalc();
        });
        $('#calculator').on('blur','#laborPrice',function(){
            stopCalc();
        });
        $('#calculator').on('blur','#miscPrice',function(){
            stopCalc();
        });
        function startCalc(){
            interval=setInterval(function(){

                var length=$('#length').val();
                var width=$('#width').val();
                var weight=$('#weight').val();
                var pricePerKg=$('#pricePerKg').val();
                var totalWeight=$('#total_weight').val();
                var noOfPages=$('#noOfPages').val();
                var copyPages=$('#copyPages').val();
                var coverPrice=$('#coverPrice').val();
                var laborPrice=$('#laborPrice').val();
                var miscPrice=$('#miscPrice').val();
                var up=$('#up').val();
                if(length=='' || width=='' || weight==''){
                    $('#total_weight').val('0');
                }
                else
                    $('#total_weight').val(parseFloat(length)*parseFloat(width)*parseFloat(weight)/20000);
                if(pricePerKg=='' || noOfPages=='')
                    $('#up').val('0');
                else
                    if(parseFloat(noOfPages)==0){}
                        //stopCalc();
                    else
                        $('#up').val(parseFloat(totalWeight)*parseFloat(pricePerKg)/parseFloat(noOfPages))
                if(copyPages=='' || coverPrice=='' || laborPrice=='' || miscPrice=='')
                    $('#finalPrice').val('0');
                else
                    $('#finalPrice').val(parseFloat(copyPages)*parseFloat(up)+parseFloat(coverPrice)+parseFloat(laborPrice)*parseFloat(copyPages)+parseFloat(miscPrice));
            },1)
        }
        function stopCalc(){
            clearInterval(interval);
        }
        var currentBoxNumber = 0;
        $(".input-data").keyup(function (event) {
            var increment=[3,3,3,3,-2,2,-5,1]
            if (event.keyCode == 13) {
                textboxes = $("input.input-data");
                currentBoxNumber = textboxes.index(this);
                if (textboxes[currentBoxNumber + increment[currentBoxNumber]] != null) {
                    nextBox = textboxes[currentBoxNumber + increment[currentBoxNumber]];
                    nextBox.focus();
                    nextBox.select();
                    event.preventDefault();
                    return false;
                }
            }
        });
    });
</script>