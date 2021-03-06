<?php
/************************************************************************************
 *
 *  Copyright (c) 2018 Thanasis Vergoulis & Konstantinos Zagganas &  Loukas Kavouras
 *  for the Information Management Systems Institute, "Athena" Research Center.
 *  
 *  This file is part of SCHeMa.
 *  
 *  SCHeMa is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *  
 *  SCHeMa is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  
 *  You should have received a copy of the GNU General Public License
 *  along with Foobar.  If not, see <https://www.gnu.org/licenses/>.
 *
 ************************************************************************************/
namespace app\models;

use Yii;
use webvimark\modules\UserManagement\models\User as Userw;
use yii\db\Query;
use yii\data\Pagination;

/**
 * This is the model class for table "notification".
 *
 * @property int $id
 * @property int $recipient_id
 * @property string $message
 * @property bool $seen
 * @property int $type
 * @property string $created_at
 * @property string $read_at
 */
class Notification extends \yii\db\ActiveRecord
{
    const DANGER=-1;
    const NORMAL=0;
    const WARNING=1;
    const SUCCESS=2;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notification';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['message'], 'string'],
            [['seen'], 'boolean'],
            [['type'], 'default', 'value' => null],
            [['type'], 'integer'],
            [['created_at', 'read_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'recipient_id' => 'Recipient ID',
            'message' => 'Message',
            'seen' => 'Seen',
            'type' => 'Type',
            'created_at' => 'Created At',
            'read_at' => 'Read At',
        ];
    }

    


    

    public static function notify($recipient_id, $message, $type,$url=null)
    {

        $query=Yii::$app->db->createCommand()->insert('notification',
            [

                "recipient_id"=>$recipient_id,
                "message" => $message,
                "type" =>$type ,
                "url" => $url,
                "created_at" => 'NOW()',
                "read_at" => null
            ]
                
        )->execute();

    }


    public function markAsSeen()
    {
         $query=Yii::$app->db->createCommand()->update('notification', ["seen" => true,
                "read_at" => 'NOW()'], "id=$this->id")->execute();     

    }

    public static function markAllAsSeen()
    {
        $recipient_id=Userw::getCurrentUser()['id'];
         $query=Yii::$app->db->createCommand()->update('notification', ["seen" => true,
                "read_at" => 'NOW()'], "recipient_id='$recipient_id'")->execute();
                 
            
    }

    public static function getNotificationHistory()
    {
        $query=new Query;


        $user=Userw::getCurrentUser()['id'];
        $query->select(['message','url','created_at', 'type'])
              ->from('notification')
              ->where(['recipient_id'=>$user]);

        $pages = new Pagination(['totalCount' => $query->count()]);
        $pages->setPageSize(15);
        
        $results = $query->orderBy('created_at DESC')->offset($pages->offset)->limit($pages->limit)->all();

        return [$pages,$results];
    }

}
