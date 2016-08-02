<?php 
namespace app\models;
use Yii;
use yii\db\ActiveRecord;
class Member_profile extends ActiveRecord
{
	public static function tableName()
	{
		return "{{%member_profile}}";
	} 
}