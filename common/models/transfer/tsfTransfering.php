<?php

namespace common\models\transfer;


use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;


/**
 * This is the model class for table "tsf_transfering".
 *
 * @property integer $transfer_id
 * @property string $date_transfer
 * @property string $id_accountability
 * @property string $name_account
 * @property string $details
 * @property string $amount_money
 * @property string $update_date
 * @property string $reference
 */
class tsfTransfering extends \yii\db\ActiveRecord
{
    public $search;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tsf_transfering';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date_transfer', 'id_accountability', 'name_account', 'details', 'amount_money', 'update_date', 'references'], 'required'],
            [['update_date'], 'safe'],
            [['date_transfer'], 'string', 'max' => 50],
            [['id_accountability', 'amount_money'], 'string', 'max' => 20],
            [['name_account'], 'string', 'max' => 100],
            [['details'], 'string', 'max' => 255],
            [['references'], 'string', 'max' => 15]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'transfer_id' => 'Transfer ID',
            'date_transfer' => 'Date Transfer',
            'id_accountability' => 'กรอกเลขบัญชีธนาคาร',
            'name_account' => 'Name Account',
            'details' => 'Details',
            'amount_money' => 'Amount Money',
            'update_date' => 'Update Date',
            'references' => 'Reference',
        ];
    }
    public function lists($id_accountability=null,$name_account=null) {
       $data = explode(' ', $name_account);
       $name_accounts=$data[0];
       $name_accountss=$data[3];
        if ($id_accountability) {
            $query = tsfTransfering::find()
                  //  ->where('id_accountability=:id_accountability','name_account=:name_account')
                    //    ->addParams([':id_accountability'=>$id_accountability],[':name_account'=>'%'.$name_account.'%']);
                //        ]);
                    ->where(['like','id_accountability',$id_accountability])
                    ->andwhere('name_account LIKE "' . $name_accounts . '%"')
                    ->andwhere('name_account LIKE "%' . $name_accountss . '"')
						->orderBy('update_date DESC');
                    
        } 

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pagesize' => 10,
            ],
        ]);

        
        return $dataProvider;
    }
}
