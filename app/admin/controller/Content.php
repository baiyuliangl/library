<?php
namespace app\admin\controller;
use think\Db;
use think\Model;
use app\admin\model\Contents;
class Content extends Base{
	//书库内容管理
	public function Content(){
		$total = Db::name('contents')->select();
		$num = count($total);
		$man = Db::name('contents')->where('Content_Channel',0)->select();
		$mannum = count($man);
		$woman = Db::name('contents')->where('Content_Channel',1)->select();
		$womannum = count($woman);

		$model = new Contents;
		$models = $model->paginate(5);
		$this->assign('num',$num);
		$this->assign('page',$models);
		$this->assign('mannum',$mannum);
		$this->assign('womannum',$womannum);
		$this->assign('total',$models);
		return $this->fetch();
	}
	public function Add(){
		$res = array(array(
				'Content_BookName' => '',
				'Content_Author' => '',
				'Content_Channel' => '',
				'Content_BookType' => '',
				'Content_Mark' => '',
				'Content_From' => '',
				'Content_Descrition' => '',
				'Content_Picture' => ''
				));
		return $this->fetch('Content/Add',[
				'res' => $res
			]);
	}
	//详情页面
	public function Details(){
		$bookid = input('id');
		$res = Db::name('contents')->where('Contents_id',$bookid)->select();
		// echo "<pre>";
		// print_r($res);exit;
		return $this->fetch('Content/Add',
				['res'=>$res]
			);
	}
	//保存书籍录入
	public function Save(){
		$model = new Contents;
		// $test = input('post.');
		// $model = model('contents');
		$file = request()->file('Content_Picture');
		// echo "<pre>";
		// print_r($test);exit;
		// 移动到框架应用根目录/public/uploads/ 目录下
	    if($file){
	        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
	        if($info){
	            // 成功上传后 获取上传信息
	            // 输出 jpg
	            echo $info->getExtension();
	            // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
	            echo $info->getSaveName();
	            // 输出 42a79759f284b767dfcb2a0197904287.jpg
	            echo $info->getFilename(); 
	            $path ='\uploads\\'.$info->getSaveName();
	        }else{
	            // 上传失败获取错误信息
	            echo $file->getError();
	        }
	    }
	    if(input('Contents_id')!=''){
	    	//更新
			$model->save([
				'Contents_id' => input('Contents_id'),
				'Content_BookName' => input('Content_BookName'),
				'Content_Author' => input('Content_Author'),
				'Content_Channel' => input('Content_Channel'),
				'Content_BookType' => input('Content_BookType'),
				'Content_Mark' => input('Content_Mark'),
				'Content_From' => input('Content_From'),
				'Content_Descrition' => input('Content_Descrition'),
				'Content_Picture'=> $path
			],['Contents_id' => input('Contents_id')]);
	    }else{
	    	//新增
	    	$model->data([
				'Content_BookName' => input('Content_BookName'),
				'Content_Author' => input('Content_Author'),
				'Content_Channel' => input('Content_Channel'),
				'Content_BookType' => input('Content_BookType'),
				'Content_Mark' => input('Content_Mark'),
				'Content_From' => input('Content_From'),
				'Content_Descrition' => input('Content_Descrition'),
				'Content_Picture'=> $path
			]);
			$model->save();
	    }
		if($model->save()){
			$this->error('保存失败',url('admin/Content/Add'));
		}else{
			$this->success('保存成功',url('admin/Content/Content'));
		}
	}
	
}