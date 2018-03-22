<?php

namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Session;
use think\open51094;
use think\Image;
use think\Request;
use app\index\model\User as UserModel;
use app\index\model\Ucpaas as UcpaasModel;//引用手机验证码类
class User extends controller
{
 	public function notice($msg, $url = null, $sec = 3)
	{
		if (empty($url)) {
			$url = $_SERVER['HTTP_REFERER'];
		}
		$this->assign('msg', $msg);
		$this->assign('url', $url);
		$this->assign('sec', $sec);
		return $this->fetch('notice');
	}
    public function register()
    {
    	return $this->fetch();
    }
    //注册
    public function doregister()
    {
    	//得到用户名
    	 $username = $this->request->post('username');
    	 $result = UserModel::getByUsername($username);
    	 // dump($result);
    	 if (!empty($result)) {
    	 	 echo "<script>alert('用户名已经存在');history.go(-1);</script>";
                exit;
    	 }
    	 //得到密码
    	 $pwd = $this->request->post('password');
    	 $repwd = $this->request->post('pass');
    	 //判断用户名长度
    	 if (strlen($username) < 3 || 12 < strlen($username)) {
    	 	 // return  $this->notice('二货，你是要学杨洋吗？用户名长度不合适');
             echo "<script>alert('二货，你是要学杨洋吗？用户名长度不合适');history.go(-1);</script>";
                exit;
    	 //判断密码长度
    	 } else if (strlen($pwd) < 3 || 10 < strlen($pwd)) {
    	 	// return  $this->notice(' 密码长度不合适');
            echo "<script>alert('密码长度不合适');history.go(-1);</script>";
                exit;
    	 //判断两次密码是否一致
    	 } else if ($pwd != $repwd) {
    	 	// return  $this->notice('傻逼两次密码不一样');
            echo "<script>alert('傻逼两次密码不一样');history.go(-1);</script>";
                exit;
    	 	
    	 }
    	 $pwd = md5($pwd);
    	//判断邮箱的格式是否正确
    	$email = $this->request->post('email');

		$pattern = "/^\w+@(\w+\.)+\w+$/";

		if(preg_match($pattern, $_POST['email'])){ 

		    echo '';  
		} else{  
            echo "<script>alert('邮箱格式不正确');history.go(-1);</script>";
            exit;

		}
		//手机号
		$phone = $this->request->post('iphone');
		//手机验证码
		$phoneyzm = $this->request->post('phoneyzm');

		// 手机验证码
		if(!empty($_SESSION['code1'])){
			if (strcmp($phoneyzm, $_SESSION['code1'])) {
				$msg = '二货，手机验证码输入密码不一样';
				return $this->notice($msg);
			}
		}
		//注册积分加10分
		$grade = 10;

		//插入数据库
		$result = Db::execute("insert into video_user(username , password , email , grade , phone) 
			values 
			('$username' , '$pwd' , '$email' , '$grade' , '$phone')");
		// dump($result);
		if ($result) {
			return  $this->notice('恭喜你呀，小子！');
		}
    }
    //显示登录页面
  	public function login()
    {
        return $this->fetch();
    }
    //登录判断
    public function dolg()
    {
    	//登录时提交过来的名字 
    	$username= $this->request->post('username');
    	//判断数据库是否有这个名字
        $result = UserModel::getByUsername($username);

        if (empty($result)) { 
            // 为空的话，这个用户名不存在    
            echo json_encode(['state'=>0]);
            exit;
        } else {
            echo json_encode(['state'=>1]);
        }
    }
    //点击登录按钮执行的方法
    public function dologin()
    {

    	$post = input('post.');
    	//第三方登录
    	if (empty($post)){
    		$open = new open51094();
    		$code = $_GET['code'];
    		$data = $open->me($code);
    		$username = $data['name'];
    		$type = $data['from'];
    		$img = $data['img'];
    		$sex = $data['sex'];
    		if ($type == 'weibo'){
    			$type = "微博";
    		} else {
    			$type = 'QQ';
    		}
    		//判断是否登录过
    		$result = Db::name('user')->where('username' , "$username")->select();
    		if ($result) {
    			Session::set('username' , $username);
    			return $this->notice('登录成功','/index/index/index');
    			exit;
    		}else {
    			//没有登陆
    			$result = Db::execute("insert into video_user(username , picture , sex , type) 
							values 
							('$username' , '$img' , '$sex' , '$type')");
    		}
    	}else {
		    	//提交过来的用户名 、 密码 、
		    	$username= $this->request->post('username');

		        $password = $this->request->post('password');
		        //根据姓名查询
		        $result = UserModel::getByUsername($username);
		        // dump($result);
		        //判断用户
		        if (empty($result)) {
		            return  $this->notice('登陆失败');          
		        }
		        //查询密码是否匹配
		        $pwd = Db::table('video_user')->where('username',$username)->value('password');
		        //判断是否登陆成功
		        if (md5($password) == $pwd) {
		        	$id = $result->uid;
		        	// dump($id);
		            $grade = $result->grade;
		            $allowlogin = $result->allowlogin;
		            // $regip = $result->regip;
		            $picture = $result->picture; 
		            //是否被禁止登录
		            if ($allowlogin == 1) {
		                echo "<script>alert('您的用户被锁定,请联系管理员');history.go(-1);</script>";
		                exit;
		            }
		            //登陆成功加 5积分
		            $grade = ($grade + 5);
		            Db::table('video_user')->where('uid',$id)->update(['grade' => $grade]);

		            //登陆成功后将登陆错误次数更新为0
		            Db::table('video_user')->where('uid',$id)->update(['error'=>0]);

		             //把用户信息放入session,以后用
		            Session::set('username' , $username);
		            Session::set('uid' , $id);
		            Session::set('grade' , $grade);
		            Session::set('picture' , $picture);
		            return $this->notice('登陆成功', '/index/index/index');
		        } else {
		        	 $id = $result->uid;
		        	//判断五次登录
		        	$error = Db::table('video_user')->where('uid',$id)->value('error');
		        	// dump($error);
		        	if ($error <5) {
		                $error++;
		                UserModel::where('uid',$id)->update(['error'=>"$error"]); 
		                //5次锁定账号
		            }  else {
		                UserModel::where('uid',$id)->update(['allowlogin'=>1]);
		                echo "<script>alert('您的用户被锁定,请重新找回密码登陆');history.go(-1);</script>";
		                exit;
		            }
		            return  $this->notice('登陆失败');
		        }
		  }
    }
   	public function exit()
	{
		Session::delete('username');
		
		return $this->notice('退出成功', '/index/index/index');
	}

    //个人设置
    public function set()
    {
    	return $this->fetch();
    }
   
    //修改个人资料
    public function xiugai(Request $request)
    {
    	//得到邮箱
    	$email= $this->request->post('Email');
    	//得到电话
    	$phone = $this->request->post('phone');
    	//得到地方
    	$place = $this->request->post('place');
    	//得到生日的年月日
    	$year = $this->request->post('brithyear');
    	$month = $this->request->post('brithmonth');
    	$day = $this->request->post('brithday');
    	//性别
    	$sex = $this->request->post('sex');
    	if ($sex == '男') {
    		$sex = 1;
    	} else if ($sex == '女') {
    		$sex = 3;
    	} else {
    		$sex = 2;
    	}

    	$birthday = $year.'-'.$month.'-'.$day;
    	// dump($birthday);
    	$data['sex'] = $sex;
    	$data['email'] = $email;
    	$data['phone'] = $phone;
    	$data['place'] = $place;
    	$data['birthday'] = $birthday;

    	$user = Session::get('username');
    	  // 获取表单上传文件 例如上传了001.jpg
    	$file = request()->file('image');
    	// dump($file);
    	if (empty($file)) {
    		 $result = Db::name('user')->where('username',$user)->update($data);
    		 // dump($result);
             return $this->notice('修改成功');
			} else {
				 if($file){
				 	$info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
				 	// dump($info);
				 	if($info){
					 		// 成功上传后 获取上传信息
				            //输出 jpg
				            // echo $info->getExtension();
				            //输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
				 			$data['picture'] = '/uploads/'.$info->getSaveName();
				           	$result = Db::name('user')->where('username',$user)->update($data);
				           	 return $this->notice('修改成功');
				           // 输出 42a79759f284b767dfcb2a0197904287.jpg
				            // echo $info->getFilename(); 
			            }else{
			            	// 上传失败获取错误信息
			            	 echo $info->getFilename();
			            	}
			            }
			}	
	}
	 //个人修改
    public function data()
    {
    	$username = Session::get('username');
    	$this->assign('username',$username);
    	//查询登录人的信息
    	$result = Db::query("select * from video_user where username = '$username'");
    	$this->assign('result',$result);

    	// dump($result[0]['picture']);
    	return $this->fetch();
    }
    //修改密码的页面
    public function password()
    {
    	return $this->fetch();
    }
    //找回密码
    public function found()
    {
    	return $this->fetch();
    }
    public function phoneyzm()
    {
    	//初始化必填
		$options['accountsid']='06ed8083ddce5078f9effb3cdbc889e0';
		$options['token']='f09a5a88febdd49dacffec57eee311c3';

		//初始化 $options必填
		$ucpass = new UcpaasModel($options);

		//开发者账号信息查询默认为json或xml
		header("Content-Type:text/html;charset=utf-8");


		//封装验证码
		$str = '1234567890123567654323894325789';
		$code = substr(str_shuffle($str),0,5);
		// $_SESSION['code']=$code;
		//短信验证码（模板短信）,默认以65个汉字（同65个英文）为一条（可容纳字数受您应用名称占用字符影响），超过长度短信平台将会自动分割为多条发送。分割后的多条短信将按照具体占用条数计费。
		$appId = "fbf4c9c15e8346cbad7a5ccf209a635d";
		//给那个手机号发送
		$to = $_GET['phone'];

		$templateId = "257916";
		//这就是验证码
		$param=$code;
		$_SESSION['code1']= $param;

		echo $ucpass->templateSMS($appId,$to,$templateId,$param);
    }

}