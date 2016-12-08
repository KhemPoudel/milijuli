<?php

class InventoryItemsManualIncrementController extends Controller
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
				'actions'=>array('index','view'),
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
	public function actionCreate($inventoryId)
	{
		$model=new InventoryItemsManualIncrement;
        $inventoryModel=InventoryItems::model()->findByPk($inventoryId);
        //$inventoryId=$_GET['inventoryId'];
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['InventoryItemsManualIncrement']))
		{
            $inventory_model=InventoryItems::model()->findByPk($inventoryId);
            $model->attributes=$_POST['InventoryItemsManualIncrement'];
            $model->inventory_id=$inventoryId;
            $model->total_cost=$model->qty*$model->cp;
            $inventory_model->qty+=$model->qty;
            $inventory_model->total_cost+=$model->total_cost;
			if($model->save() && $inventory_model->save());
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
            'inventoryModel'=>$inventoryModel
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
        $inventory_model=$model->inventory;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['InventoryItemsManualIncrement']))
		{
            $oldqty=$model->qty;
            $old_total_cost=$model->total_cost;
			$model->attributes=$_POST['InventoryItemsManualIncrement'];
            $model->total_cost=$model->qty*$model->cp;
            $inventory_model->qty+=($model->qty-$oldqty);
            $inventory_model->total_cost+=($model->total_cost-$old_total_cost);
			if($model->save() && $inventory_model->save())
				$this->redirect(array('view','id'=>$model->id));
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
        $inventory_model=$model->inventory;
        $oldqty=$model->qty;
        $old_total_cost=$model->total_cost;
        $inventory_model->qty-=$oldqty;
        $inventory_model->total_cost-=$old_total_cost;
        $inventory_model->save();
        $model->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		$this->redirect(array('inventoryItems/index'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('InventoryItemsManualIncrement');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new InventoryItemsManualIncrement('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['InventoryItemsManualIncrement']))
			$model->attributes=$_GET['InventoryItemsManualIncrement'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return InventoryItemsManualIncrement the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=InventoryItemsManualIncrement::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param InventoryItemsManualIncrement $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='inventory-items-manual-increment-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
