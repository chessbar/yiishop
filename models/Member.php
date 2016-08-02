<?php 
namespace app\models;
use Yii;
use yii\db\ActiveRecord;
use app\models\Member_profile;
class Member extends ActiveRecord
{
	public $reuserpass;
	public $createtime;
	public $useroremail;
	public $rememberMe=true;
	public static function tableName()
	{
		return "{{%member}}";
	}
	public function attributeLabels()
	{
		return[
			'username'=>'用户名',
			'userpass'=>"用户密码",
			'useremail'=>"用户邮箱",
			'reuserpass'=>"确认密码",
			'useroremail'=>'用户名/电子邮箱',
			'rememberMe'=>'7天免登陆',
		];
	}
	public function rules()
	{
		return[
			['username','required','message'=>'用户名不能为空','on'=>['reg']],
			['useremail','required','message'=>'邮箱不能为空','on'=>['reg','mailreg']],
			['useremail','email','message'=>'邮箱格式不正确','on'=>['reg','mailreg']],
			['userpass','required','message'=>'用户密码不能为空','on'=>['reg','login']],
			['reuserpass','required','message'=>'确认密码不能为空','on'=>['reg']],
			['reuserpass','compare','compareAttribute'=>'userpass','message'=>"两次密码输入不一致",'on'=>['reg']],
			['username','unique','message'=>'用户名已注册','on'=>['reg']],
			['useremail','unique','message'=>'邮箱已注册','on'=>['reg','mailreg']],
			['useroremail','required','message'=>'用户名或邮箱不能为空','on'=>['login']],
			['userpass','validatePass','on'=>['login']],
		];
	}
	//后台添加用户
	public function reg($data)
	{
		$this->scenario='reg';
		if($this->load($data) && $this->validate())
		{
			$this->userpass=md5($data['Member']['userpass']);
			$this->createtime=time();
			if($this->save(false))
			{
				return true;
			}
		}
		return false;
	}
	public function getMember_profile()
	{
		return $this->hasOne(Member_profile::className(),['uid'=>'uid']);
	}
	//前台登录
	public function login($data)
	{
		$this->scenario='login';
		if($this->load($data) && $this->validate()){
			$lifetime = $this->rememberMe ? 86400*7 : 0;
			$session = Yii::$app->session;
			session_set_cookie_params($lifetime);
			$session['loginname']=$this->username;
			$session['uid']=$this->uid;
			$session['isLogin']=1;
			//更新数据
			//$this->updateAll(['logintime'=>time(),'loginip'=>ip2long(Yii::$app->request->userIP)],'username=:user',[':user'=>$this->user]);
			return (bool)$session['isLogin'];
		}
		return false;
	}
	//前台登录 验证密码
	public function validatePass()
	{
		if(!$this->hasErrors()){
			//根据用户名或邮箱验证
			$data = self::find()->where('(username=:useroremail or useremail=:useroremail) and userpass=:userpass',[':useroremail'=>$this->useroremail,':userpass'=>md5($this->userpass)])->one();
			if(is_null($data)){
				$this->addError("useroremail","用户名或密码错误");
			}
			$this->uid = $data->uid;
			$this->username = $data->username;
		}
	}
	//前台通过邮件进行注册
	public function mailreg($data)
	{
		$this->scenario = 'mailreg';
		//系统自动生成用户名及密码
		$data['Member']['username']='mw_'.uniqid();
		$data['Member']['userpass']=uniqid();
		if($this->load($data) && $this->validate())
		{
			//先发送邮件在插入到数据库
			$mailer = Yii::$app->mailer->compose('mailreg',['username'=>$data['Member']['username'],'userpass'=>$data['Member']['userpass']]);
			$mailer->setFrom("yiishop@163.com");
			$mailer->setTo($data['Member']['useremail']);
			$mailer->setSubject("注册成功-yiishop");
			if($mailer->send())
			{
				//插入到数据库
				$this->username=$data['Member']['username'];
				$this->userpass=md5($data['Member']['userpass']);
				if($this->save(false))
				{
					return true;
				}
			}
		}
		return false;
	}
}