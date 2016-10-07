<?php

namespace frontend\controllers;

use Yii;
use common\models\Category;
use common\models\Note;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),                
                'rules' => [                    
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
    
    /**
     * Lists authorized user's categories.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'dataProvider' => new ArrayDataProvider([
                'allModels' => Yii::$app->user->identity->getCategoriesList(),
                'pagination' => [
                    'pageSize' => 10,
                ],
            ])
        ]);
    }

    /**
     * Displays a single Category model.
     * @param integer $id
     * @return mixed
     */
    public function actionShow($id)
    {
        return $this->render('show', [
            'model' => $this->findModel($id),
        ]);
    }
    
    /**
     * Saves Category to Database
     * 
     */
    public function actionStore()
    {
        //$model = new Category();
        //return $model->save();        
    }
    
    public function actionEdit()
    {
        //$model = new Category();
        //return $model->save();        
    }

    /**
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Category();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['show', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Category model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['show', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Category model.         
     * @param integer $id
     * @return mixed
     */
    public function actionDestroy($id, $ajax = false)
    {
        $model = $this->findModel($id);
        if($model) {
            $user = Yii::$app->user->identity;
            $defaultCategory = $user->getCategories(['name' => Category::DEFAULT_CATEGORY_NAME])->one();
            if($defaultCategory) {
                $notes = $model->getNotes()->all();            
                foreach($notes as $note) {
                    $note->updateAttributes(['category_id' => $defaultCategory->id]);
                }
            }
            
            $model->delete();
            
            if(!$ajax) {                
                return $this->redirect(['index']);
            }
            else {
                $result = json_encode(['category_id' => $id]); 
                return $result;
            }
        }        
    }

    /**
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            if($model->user_id == Yii::$app->user->id) {
                return $model;
            }            
            throw new ForbiddenHttpException('You are not allowed to access this page.');
        } 
        else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
