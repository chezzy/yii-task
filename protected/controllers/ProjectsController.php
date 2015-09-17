<?php

/**
 * Class ProjectsController
 */
class ProjectsController extends CController
{
	public $layout = 'main';

	public function filters()
	{
		return array(
			'accessControl',
		);
	}

	public function accessRules()
	{
		return array(
			array('allow',
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionIndex()
	{
		$model = new Projects('search');
        //echo '<pre>'; print_r($model->search()); die;
        $this->render('index', array('model' => $model));
	}

	public function actionSave($id=NULL)
	{
        $model = ($id == null) ? new Projects() : $this->loadModel($id);

		if (isset($_POST['Projects']))
		{
			$model->attributes = $_POST['Projects'];
			$model->due_date = strtotime($_POST['Projects']['due_date']);
			$model->save();
		}

		$this->render('save', array('model' => $model));
	}

    /**
     * @param $id
     * @throws CHttpException
     */
    public function actionComplete($id)
    {
        $model = $this->loadModel($id);
        $model->completed ^= 1;
        $model->save();

        $this->redirect($this->createUrl('/projects'));
    }

    /**
     * @param null $id
     * @throws CException
     * @throws CHttpException
     */
    public function actionTasks($id=NULL)
	{
		if ($id == NULL)
			throw new CHttpException(400, 'Missing ID');

		$project = $this->loadModel($id);
		if ($project === NULL)
			throw new CHttpException(400, 'No project with that ID exists');

        $this->processPageRequest('page');

		$model = new Tasks('search');
        $model->attributes = array('project_id' => $id);

        // Ajax request
        if (Yii::app()->request->isAjaxRequest)
        {
            $this->renderPartial('_tasksAjax', array('model' => $model, 'project' => $project));
            Yii::app()->end();
        }
        else
        {
            $this->render('tasks', array('model' => $model, 'project' => $project));
        }
	}

	public function actionDelete($id)
	{
		$model = $this->loadModel($id);

		if ($model->delete())
			$this->redirect($this->createUrl('/projects'));

		throw new CHttpException('500', 'There was an error deleting the model.');
	}

    protected function processPageRequest($param = 'page')
    {
        if (Yii::app()->request->isAjaxRequest && isset($_POST[$param]))
            $_GET[$param] = Yii::app()->request->getPost($param);
    }

	private function loadModel($id)
	{
		$model = Projects::model()->findByPk($id);
		if ($model == NULL)
			throw new CHttpException('404', 'No model with that ID could be found.');
		return $model;
	}
}
