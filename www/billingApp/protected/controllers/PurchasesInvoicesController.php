<?php

class PurchasesInvoicesController extends Controller
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
        $model1=new PurchasesInvoices;
        $model2=new PurchasesInfo;
        $paidModel=new MoneyPaid;

        if(isset($_POST['PurchasesInvoices']))
        {
            $model1->attributes=$_POST['PurchasesInvoices'];
            $model1->save();
            $purchases_invoice_id=$model1->id;
            if(isset($_POST['PurchasesInfo']))
            {
                $testModel=array();
                while($i<=$_SESSION['sts'])
                {
                    $testModel[$i]=new PurchasesInfo;
                    if(isset($_POST['PurchasesInfo'][$i]))
                    {
                        $testModel[$i]->attributes=$_POST['PurchasesInfo'][$i];
                        $testModel[$i]->purchases_invoice_id=$purchases_invoice_id;
                        if(!empty($testModel[$i]->item_id))
                        {
                            $testModel[$i]->save();
                            $item=InventoryItems::model()->findByPk($testModel[$i]->item_id);
                            $item->qty+=$testModel[$i]->qty;
                            $item->total_cost+=$testModel[$i]->total_amount;
                            $item->save();
                        }
                    }
                    $i++;
                }

            }
            if(isset($_POST['MoneyPaid']))
            {
                $paidModel->attributes=$_POST['MoneyPaid'];
                $paidModel->paid_date=$model1->issue_date;
                $paidModel->purchases_invoice_id=$model1->id;
                $paidModel->supplier_id=$model1->supplier_id;
                $paidModel->status=1;
                if($paidModel->save())
                {
                    $balance=($paidModel->amount)*1.0-($model1->total_amount)*1.0;
                    if($balance<=0){
                        $model1->balance=$balance*(-1.0);
                    }
                    else{
                        $model1->balance=0;
                        $creditModel=new SuppliersCredit;
                        $creditModel->amount=$balance;
                        $creditModel->credited_from=$model1->id;
                        $creditModel->supplier_id=$model1->supplier_id;
                        $creditModel->credited_date=$model1->issue_date;
                        $creditModel->save();
                    }
                    //PurchasesInvoices::model()->findByPk($model1->id)->delete();
                    $model1->save();
                    //Yii::app()->AutomatedExecution->AutomatedExecutionHandler();
                }
            }
            AutomatedClassSupplier::AutomatedTransaction($model1->id,'');
            $this->redirect(array('view','id'=>$model1->id));
        }

        $this->render('create',array(
            'model1'=>$model1,
            'model2'=>$model2,
            'paidModel'=>$paidModel
        ));
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
        $model2=$model1->purchasesInfos;
        $paidModel=$this->loadPaidModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['PurchasesInvoices']))
        {
            //checking if the items are available
            if(isset($_POST['PurchasesInfo']))
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
                    $testModel[$i1]=new PurchasesInfo;

                    if(isset($_POST['PurchasesInfo'][$i1]))
                    {
                        $testModel[$i1]->attributes=$_POST['PurchasesInfo'][$i1];
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
                            if($counts[$item->id]+$item->qty<0 ){
                                $errors[$i1]=1;
                                $checkValid=0;
                            }
                        }
                    }
                    $i1++;
                }
                if($checkValid==0){
                    $model1->attributes=$_POST['PurchasesInvoices'];
                    if(isset($_POST['MoneyPaid']))
                        $paidModel->attributes=$_POST['MoneyPaid'];
                    Yii::app()->user->setFlash('item_less', "<strong>You donot have enough Items left in inventotry</strong>Please check inventory");
                    $this->render('update',array(
                        'model1'=>$model1,
                        'model2'=>$testModel,
                        'paidModel'=>$paidModel,
                        'errors'=>$errors
                    ));
                }
            }
            //checking end
            if($checkValid==1){
                //if supplier is updated
                $model_temp=new PurchasesInvoices;
                $model_temp->attributes=$_POST['PurchasesInvoices'];
                if($model1->supplier_id!==$model_temp->supplier_id){
                    $model1->balance=0;
                    $creditModel=SuppliersCredit::model()->find(array('condition'=>'credited_from=:id','params'=>array(':id'=>$id)));
                    if(isset($creditModel)){
                        $creditModel->credit_assigned=0;
                        $creditModel->save();
                    }
                    $model1->save();
                    AutomatedClassSupplier::AutomatedTransaction($id,'');
                    //$model1->delete();
                    foreach($model1->automatedTransactionSuppliers as $c)
                        $c->delete();
                    foreach($model1->automatedTransactionSuppliers1 as $c)
                        $c->delete();
                    foreach($model1->moneyPas as $c)
                        $c->delete();
                    foreach($model1->suppliersCredits as $c)
                        $c->delete();
                    foreach($model1->purchasesInfos as $c)
                        $c->delete();
                    $model1->credited=0;
                    $paidModel=new MoneyPaid;
                }
                //end
                $model1->attributes=$_POST['PurchasesInvoices'];
                $model1->save();
                $purchases_invoice_id=$model1->id;
                if(isset($_POST['PurchasesInfo']))
                {
                    foreach($model2 as $cnt=>$indModel)
                    {
                        $indModel->delete();
                    }
                    while($i<=$_SESSION['sts'])
                    {
                        if(isset($_POST['PurchasesInfo'][$i]))
                        {
                            $indModel=new PurchasesInfo;
                            $indModel->attributes=$_POST['PurchasesInfo'][$i];
                            $indModel->purchases_invoice_id=$purchases_invoice_id;
                            $indModel->save();
                            $item=InventoryItems::model()->findByPk($indModel->item_id);
                            $item->qty+=$indModel->qty;
                            $item->total_cost+=$indModel->total_amount;
                            $item->save();
                        }
                        $i++;
                    }
                    //$model2->save();
                }
                if(isset($_POST['MoneyPaid']))
                {
                    $paidModel->attributes=$_POST['MoneyPaid'];
                    $paidModel->supplier_id=$model1->supplier_id;
                    $paidModel->paid_date=$model1->issue_date;
                    $paidModel->purchases_invoice_id=$model1->id;
                    $paidModel->status='1';
                    $paidModel->save();
                    $total_paid=0;
                    foreach(MoneyPaid::model()->findAll(array('condition'=>'purchases_invoice_id=:invoice','params'=>array(':invoice'=>$id))) as $receipts){
                        $total_paid+=$receipts->amount;
                    }
                    $tempBalance=$model1->total_amount-$total_paid;
                    $creditRow=SuppliersCredit::model()->find(array('condition'=>'credited_from=:invoice','params'=>array(':invoice'=>$id)));
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
                            $creditRow=new SuppliersCredit;
                            $creditRow->credited_from=$id;
                            $creditRow->supplier_id=$model1->supplier_id;
                        }
                        $creditRow->amount=-1*$tempBalance;
                        $creditRow->credited_date=$model1->issue_date;
                        $creditRow->supplier_id=$model1->supplier_id;
                        $creditRow->save();
                    }
                    $model1->save();

                }
                AutomatedClassSupplier::AutomatedTransaction($model1->id,'');
                $this->redirect(array('view','id'=>$model1->id));
            }
            }

        if($checkValid==1)
        {
            $this->render('update',array(
                'model1'=>$model1,
                'model2'=>$model2,
                'paidModel'=>$paidModel,
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
        foreach($model1->purchasesInfos as $cnt=>$indModel)
        {
            $item=InventoryItems::model()->findByPk($indModel->item_id);
            $item->qty-=$indModel->qty;
            $item->total_cost-=$indModel->total_amount;
            $item->save();
        }
        $model1->balance=0;
        $creditModel=SuppliersCredit::model()->find(array('condition'=>'credited_from=:id','params'=>array(':id'=>$id)));
        if(isset($creditModel)){
            $creditModel->credit_assigned=0;
            $creditModel->save();
        }
        $model1->save();
        AutomatedClassSupplier::AutomatedTransaction($id,'');
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
                'condition' => "supplierName LIKE :match",         // no quotes around :match
                'params'    => array(':match' => "%$match%")
            ));
            $model1=Suppliers::model()->findAll($q1);
            $arrayOfIds=array();
            foreach($model1 as $cnt=>$indModel)
            {
                $arrayOfIds[$cnt]=$indModel->id;
                echo $indModel->id;
            }
            $q = new CDbCriteria( array(
                'condition' => "id LIKE :match OR issue_date LIKE :match OR total_amount LIKE :match",         // no quotes around :match
                'params'    => array(':match' => "%$match%")  // Aha! Wildcards go here
            ));
            $q3=new CDbCriteria;
            $q3->addInCondition('supplier_id',$arrayOfIds);
            $q->mergeWith($q3,'OR');
            $count=PurchasesInvoices::model()->count($q);
            $pages=new CPagination($count);
            // results per page
            $pages->pageSize=10;
            $pages->applyLimit($q);
            $q->order='issue_date DESC';
            $models = PurchasesInvoices::model()->findAll( $q );     // works!
        }

        else if(isset($_GET['supplier']))
        {
            $criteria=new CDbCriteria();
            $criteria->condition='supplier_id=:supplier_id';
            $criteria->params=array(':supplier_id'=>$_GET['supplier']);
            $count=PurchasesInvoices::model()->count($criteria);
            $pages=new CPagination($count);
            // results per page
            $pages->pageSize=10;
            $pages->applyLimit($criteria);
            $criteria->order='id DESC';
            $models=PurchasesInvoices::model()->findAll($criteria);
        }
        else
        {
            $criteria=new CDbCriteria();
            $count=PurchasesInvoices::model()->count($criteria);
            $pages=new CPagination($count);
            // results per page
            $pages->pageSize=10;
            $pages->applyLimit($criteria);
            $criteria->order='id DESC';
            $models=PurchasesInvoices::model()->findAll($criteria);
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
        $model=new PurchasesInvoices('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['PurchasesInvoices']))
            $model->attributes=$_GET['PurchasesInvoices'];

        $this->render('admin',array(
            'model'=>$model,
        ));
    }

    public function actionGetNewForm()
    {
        $sts=$_POST['sts'];
        $_SESSION['sts']=$sts;
        return $this->renderPartial('_form-purchases-info',array('form'=>$_SESSION['form'],'model2'=>$_SESSION['model2'],'sts'=>$sts));
    }

    public function actionViewTransaction($id)
    {
        $this->render('viewTransaction',array('id'=>$id));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return purchasesInvoices the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model=PurchasesInvoices::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param purchasesInvoices $model the model to be validated
     */
    public function loadPaidModel($id)
    {
        $model=MoneyPaid::model()->find(array('condition'=>'purchases_invoice_id=:invoice AND status=:status','params'=>array(':status'=>1,':invoice'=>$id)));
        return $model;
    }
}

