<?php

namespace frontend\controllers;

use Yii;
use common\models\Note;
use common\models\Category;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\authclient\OAuthToken;

/**
 * NoteController implements the CRUD actions for Note model.
 */
class NoteController extends Controller
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
     * Lists authorized user's notes.
     * @return mixed
     */
    public function actionIndex()
    {  
        $client = Yii::$app->authClientCollection->getClient('vkontakte');
        $userToken = Yii::$app->user->identity->token;
        
        if(!empty($userToken)) {
            $oauthToken = new OAuthToken();
            $oauthToken->setToken($userToken);
            if($oauthToken->getIsExpired())
            {
                $oauthToken = $client->refreshAccessToken($oauthToken);
                Yii::$app->user->identity->updateAttributes(['token' => $oauthToken->getToken()]);
            }
            
            $vkNotesProvider = new ArrayDataProvider([
                'allModels' => Yii::$app->user->identity->getVkontakteNotes(),
                'pagination' => false,            
            ]);

            $button = ['id' => 'vk-disconnect', 'text' => Yii::t('app', 'VK disconnect'), 'link' => '/note/vkontakte-disconnect'];   
        }
        else {
            if(!isset($_GET['code'])) {
                $vkNotesProvider = new ArrayDataProvider([
                    'allModels' => false,
                    'pagination' => false,            
                ]);
                
                $button = ['id' => 'vk-connect', 'text' => Yii::t('app', 'VK connect'), 'link' => $client->buildAuthUrl()];
            }
            else {
                $token = $client->fetchAccessToken($_GET['code']);
                Yii::$app->user->identity->updateAttributes(['token' => $token->getToken()]); 
                
                $vkNotesProvider = new ArrayDataProvider([
                    'allModels' => Yii::$app->user->identity->getVkontakteNotes(),
                    'pagination' => false,            
                ]);
                
                $button = ['id' => 'vk-disconnect', 'text' => Yii::t('app', 'VK disconnect'), 'link' => '/note/vkontakte-disconnect'];
            }            
        }
       
        return $this->render('index', [
            'dataProvider' => new ArrayDataProvider([
                'allModels' => Yii::$app->user->identity->getNotesList(),
                'pagination' => false,            
            ]),
            'vkNotesProvider' => $vkNotesProvider,
            'button' => $button
        ]);
    }
    
    public function actionVkontakteDisconnect()
    {
        return Yii::$app->user->identity->updateAttributes(['token' => null]);       
    }

    /**
     * Displays a single Note model.
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
     * Saves Note to Database
     * 
     */
    public function actionStore()
    {
        //$model = new Note();
        //return $model->save();        
    }
    
    public function actionEdit()
    {
        //$model = new Note();
        //return $model->save();        
    }

    /**
     * Creates a new Note model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Note();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['show', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Note model.
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
     * Deletes an existing Note model.    
     * @param integer $id
     * @return mixed
     */
    public function actionDestroy($id, $ajax=false)
    {
        $model = $this->findModel($id);
        if($model) {
            $model->delete();
            
            if(!$ajax) {                
                return $this->redirect(['index']);
            }
            else {
                $result = json_encode(['note_id' => $id]); 
                return $result;
            }
        }
    }

    /**
     * Finds the Note model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Note the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Note::findOne($id)) !== null) {
            if($model->category->user_id == Yii::$app->user->id) {
                return $model;
            }            
            throw new ForbiddenHttpException('You are not allowed to access this page.');
        } 
        else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }       
    }
}
