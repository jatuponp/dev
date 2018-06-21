<?php

namespace common\models\academicranks;

use Yii;
use common\models\Staff;
//use app\modules\academicranks\models\ariStaff;
use common\models\tblStaffExecutive;
use common\models\TblAdministrator;
use common\models\TblBelongto;
use common\models\Department;
use common\components\ndate;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * This is the model class for table "tbl_staff_executive".
 *
 * @property integer $id
 * @property string $citizen_id
 * @property integer $admin_id
 * @property string $date_start
 * @property string $date_stop
 * @property string $modifieddate
 * @property string $modifiedby
 *
 * @property Administrator $admin
 */
class ariStaffExecutive extends tblStaffExecutive {

    public function search() {
        $query = ariStaffExecutive::find();
        $query->all();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        //  }
        return $dataProvider;
    }

    public function Admins($citizen_id = NULL) {
        $subQuery = ariStaffExecutive::findOne(['citizen_id' => $citizen_id]);
        $admin_id = $subQuery->admin_id;
        $query = TblAdministrator::findOne(['admin_id' => $admin_id]);

        return $query->admin_title;
    }

    public function DateStart($citizen_id = NULL) {
        $query = ariStaffExecutive::findOne(['citizen_id' => $citizen_id]);
        //    ->select(['status_date']);
        // Yii::$app->thaiFormatter->locale = 'th_TH';
        $date_start = $query->date_start;
        // Yii::$app->thaiFormatter->locale = 'th-TH';
//        $dmy= Yii::$app->Formatter->asDate($date_start, 'php:Y-m-d'); 
//        $d = new ndate();
//        return $d->getThaiShortDate($dmy);
        $d = new ndate();
        return $d->getThaiShortDate($date_start);
    }

    public function DateStop($citizen_id = NULL) {
        $query = ariStaffExecutive::findOne(['citizen_id' => $citizen_id]);
        //    ->select(['status_date']);
        // Yii::$app->thaiFormatter->locale = 'th_TH';
        $date_stop = $query->date_stop;
        // Yii::$app->thaiFormatter->locale = 'th-TH';
//        $dmy = Yii::$app->Formatter->asDate($date_stop, 'php:Y-m-d');
//        $d = new ndate();
//        return $d->getThaiShortDate($dmy);
        $d = new ndate();
        return $d->getThaiShortDate($date_stop);
    }

}
