<?php

class CustomersController extends Controller
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
				'actions'=>array('index','view','viewTransaction','viewTransactionCreditType'),
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
		$model=new Customers;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Customers']))
		{
			$model->attributes=$_POST['Customers'];
			if($model->save()){
                //sleep(2);
                Yii::app()->user->setFlash('customer_addition', "<strong>Customer was successfully created.</strong> You can start transaction with the customer ");
				$this->redirect($this->createAbsoluteUrl('index'));

            }
        }
		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Customers']))
		{
			$model->attributes=$_POST['Customers'];
			if($model->save()){
                Yii::app()->user->setFlash('customer_update', "<strong>Customer was successfully updated.</strong>");
                $this->redirect(array('index'));

            }
        }
		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();
        Yii::app()->user->setFlash('customer_deletion', "<strong>Customer was successfully deleted.</strong> Any related transactions are also deleted automatically ");
        $this->redirect(array('index'));
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
                'condition' => "Email LIKE :match OR Fax LIKE :match OR Telephone LIKE :match OR Mobile LIKE :match",         // no quotes around :match
                'params'    => array(':match' => "%$match%")
            ));
            $model1=CustomersContact::model()->findAll($q1);
            $arrayOfIds=array();
            foreach($model1 as $cnt=>$indModel)
            {
                $arrayOfIds[$cnt]=$indModel->customer_id;
            }
            $q = new CDbCriteria( array(
                'condition' => "customerName LIKE :match OR PANNumber LIKE :match OR BillingAddress LIKE :match",         // no quotes around :match
                'params'    => array(':match' => "%$match%")  // Aha! Wildcards go here
            ));
            $q3=new CDbCriteria;
            $q3->addInCondition('id',$arrayOfIds);
            $q->mergeWith($q3,'OR');
            $count=Customers::model()->count($q);
            $pages=new CPagination($count);
            // results per page
            $pages->pageSize=10;
            $pages->applyLimit($q);
            $q->order='id DESC';
            $models = Customers::model()->findAll( $q );     // works!
        }
        else
        {
            $criteria=new CDbCriteria();
            $count=Customers::model()->count($criteria);
            //echo $count;
            $pages=new CPagination($count);

            // results per page
            $pages->pageSize=10;
            $pages->applyLimit($criteria);
            $criteria->order='id DESC';
            $models=Customers::model()->with('customersContacts')->findAll($criteria);
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
		$model=new Customers('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Customers']))
			$model->attributes=$_GET['Customers'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

    public function actionViewTransaction($id)
    {
        $this->render('viewTransaction',array('id'=>$id));
    }

    public function actionViewTransactionCreditType($id)
    {
        $this->render('viewTransactionCreditType',array('id'=>$id));
    }

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Customers the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Customers::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Customers $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='customers-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
