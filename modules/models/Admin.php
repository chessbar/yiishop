<?php 
namespace app\modules\models;
use Yii;
use yii\db\ActiveRecord;
class Admin extends ActiveRecord
{
	public $rememberMe = true;
	public $repwd;
	public $oldpwd;
	//public $createtime;
	public static function tableName()
	{
		return "{{%admin}}";
	}
	public function attributeLabels()
	{
		return [
			'user'=>'管理员账号',
			'email'=>'管理员邮箱',
			'pwd'=>'管理员密码',
			'repwd'=>'确认密码',
			'oldpwd'=>'旧密码',
		];
	}
	public function rules()
	{
		return[
			['user','required','message'=>"管理员账号不能为空","on"=>['login','seekpass','changepass','adminadd','changeemail','changepwd']],
			['user','unique','message'=>"账号已注册","on"=>['adminadd']],
			['pwd','required','message'=>"管理员密码不能为空","on"=>['login','changepass','adminadd','changeemail','changepwd']],
			['rememberMe','boolean',"on"=>'login'],
			['pwd','validatePass',"on"=>['login','changeemail']],
			['oldpwd','validateOldPass',"on"=>['changepwd']],
			['email','required','message'=>"邮箱不能为空","on"=>['seekpass','adminadd','changeemail']],
			['email','email','message'=>"邮箱格式不正确","on"=>['seekpass','adminadd','changeemail']],
			['email','unique','message'=>"邮箱已注册","on"=>['adminadd','changeemail']],
			['email','validateEmail',"on"=>'seekpass'],
			['repwd','required','message'=>"确认密码不能为空","on"=>['changepass','adminadd','changepwd']],
			['repwd','compare','compareAttribute'=>'pwd','message'=>"两次密码输入不一致","on"=>['changepass','adminadd','changepwd']]
		];
	}
	public function validatePass()
	{
		if(!$this->hasErrors()){
			$data = self::find()->where('user=:user and pwd = :pwd',[":user"=>$this->user,":pwd"=>md5($this->pwd)])->one();
			if(is_null($data)){
				$this->addError("pwd","用户名或密码错误");
			}
		}
	}
	public function validateOldPass()
	{
		if(!$this->hasErrors()){
			$data = self::find()->where('user=:user and pwd = :pwd',[":user"=>$this->user,":pwd"=>md5($this->oldpwd)])->one();
			if(is_null($data)){
				$this->addError("oldpwd","用户名或密码错误");
			}
		}
	}
	public function login($data)
	{
		$this->scenario ="login";
		if($this->load($data) && $this->validate())
		{
			$lifetime = $this->rememberMe ? 24*3600*7 : 0;
			$session = Yii::$app->session;
			session_set_cookie_params($lifetime);
			$session['admin']=[
				'user'=>$this->user,
				'isLogin'=>1,
			];
			//更新数据
			$this->updateAll(['logintime'=>time(),'loginip'=>ip2long(Yii::$app->request->userIP)],'user=:user',[':user'=>$this->user]);
			return (bool)$session['admin']['isLogin'];
		}
		return false;
	}
	public function reg($data)
	{
		$this->scenario="adminadd";
		//$data['Admin']['createtime']=time();
		if($this->load($data) && $this->validate())
		{
			$this->pwd=md5($data['Admin']['pwd']);
			//$this->createtime=time();
			if($this->save(false))
			{
				return true;
			}
		}
		return false;
	}
	public function seekpass($data)
	{
		$this->scenario="seekpass";
		if($this->load($data) && $this->validate())
		{
			$time = time();
			$token=$this->createToken($data['Admin']['user'],$time);
			$mailer=Yii::$app->mailer->compose('seekpass',['user'=>$data['Admin']['user'],'time'=>$time,'token'=>$token]);
			$mailer->setFrom("yiishop@163.com");
			$mailer->setTo($data['Admin']['email']);
			$mailer->setSubject("yiishop-找回密码");
			if($mailer->send())
			{
				return true;
			}
		}
		return false;
	}
	public function validateEmail()
	{
		if(!$this->hasErrors())
		{
			$data=self::find()->where("user =:user and email=:email",[':user'=>$this->user,':email'=>$this->email])->one();
			if(is_null($data)){
				$this->addError("email","管理员邮箱不匹配");
			}
		}
	}
	public function createToken($user,$time)
	{
		return md5(md5($user).base64_encode(Yii::$app->request->userIP).md5($time));
	}
	public function changepass($data)
	{
		$this->scenario='changepass';
		if($this->load($data) && $this->validate())
		{
			return (bool)$this->updateAll(['pwd'=>md5($this->pwd)],'user=:user',[':user'=>$this->user]);
		}
		return false;
	}
	public function changeemail($data)
	{
		$this->scenario = 'changeemail';
		if($this->load($data) && $this->validate())
		{
			return (bool)$this->updateAll(['email'=>$this->email],'user=:user',[':user'=>$this->user]);
		}
		return false;
	}
	public function changepwd($data)
	{
		$this->scenario = 'changepwd';
		if($this->load($data) && $this->validate())
		{
			return (bool)$this->updateAll(['pwd'=>md5($this->pwd)],'user=:user',[':user'=>$this->user]);
		}
		return false;
	}
}