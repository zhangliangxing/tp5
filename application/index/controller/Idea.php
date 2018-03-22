<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Session;
use app\index\model\Idea as IdeaModel;
class Idea extends controller
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
	public function pinlun()
    {
        if (empty($username)) {
             echo "<script>alert('先登录好吗！！！');history.go(-1);</script>";
            exit;
        }
        $username = Session::get('username');
        $this->assign('username',$username);
        
        //视频的id
        $id = $_GET['id'];
        // $counts = Db::name('shipin')->where('id' , "$id")->value('count');
        // $data['count'] = $counts + 1;
        //修改 count的总数
        $content = $this->request->post('ping');
        // dump($content);
        //当前登录人的id
        $uid = Db::name('user')->where('username' , "$username")->value('uid');
        // dump($uid);
        $data['uid'] = $uid;
        $data['sid'] = $id;
        $data['content'] = $content;
        $result = Db::name('idea')->insert($data);
        // dump($result);
        // dump($result);
        if ($result) {
           return $this->notice('评论成功');
           exit;
        } else {
        	return $this->notice('评论失败');
        	exit;
        }
    }
    public function return()
    {
        if (empty($username)) {
             echo "<script>alert('先登录好吗！！！');history.go(-1);</script>";
            exit;
        }
    	//回复谁的id
    	$pid = $_GET['pid'];
    	// dump($pid);
    	//回复视频的id
    	$id = $_GET['id'];
    	// dump($id);
    	//回复者的id
    	$username = Session::get('username');
        $this->assign('username',$username);
        $uid = Db::name('user')->where('username' , "$username")->value('uid');
        // dump($uid);
    	//回复内容
    	$content = $this->request->post('hui');
    	// dump($content);
    	// dump($uid);   
    	$hid = $_GET['hid'];
    	// dump($hid);die;
    	$data['hid'] = $hid;
    	$data['uid'] = $uid;
    	$data['pid'] = $pid;
    	$data['sid'] = $id;
    	$data['content'] = $content;
    	if (empty($content)) {
    		return $this->notice('回复失败');
    		exit;
    	}
    	 $result = Db::name('idea')->insert($data);
    	
        // dump($result);
    	  if ($result) {
           return $this->notice('回复成功');
           exit;
        } else {
        	return $this->notice('回复失败');
        	exit;
        }

    }
}