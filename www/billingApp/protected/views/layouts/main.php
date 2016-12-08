<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="language" content="en" />

    <!-- blueprint CSS framework -->
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
    <!--[if lt IE 8]>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
    <![endif]-->

    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/cssForHeaders.css" />
    <?php Yii::app()->bootstrap->init(); ?>
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body style="">
<div class="" style="padding-top:1%;padding-bottom:1%;text-align:center;background-color:#acafcf;">


    <h2>Milijuli Paper House</h2>
    <h4 style="">Pokhara-8, Simalchour</h4>
</div>

<div class="row" style="margin:0.2% 0.1% 0 0.1%; ">
    <div class="navbar">
        <div class="navbar-inner">
            <span class="brand" style="margin-top: -8px;">
                    <?php
                    $image=CHtml::image(Yii::app()->request->baseUrl.'/assets/back.png','',
                        array('width'=>'30px','height'=>'30px','title'=>'image title here'));
                    echo CHtml::htmlButton($image,array(
                            'name' => 'btnBack',
                            'onclick' => "history.go(-1)",
                        )
                    );?>
            </span>
            <a class="brand" href="#">Milijuli</a>
            <ul class=" menu nav">
                <li><a href="<?php echo $this->createAbsoluteUrl('/salesInvoices')?>">Sales Invoices</a></li>
                <li><a href="<?php echo $this->createAbsoluteUrl('/customers')?>">Customers</a></li>
                <li><a href="<?php echo $this->createAbsoluteUrl('/inventoryItems')?>">Inventory Items</a></li>
                <li><a href="<?php echo $this->createAbsoluteUrl('/suppliers')?>">Suppliers</a></li>
                <li><a href="<?php echo $this->createAbsoluteUrl('/purchasesInvoices')?>">Purchase Invoices</a></li>
                <li><a href="<?php echo $this->createAbsoluteUrl('/reports')?>">Reports</a></li>
                <li><a href="<?php echo $this->createAbsoluteUrl('/Calculator')?>">Calculator</a></li>
                <li><a href="<?php echo $this->createAbsoluteUrl('/')?>">Settings</a></li>
            </ul>
        </div>
    </div>
    <div class="col-md-8" style="background-color: #ffffff;">
        <?php echo $content; ?>
    </div><!-- mainmenu -->






    <div class="clear"></div>

    <div id="footer">
        Copyright &copy; <?php echo date('Y'); ?> Khem Poudel<br/>
        All Rights Reserved.<br/>
        <a href='http://www.parassigdel.com.np' target="_blank">Parash & The Company</a>
    </div><!-- footer -->

</div><!-- page -->

</body>
<script type="text/javascript">
        $(document).ready(function(){
            var url=window.location.href;
            var splitValues=url.split('/',5);
            $('.menu li a').each(function() {
                var selectedUrlItems=($(this).attr('href')).split('/',5);
                if(splitValues[4]==selectedUrlItems[4]){
                    $(this).parent().addClass('active');
                    $(this).parent().siblings().removeClass('active');
                }
            });
        })



</script>
</html>
