<?php

class InventoryItemsController extends Controller
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
				'actions'=>array('index','view','viewTransaction'),
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
		$model=new InventoryItems;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['InventoryItems']))
		{
			$model->attributes=$_POST['InventoryItems'];
			if($model->save())
            {
                $flash="<strong>Inventory item '".$model->item_name."' is successfully inserted.</strong> You can purchase or sell it now";
                Yii::app()->user->setFlash('inv_creation',$flash);
                $this->redirect(array('index'));
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

		if(isset($_POST['InventoryItems']))
		{
			$model->attributes=$_POST['InventoryItems'];
			if($model->save()){
                $flash="<strong>Inventory item '".$model->item_name."' is successfully updated.</strong> Related transactions are updated as well";
                Yii::app()->user->setFlash('inv_update',$flash);
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

		$model=$this->loadModel($id);
        $model->delete();
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		//if(!isset($_GET['ajax']))
        $flash="<strong>Inventory item '".$model->item_name."' was successfully deleted.</strong>Related Transactions Deleted";
        Yii::app()->user->setFlash('inv_deletion',$flash);
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
            $q = new CDbCriteria( array(
                'condition' => "item_name LIKE :match OR item_identifier LIKE :match OR total_cost LIKE :match OR sp LIKE :match OR cp LIKE :match OR qty LIKE :match",         // no quotes around :match
                'params'    => array(':match' => "%$match%")  // Aha! Wildcards go here
            ));
            $count=InventoryItems::model()->count($q);
            $pages=new CPagination($count);
            // results per page
            $pages->pageSize=10;
            $pages->applyLimit($q);
            $q->order='id DESC';
            $models = InventoryItems::model()->findAll( $q );     // works!
        }
        else
        {
            $criteria=new CDbCriteria();
            $count=InventoryItems::model()->count($criteria);
            //echo $count;
            $pages=new CPagination($count);

            // results per page
            $pages->pageSize=10;
            $pages->applyLimit($criteria);
            $criteria->order='id DESC';
            $models=InventoryItems::model()->findAll($criteria);
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
		$model=new InventoryItems('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['InventoryItems']))
			$model->attributes=$_GET['InventoryItems'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

    public function actionViewTransaction($id)
    {
        $this->render('viewTransaction',array('id'=>$id));
    }

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return InventoryItems the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=InventoryItems::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param InventoryItems $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='inventory-items-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
