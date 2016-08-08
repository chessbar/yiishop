<?php 
namespace app\models;
use Yii;
use yii\db\ActiveRecord;
class Product extends ActiveRecord
{
	const AK='V3dANmFAxWZDz8F6i7vYSg9VpA9603CGwj2FQ1XK';
	const SK='98bYHN3TQCGQzQTi9lPALWgtetVMAQ9rxkL7oMV7';
	const DOMAIN ='ob7j2k33h.bkt.clouddn.com';
	const BUCKET ='blackberry';
	public $cate;
	public static function tableName()
	{
		return "{{%product}}";
	}
	public function rules()
	{
		return [
			['title','required','message'=>'商品名称不能为空'],
			['desc','required','message'=>'商品描述不能为空'],
			['cateid','required','message'=>'商品分类不能为空'],
			['price','required','message'=>'商品价格不能为空'],
			[['price','saleprice'],'number','min'=>0.01,'message'=>'价格必须为数字，最小0.01'],
			['num','integer','min'=>0,'message'=>'库存必须为整数'],
			[['summary','issale','ishot','isshelve','isrecommend','pics','createtime','updatetime'],'safe'],
			[['cover'],'required'],
		];
	}
	public function attributeLabels()
	{
		return [
			'cateid'=>"商品类别",
			'title'=>'商品名称',
			'summary'=>'商品摘要',
			'desc'=>'商品描述',
			'price'=>'商品价格',
			'ishot'=>'是否热销',
			'issale'=>'是否促销',
			'saleprice'=>'促销价格',
			'num'=>'商品库存',
			'isshelve'=>'是否上架',
			'isrecommend'=>'是否推荐',
			'cover'=>'商品封面',
			'pics'=>'商品照片',
		];
	}
	public function add($data)
	{
		$data['Product']['createtime']=time();
		$data['Product']['updatetime']=time();
		if($this->load($data) && $this->save())
		{
			return true;
		}
		return true;
	}
}