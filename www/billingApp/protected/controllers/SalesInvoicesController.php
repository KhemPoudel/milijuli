<?php

class SalesInvoicesController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout='//layouts/column2';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }
    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow',  // allow all users to perform 'index' and 'view' actions
                'actions'=>array('index','view','getNewForm','automatedTransaction','viewTransaction'),
                'users'=>array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions'=>array('create','update'),
                'users'=>array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions'=>array('admin','delete'),
                'users'=>array('admin'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),

        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $this->render('view',array(
            'model'=>$this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $i=0;
        $checkValid=1;
        $errors=array();
        $model1=new SalesInvoices;
        $model2=new SalesInfo;
        $receivedModel=new MoneyReceived;

        if(isset($_POST['SalesInvoices']))
        {
            //checking if the items are available
            if(isset($_POST['SalesInfo']))
            {
                $i1=0;
                $testModel=array();
                $counts=array();
                while($i1<=$_SESSION['sts'])
                {
                    $testModel[$i1]=new SalesInfo;

                    if(isset($_POST['SalesInfo'][$i1]))
                    {
                        $testModel[$i1]->attributes=$_POST['SalesInfo'][$i1];
                        if(!empty($testModel[$i1]->item_id))
                        {
                            $item=InventoryItems::model()->findByPk($testModel[$i1]->item_id);
                            if(!empty($counts[$item->id]))
                                $counts[$item->id]+=$testModel[$i1]->qty;
                            else
                                $counts[$item->id]=$testModel[$i1]->qty;
                            if($counts[$item->id]>$item->qty ){
                                $errors[$i1]=1;
                                $checkValid=0;
                            }
                        }
                    }
                    $i1++;
                }
                if($checkValid==0){
                    $model1->attributes=$_POST['SalesInvoices'];
                    if(isset($_POST['MoneyReceived']))
                        $receivedModel->attributes=$_POST['MoneyReceived'];
                    Yii::app()->user->setFlash('item_less', "<strong>You donot have enough Items left in inventotry</strong>Please check inventory");
                    $this->render('create',array(
                        'model1'=>$model1,
                        'model2'=>$testModel,
                        'receivedModel'=>$receivedModel,
                        'errors'=>$errors,
                        'mode'=>'update'
                    ));
                }
            }
            //checking end
            if($checkValid==1){
                $model1->attributes=$_POST['SalesInvoices'];
                $model1->save();
                $sales_invoice_id=$model1->id;
                if(isset($_POST['SalesInfo']))
                {
                    $testModel=array();
                    while($i<=$_SESSION['sts'])
                    {
                        $testModel[$i]=new SalesInfo;
                        if(isset($_POST['SalesInfo'][$i]))
                        {
                            $testModel[$i]->attributes=$_POST['SalesInfo'][$i];
                            $testModel[$i]->sales_invoice_id=$sales_invoice_id;
                            if(!empty($testModel[$i]->item_id))
                            {
                                //$testModel[$i]->save();
                                $item=InventoryItems::model()->findByPk($testModel[$i]->item_id);
                                if($item->qty>0){
                                    $rate_of_cost_to_decrease=$item->total_cost/$item->qty;
                                    $item->qty-=$testModel[$i]->qty;
                                    $item->total_cost-=$rate_of_cost_to_decrease*$testModel[$i]->qty;
                                    $testModel[$i]->cost_price=$rate_of_cost_to_decrease*$testModel[$i]->qty;
                                    $item->save();
                                }
                                $testModel[$i]->save();
                            }
                        }
                        $i++;
                    }

                }
                if(isset($_POST['MoneyReceived']))
                {
                    $receivedModel->attributes=$_POST['MoneyReceived'];
                    $receivedModel->received_date=$model1->issue_date;
                    $receivedModel->sales_invoice_id=$model1->id;
                    $receivedModel->customer_id=$model1->customer_id;
                    $receivedModel->status=1;
                    if($receivedModel->save())
                    {
                        $balance=($receivedModel->amount)*1.0-($model1->total_amount)*1.0;
                        if($balance<=0){
                            $model1->balance=$balance*(-1.0);
                        }
                        else{
                            $model1->balance=0;
                            $creditModel=new CustomersCredit;
                            $creditModel->amount=$balance;
                            $creditModel->credited_from=$model1->id;
                            $creditModel->customer_id=$model1->customer_id;
                            $creditModel->credited_date=$model1->issue_date;
                            $creditModel->save();
                        }
                        //SalesInvoices::model()->findByPk($model1->id)->delete();
                        $model1->save();
                        //Yii::app()->AutomatedExecution->AutomatedExecutionHandler();
                    }
                }
                AutomatedClass::AutomatedTransaction($model1->id,'');
                $this->redirect(array('view','id'=>$model1->id));
            }

        }
        if($checkValid==1){
            $this->render('create',array(
                'model1'=>$model1,
                'model2'=>$model2,
                'receivedModel'=>$receivedModel,
                'errors'=>$errors,
                'mode'=>''
            ));
        }

    }



    /*------------------------------Automated transaction start-----------------------------------------------------------*/


    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */

    public function actionUpdate($id)
    {
        $i=0;
        $checkValid=1;
        $errors=array();
        $model1=$this->loadModel($id);
        $model2=$model1->salesInfos;
        $receivedModel=$this->loadReceivedModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['SalesInvoices']))
        {
            //checking if the items are available
            if(isset($_POST['SalesInfo']))
            {
                $i1=0;
                $testModel=array();
                $counts=array();
                $oldQty=array();
                foreach($model2 as $indModel)
                {
                    if(!empty($oldQty[$indModel->item_id]))
                        $oldQty[$indModel->item_id]+=$indModel->qty;
                    else
                        $oldQty[$indModel->item_id]=$indModel->qty;
                }
                while($i1<=$_SESSION['sts'])
                {
                    $testModel[$i1]=new SalesInfo;

                    if(isset($_POST['SalesInfo'][$i1]))
                    {
                        $testModel[$i1]->attributes=$_POST['SalesInfo'][$i1];
                        if(!empty($testModel[$i1]->item_id))
                        {
                            $item=InventoryItems::model()->findByPk($testModel[$i1]->item_id);
                            if(!empty($counts[$item->id]))
                                $counts[$item->id]+=$testModel[$i1]->qty;
                            else
                                $counts[$item->id]=$testModel[$i1]->qty;
                            if(!empty($oldQty[$item->id])){
                                $counts[$item->id]-=$oldQty[$item->id];
                                $oldQty[$item->id]=0;
                            }
                            if($counts[$item->id]>$item->qty ){
                                $errors[$i1]=1;
                                $checkValid=0;
                            }
                        }
                    }
                    $i1++;
                }
                if($checkValid==0){
                    $model1->attributes=$_POST['SalesInvoices'];
                    if(isset($_POST['MoneyReceived']))
                        $receivedModel->attributes=$_POST['MoneyReceived'];
                    Yii::app()->user->setFlash('item_less', "<strong>You donot have enough Items left in inventotry</strong>Please check inventory");
                    $this->render('update',array(
                        'model1'=>$model1,
                        'model2'=>$testModel,
                        'receivedModel'=>$receivedModel,
                        'errors'=>$errors,
                        'mode'=>'update'
                    ));
                }
            }
            //checking end
            if($checkValid==1){
                //if customer is updated
                $model_temp=new SalesInvoices;
                $model_temp->attributes=$_POST['SalesInvoices'];
                if($model1->customer_id!==$model_temp->customer_id){
                    $model1->balance=0;
                    $creditModel=CustomersCredit::model()->find(array('condition'=>'credited_from=:id','params'=>array(':id'=>$id)));
                    if(isset($creditModel)){
                        $creditModel->credit_assigned=0;
                        $creditModel->save();
                    }
                    $model1->save();
                    AutomatedClass::AutomatedTransaction($id,'');
                    //$model1->delete();
                    foreach($model1->automatedTransactionCustomers as $c)
                        $c->delete();
                    foreach($model1->automatedTransactionCustomers1 as $c)
                        $c->delete();
                    foreach($model1->moneyReceiveds as $c)
                        $c->delete();
                    foreach($model1->customersCredits as $c)
                        $c->delete();
                    foreach($model1->salesInfos as $c)
                        $c->delete();
                    $model1->credited=0;
                    $receivedModel=new MoneyReceived;
                }
                //end
                $model1->attributes=$_POST['SalesInvoices'];
                $model1->save();
                $sales_invoice_id=$model1->id;
                foreach($model2 as $indModel)
                {
                    $indModel->delete();
                }
                if(isset($_POST['SalesInfo']))
                {
                    while($i<=$_SESSION['sts'])
                    {
                        $item=array();
                        if(isset($_POST['SalesInfo'][$i]))
                        {
                            $indModel1=new SalesInfo;
                            $indModel1->attributes=$_POST['SalesInfo'][$i];
                            $indModel1->sales_invoice_id=$sales_invoice_id;
                            $item[$i]=InventoryItems::model()->findByPk($indModel1->item_id);
                            if($item[$i]->qty>0){
                                $rate_of_cost_to_decrease=$item[$i]->total_cost/$item[$i]->qty;
                                //echo $rate_of_cost_to_decrease;
                                $item[$i]->qty-=$indModel1->qty;
                                $item[$i]->total_cost-=$rate_of_cost_to_decrease*$indModel1->qty;
                                $indModel1->cost_price=$rate_of_cost_to_decrease*$indModel1->qty;
                                $item[$i]->save();
                            }
                            $indModel1->save();
                        }
                        $i++;
                    }
                }

                if(isset($_POST['MoneyReceived']))
                {
                    $receivedModel->attributes=$_POST['MoneyReceived'];
                    $receivedModel->customer_id=$model1->customer_id;
                    $receivedModel->received_date=$model1->issue_date;
                    $receivedModel->sales_invoice_id=$model1->id;
                    $receivedModel->status='1';
                    $receivedModel->save();
                    $total_received=0;
                    foreach(MoneyReceived::model()->findAll(array('condition'=>'sales_invoice_id=:invoice','params'=>array(':invoice'=>$id))) as $receipts){
                        $total_received+=$receipts->amount;
                    }
                    $tempBalance=$model1->total_amount-$total_received;
                    $creditRow=CustomersCredit::model()->find(array('condition'=>'credited_from=:invoice','params'=>array(':invoice'=>$id)));
                    if($tempBalance>=0){
                        $model1->balance=$tempBalance;
                        if(isset($creditRow))
                        {
                            $creditRow->amount=0;
                            $creditRow->save();
                        }
                    }
                    else{
                        $model1->balance=0;
                        if(!isset($creditRow)){
                            $creditRow=new CustomersCredit;
                            $creditRow->credited_from=$id;
                            $creditRow->customer_id=$model1->customer_id;
                        }
                        $creditRow->amount=-1*$tempBalance;
                        $creditRow->credited_date=$model1->issue_date;
                        $creditRow->customer_id=$model1->customer_id;
                        $creditRow->save();
                    }
                    $model1->save();

                }
                AutomatedClass::AutomatedTransaction($model1->id,'');
                $this->redirect(array('view','id'=>$model1->id));
            }
        }
        if($checkValid==1){
            $this->render('update',array(
                'model1'=>$model1,
                'model2'=>$model2,
                'receivedModel'=>$receivedModel,
                'errors'=>$errors
            ));
        }

    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $model1=$this->loadModel($id);
        foreach($model1->salesInfos as $cnt=>$indModel)
        {
            $item=InventoryItems::model()->findByPk($indModel->item_id);
            $rate_of_cost_of_decrease=$item->total_cost/$item->qty;
            $item->qty+=$indModel->qty;
            $item->total_cost+=$indModel->qty*$rate_of_cost_of_decrease;
            $item->save();
        }
        $model1->balance=0;
        $creditModel=CustomersCredit::model()->find(array('condition'=>'credited_from=:id','params'=>array(':id'=>$id)));
        if(isset($creditModel)){
            $creditModel->credit_assigned=0;
            $creditModel->save();
        }
        $model1->save();
        AutomatedClass::AutomatedTransaction($id,'');
        $model1->delete();
        Yii::app()->user->setFlash('invoice_deletion', "Invoice was successfully deleted. Any related transactions are also deleted automatically ");
        $this->redirect(array('index'));

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        //if(!isset($_GET['ajax']))
          //  $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        if(isset($_POST['search_params']))
        {
            $searchParams=$_POST['search_params'];
            $match = addcslashes($searchParams, '%_'); // escape LIKE's special characters
            $q1 = new CDbCriteria(array(
                'condition' => "customerName LIKE :match",         // no quotes around :match
                'params'    => array(':match' => "%$match%")
            ));
            $model1=Customers::model()->findAll($q1);
            $arrayOfIds=array();
            foreach($model1 as $cnt=>$indModel)
            {
                $arrayOfIds[$cnt]=$indModel->id;
            }
            $q = new CDbCriteria( array(
                'condition' => "id LIKE :match OR issue_date LIKE :match OR total_amount LIKE :match",         // no quotes around :match
                'params'    => array(':match' => "%$match%")  // Aha! Wildcards go here
            ));
            $q3=new CDbCriteria;
            $q3->addInCondition('customer_id',$arrayOfIds);
            $q->mergeWith($q3,'OR');
            $count=SalesInvoices::model()->count($q);
            $pages=new CPagination($count);
            // results per page
            $pages->pageSize=10;
            $pages->applyLimit($q);
            $q->order='id DESC';
            $models = SalesInvoices::model()->findAll( $q );     // works!
        }


        else if(isset($_GET['customer']))
        {
            $criteria=new CDbCriteria();
            $criteria->condition='customer_id=:customer_id';
            $criteria->params=array(':customer_id'=>$_GET['customer']);
            $count=SalesInvoices::model()->count($criteria);
            $pages=new CPagination($count);
            // results per page
            $pages->pageSize=10;
            $pages->applyLimit($criteria);
            $criteria->order='id DESC';
            $models=SalesInvoices::model()->findAll($criteria);
        }
        else
        {
            $criteria=new CDbCriteria();
            $count=SalesInvoices::model()->count($criteria);
            $pages=new CPagination($count);
            // results per page
            $pages->pageSize=10;
            $pages->applyLimit($criteria);
            $criteria->order='id DESC';
            $models=SalesInvoices::model()->findAll($criteria);
        }


        $this->render('index', array(
            'models' => $models,
            'pages' => $pages,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model=new SalesInvoices('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['SalesInvoices']))
        {
            $model->attributes=$_GET['SalesInvoices'];
        }



        $this->render('admin',array(
            'model'=>$model,
        ));
    }

    public function actionGetNewForm()
    {
        $sts=$_POST['sts'];
        $_SESSION['sts']=$sts;
        return $this->renderPartial('_form-sales-info',array('form'=>$_SESSION['form'],'model2'=>$_SESSION['model2'],'sts'=>$sts));
    }

    public function actionViewTransaction($id)
    {
        $this->render('viewTransaction',array('id'=>$id));
    }


    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return SalesInvoices the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model=SalesInvoices::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param SalesInvoices $model the model to be validated
     */
    public function loadReceivedModel($id)
    {
        $model=MoneyReceived::model()->find(array('condition'=>'sales_invoice_id=:invoice AND status=:status','params'=>array(':status'=>1,':invoice'=>$id)));
        return $model;
    }
}

