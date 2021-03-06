<?php

namespace api\controllers;

use common\models\Lab;
use common\models\Student;
use common\models\LabItems;
use Yii;
use yii\web\Controller;
use yii\web\Response;


class TaskController extends Controller
{
    /**
     * получить задание для работы
     * @return array
     */
    public function actionIndex()
    {
        $session = Yii::$app->session;
        $result = [
            'task' => null,
            'can_finish' => !Yii::$app->user->can('viewAdminPage')
        ];

        if ($session->has('lab_number')) {
            $result['task'] = LabItems::find()->tree($session->get('lab_number'));
        }

        return $result;
    }

    /**
     * получить информацию для титольного листа
     * @return array
     */
    public function actionTitleInfo()
    {
        $session = Yii::$app->session;
        $result = [];
        $isAdmin = Yii::$app->user->can('viewAdminPage');

        if ($session->has('lab_number')) {
            $lab = Lab::findOne($session->get('lab_number'));
            $student = Student::find()->andWhere(['user_id' => Yii::$app->user->id])->one();

            $result = [
                'number' => $lab->id,
                'name' => $lab->name,
                'studentName' => ($isAdmin) ? '' : $student->exportName,
                'studentGroup' => ($isAdmin) ? '' : $student->group->name,
                'studentVariant' => ($isAdmin) ? '' : $student->variant,
                'teacherName' => ($isAdmin) ? '' : $student->teacher,
                'year' => date('Y')
            ];
        }

        return $result;
    }
}