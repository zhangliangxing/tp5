<?php
namespace app\index\controller;
use think\Controller;
use think\Session;
use think\Db;
class Index extends Controller
{
    public function index()
    {
    	$username = Session::get('username');
    	$this->assign('username',$username);
    	//查询大板块
    	$result = Db::query('select * from video_block where pid = 0');
    	$this->assign('result',$result);
    	// dump($result);s
    	//查询小版块
    	$rel = Db::query('select * from video_block where pid != 0');
    	$this->assign('rel',$rel);
    	// dump($rel);
        //查询视频
        $rell = Db::query('select * from video_shipin where ispay = 0');
        $this->assign('rell',$rell);
        // $id = Db::name('block')->where('pid' , '$pid')->value('classname');
        // $zuixin = Db::query('select * from video_shipin where ');
        //查询本周的视频
        $zuixin = Db::name('shipin')->whereTime('utime', 'week')->select();
        // dump($zuixin);
        $this->assign('zuixin',$zuixin);
        //根据点赞的个数将视频降序查出来
        $ress = Db::table('video_shipin')->order('count desc')->paginate(5);;
        // dump($ress);
        $this->assign('ress' , $ress);

      
        $data2 = $ress->render();
    
        $this->assign('data2',$data2);
        // $this->assign('video',$video);
        return $this->fetch();
    }
    public function list()
    {
        $username = Session::get('username');
        $this->assign('username',$username);
        //查询大板块
        $result = Db::query('select * from video_block where pid = 0');
        $this->assign('result',$result);
        // dump($result);
        //查询小版块
        $rel = Db::query('select * from video_block where pid != 0');
        $this->assign('rel',$rel);
        $bid=$_GET['bid'];
        //得到点击的小版块的名字
        $name = Db::name('block')->where('bid' , "$bid")->value('classname');
        dump($name);
        $this->assign('name' , $name);
        //查询大板块
        $pid = Db::name('block')->where('bid' , "$bid")->value('pid');
        $daname = Db::name('block')->where('bid' , "$pid")->value('classname');
        dump($daname);
        $this->assign('daname' , $daname);
        

        //查询视频
        $rell = Db::query("select * from video_shipin where cid = '$bid'");
        $this->assign('rell',$rell);
        return $this->fetch();
    }
    //点击大板块的时候的页面
    public function slist()
    {
        $username = Session::get('username');
        $this->assign('username',$username);
        //查询大板块
        $result = Db::query('select * from video_block where pid = 0');
        $this->assign('result',$result);
        // dump($result);
        //查询小版块
        $rel = Db::query('select * from video_block where pid != 0');
        $this->assign('rel',$rel);
        //得到大板块的id
        $bid = $_GET['bid'];
        //查询点击的大板块的名字
        $bigname = Db::name('block')->where('bid' , "$bid")->value('classname');
        // dump($bigname);die;
        $this->assign('bigname',$bigname);
        //查询当前大板块下面的小版块
        $resl = Db::name('block')->where('pid' , "$bid")->select();
        // dump($resl);
        $this->assign('resl' , $resl);
        //查询此时每个小版块下面的视频信息
        $small = Db::table('video_block b , video_shipin s')->where("s.cid = b.bid and b.pid = '$bid'")->select();
        // dump($small);
        $this->assign('small' , $small);
        return $this->fetch();
    }
    public function bofang()
    {
        $username = Session::get('username');
        $this->assign('username',$username);
        //查询当前用户的id
        $uid = Db::name('user')->where('username' , "$username")->value('uid');
        // dump($uid);
        //查询大板块
        $result = Db::query('select * from video_block where pid = 0');
        $this->assign('result',$result);
        // dump($result);
        //查询小版块
        $rel = Db::query('select * from video_block where pid != 0');
        $this->assign('rel',$rel);
        //根据点赞的个数将视频降序查出来
        $ress = Db::table('video_shipin')->order('count desc')->field('title')->select();
        // dump($ress);
        $this->assign('ress' , $ress);
        $id=$_GET['id'];
        $this->assign('id' , $id);
        //查询当前视频的点赞总数
        $count = Db::name('shipin')->where('id' , "$id")->value('count');
        // dump($count);
        $this->assign('count' , $count);
        //查询当前视频的名字
        $title = Db::name('shipin')->where('id' , "$id")->value('title');
        $this->assign('title' , $title);
        //查询当前视频所在的小版块
        $cid = Db::name('shipin')->where('id',"$id")->value('cid');
        $bname = Db::name('block')->where('bid',"$cid")->value('classname');
        $this->assign('bname',$bname);
        //查询视频所在的大板块
        $pid = Db::name('block')->where('bid' , "$cid")->value('pid');
        // dump($pid);
        $dname = Db::name('block')->where('bid' , "$pid")->value('classname');
        // dump($dname);
        $this->assign('dname',$dname);
        
        //查询当前视频的所有评论以及用户
        $sid = $_GET['id'];
        // dump($sid);
        $all = Db::table('video_idea i , video_user u')->where("i.uid = u.uid and i.sid = '$sid' and i.pid = 0")->select();
        // dump($all);
        $this->assign('all' , $all);
        // dump($pid);
        //查询当前视频评论者的回复

        $ell = Db::table('video_idea i , video_user u')->where("i.uid = u.uid and i.pid != 0")->select();
        // dump($ell);

        $this->assign('ell' , $ell);
        $rll = Db::table('video_idea i , video_user u')->where("i.uid = u.uid and i.pid != 0")->select();
        // dump($rll);
        $this->assign('rll' , $rll);
         return $this->fetch();
    }
   
    public function header()
    {
        $username = Session::get('username');
        $this->assign('username',$username);

        //查询大板块
        $result = Db::query('select * from video_block where pid = 0');
        $this->assign('result',$result);
        // dump($result);
        //查询小版块
        $rel = Db::query('select * from video_block where pid != 0');
        $this->assign('rel',$rel);
        // dump($rel);
        //查询视频
        $rell = Db::query('select * from video_shipin where ispay = 0');
        $this->assign('rell',$rell);
        // $id = Db::name('block')->where('pid' , '$pid')->value('classname');
        // $zuixin = Db::query('select * from video_shipin where ');
        //查询本周的视频
        $zuixin = Db::name('shipin')->whereTime('utime', 'week')->select();
        // dump($zuixin);
        $this->assign('zuixin',$zuixin);
        //根据点赞的个数将视频降序查出来
        $ress = Db::table('video_shipin')->order('count desc')->select();
        // dump($ress);
        $this->assign('ress' , $ress);
        return $this->fetch();
    }
    public function search()
    {
        $username = Session::get('username');
        $this->assign('username',$username);

        //查询大板块
        $result = Db::query('select * from video_block where pid = 0');
        $this->assign('result',$result);
        // dump($result);
        //查询小版块
        $rel = Db::query('select * from video_block where pid != 0');
        $this->assign('rel',$rel);
        return $this->fetch();
    }
	
	public function test()
	{
			return '11';
	}

}
