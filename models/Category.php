<?php 
namespace app\models;
use Yii;
use yii\db\ActiveRecord;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
class Category extends ActiveRecord
{
	public static function tableName()
	{
		return "{{%category}}";
	}
	public function attributeLabels()
	{
		return [
			'pid'=>'上级分类',
			'title'=>'分类名称'
		];
	}
	public function rules()
	{
		return [
			['pid','required','message'=>"上级分类不能为空"],
			['title','required','message'=>"分类名称不能为空"],
			['createtime','safe'],
		];
	}
	public function add($data)
	{
		$data['Category']['createtime']=time();
		if($this->load($data) && $this->save()){
			return true;
		}
		return false;
	}
	public function getData()
	{
		$cates = self::find()->all();
		$cates = ArrayHelper::toArray($cates);
		return $cates;
	}
	public function getTree($cates,$pid=0,$level=0,$prefix="|---")
	{
		$tree=[];
		foreach ($cates as $cate) {
			if($cate['pid'] == $pid){
				$cate['title'] = str_repeat($prefix,$level).$cate['title'];
				$tree[]=$cate;
				$tree = array_merge($tree,$this->getTree($cates,$cate['cateid'],$level+1));
			}
		}
		return $tree;
	}
	public function getOptions($type=true)
	{
		$data = $this->getData();
		$tree = $this->getTree($data);
		$options = [];
		if($type){
			$options[0]="==顶级分类==";
		}
		foreach ($tree as $cate) {
			$options[$cate['cateid']] = $cate['title'];
		}
		return $options;
	}
	public function mod($data)
	{
		if($this->load($data) && $this->save()){
			return true;
		}
		return false;
	}
	public function getMenu()
	{
		$data = self::getData();
		return self::getMenuTree($data);
	}
	private function getMenuTree($data,$pid=0,$level=0)
	{
		$tree=[];
		foreach ($data as $k=>$cate) {
			if($cate['pid'] == $pid){
				$tree[$k]=$cate;
				$tree[$k]['_son']=self::getMenuTree($data,$cate['cateid'],$level+1);
			}
		}
		return $tree;
	}
	/**
	 * 获得栏目的所有的子栏目
	 * @param  id $cateid 父级栏目id
	 * @return array
	 */
	public function getSonIds($cateid)
	{
		$data = self::getData();
		$ids=[];
		foreach ($data as $k => $cate) {
			if($cate['pid'] == $cateid){
				$ids[]=(int)$cate['cateid'];
				$ids = array_merge($ids,self::getSonIds($cate['cateid']));
			}
		}
		return $ids;
	}
}







