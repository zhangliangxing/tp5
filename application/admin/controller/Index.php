<?php
namespace app\admin\controller;

require '../vendor/sdk/autoload.php';
// 引入鉴权类
use Qiniu\Auth;
// 引入上传类
use Qiniu\Storage\UploadManager;
define('WAILIAN', 'p3pucp0o4.bkt.clouddn.com/');

use think\Controller;
use think\Db;

use lib\Page;

class Index extends Controller
{
    // 登录页面
    public function login()
    {
        return $this->fetch();
    }
    public function dologin()
    {
        $name = input('post.username');
        $pwd = md5(input('post.pass'));
        // 判断用户名
        $result = Db::name('user')->where('username',$name)->select();
        if (!$result){
            $this->error('用户名不存在');
            // exit("<script>alert('用户名不存在');history.go(-1);</script>");
        }

        // 判断是否可以登录后台
        if ($result[0]['level']>2){
            $this->error('对不起，您没有权限','index/index/index');
            // exit("<script>alert('对不起，您没有权限');location='/index/index/index';</script>");
        }
        if ($pwd != $result[0]['password']){
            $this->error('密码不正确');
            // exit("<script>alert('密码不正确');history.go(-1);</script>");
        }
        // 查询用户角色表,找到用户的角色id
        $uid = $result[0]['uid'];
        $rid = Db::name('urole_user')->where('uid',$uid)->value('rid');
        // 查询角色权限表，找到角色拥有的权限
        $node = Db::name('uaccess')->where('rid',$rid)->select();
        // 将权限id遍历到数组中
        $nids = [];
        foreach ($node as $k=>$v){
            $nids[] = $v['nid'];
        }
        // 找到权限
        $nodename = Db::name('unode')->where('nid','in',$nids)->select();
        //$nodename = $this->getTree($nodename);
        // dump($nodename);die;
        // 将权限跟信息放入session中
        //dump($picture);die;
        session('nodename',$nodename);
        session('username',$name);
        $this->success('登录成功','/admin/index/index');
    }

    // 登录后首页
    public function index()
    {
        if (empty(session('username'))){
            $this->error('请先登录','admin/index/login');
        }
        $username = session('username');
        $nodename = session('nodename');
        // 查询头像
        $result = Db::name('user')->where('username',$username)->value('picture');
        $this->assign('picture',$result);
        $this->assign('username',$username);
        $this->assign('nodename',$nodename);
        return $this->fetch();
    }

    // 欢迎页面
    public function welcome()
    {
        return $this->fetch();
    }






/*----------------------------------轮播图管理------------------------------------*/
    /*public function bannerlist()
    {
        $result = Db::name('banner')->paginate(3);
        $this->assign('result',$result);
        return $this->fetch();
    }*/

    public function bannerlist()
    {
        $result = Db::name('banner')->select();
        $count = count($result);
        $this->assign('count',$count);
        return $this->fetch();
    }

    public function show()
    {
        $result = Db::name('banner')->select();
        $total = count($result);
        $page = new Page(3,$total);
        $limit = $page->limit();
        $ret = Db::name('banner')->limit($limit)->select();
        $allpage = $page->allPage();
        $value['ret'] = $ret;
        $value['allpage'] = $allpage;
        echo json_encode($value);
    }

    // 添加轮播图
    public function banneradd()
    {
        return $this->fetch();
    }
    // 执行添加轮播图
    public function addbanner()
    {
        // 获取表单上传文件
        $file = request()->file('file');
        // $file = input('post.file');
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info1 = $file->move(ROOT_PATH . 'public' . DS . 'static' . DS . 'uploads');
        $data['pic'] = '/static/uploads/' . $info1->getSaveName();
        //
        $data['name'] = input('post.link');
        $data['info'] = input('post.desc');
        $result = Db::name('banner')->insert($data);
        if($result)
        {
            exit("<script>alert('添加成功');location='/admin/index/bannerlist';</script>");
        }else{
            exit("<script>alert('添加失败');history.go(-1);</script>");
        }
    }
    // 删除轮播图
    public function delbanner()
    {
        $banid = input('get.banid');
        $result = db('banner')->where('banid',$banid)->delete();
        if ($result){
            return json_encode(['state'=>1]);
        }else {
            return json_encode(['state'=>0]);
        }
    }
    // 批量删除轮播图
    public function delall()
    {
        foreach ($_GET['all'] as $key => $value){
            $result = Db::name('banner')->delete($value);
        }
        if ($result){
            return json_encode(['state'=>1]);
        }else {
            return json_encode(['state'=>0]);
        }
    }



    // 无限极分类
    function getTree4($list, $pid=0, $level=1)
    {
        static $newlist = array();
        foreach($list as $key => $value)
            {
            if($value['pid']==$pid)
                {
                $value['level'] = $level;
                $newlist[] = $value;
                unset($list[$key]);
                $this->getTree4($list, $value['bid'], $level+1);
                }
            }
        return $newlist;
    }

/*------------------------------------版块管理---------------------------------------------*/
    public function category()
    {
        $result = Db::name('block')->select();
        $result = $this->getTree4($result);
        $count = count($result);
        $this->assign('count',$count);
        $this->assign('result',$result);
        return $this->fetch();
    }
    // 添加版块
    public function addblock()
    {
        if (!empty(input('post.name'))){
            $name = input('post.name');
            // 查询是否已经存在
            $info = Db::name('block')->where('classname',$name)->select();
            if ($info){
                return json_encode(['state'=>0]);
            }


            $data['pid'] = input('post.pid');
            $data['classname'] = input('post.name');
            $result = Db::name('block')->insert($data);
            if ($result){
                echo json_encode(['state'=>1]);
            }else {
                echo json_encode(['state'=>0]);
            }
        }

    }
    // 批量删除版块
    public function delblocks()
    {
        foreach ($_GET['all'] as $key => $value){
            $result = Db::name('block')->delete($value);
        }
        if ($result){
            return json_encode(['state'=>1]);
        }else {
            return json_encode(['state'=>0]);
        }
    }
    // 删除版块
    public function delblock()
    {
        $bid = input('get.bid');
        $result = db('block')->where('bid',$bid)->delete();
        if ($result){
            return json_encode(['state'=>1]);
        }else {
            return json_encode(['state'=>0]);
        }
    }
    // 修改版块
    public function updblock()
    {
        $bid = input('post.id');
        $name = input('post.name');
        $result = Db::name('block')->where('bid',$bid)->update(['classname'=>$name]);
        if ($result){
            echo json_encode(['state'=>1]);
        }else {
            echo json_encode(['state'=>0]);
        }
    }



/*--------------------------------------评论管理-----------------------------------------*/
    public function commentlist()
    {
        $result = Db::table('video_idea i,video_user u')->where('i.uid=u.uid')->field('u.username,i.content,i.addtime,i.id')->paginate(5);
        $count = count($result);
        $this->assign('count',$count);
        $this->assign('result',$result);
        return $this->fetch();
    }
    // 删除评论
    public function delidea()
    {
        $id = input('get.id');
        $result = db('idea')->where('id',$id)->delete();
        if ($result){
            return json_encode(['state'=>1]);
        }else {
            return json_encode(['state'=>0]);
        }
    }
    // 批量删除评论
    public function delideas()
    {
        foreach ($_GET['all'] as $key => $value){
            $result = Db::name('idea')->delete($value);
        }
        if ($result){
            return json_encode(['state'=>1]);
        }else {
            return json_encode(['state'=>0]);
        }
    }

/*-------------------------------------视频管理----------------------------------------------*/

    public function sea()
    {
        $title = input('post.username');
        //dump($title);
        //$result = Db::table('video_shipin s,video_block b')->where('s.cid=b.bid AND s.title LIKE "%$title%"')->field('s.id,s.title,s.picture,s.content,s.play,s.ispay,b.classname')->paginate(5);
        $result = Db::query("select * from video_shipin s,video_block b where s.cid=b.bid and s.title like '%$title%' limit 5");
        //$result = Db::table('video_shipin s,video_block b')->where('s.title','like',"%$title%")->where("s.cid=b.bid")->field('s.id,s.title,s.picture,s.content,s.play,s.ispay,b.classname')->paginate(5);
        //dump($result);
        $count = count($result);
        $this->assign('result',$result);
        $this->assign('count',$count);
        return $this->fetch();
    }
    public function videolist()
    {
        $result = Db::table('video_shipin s,video_block b')->where('s.cid=b.bid')->field('s.id,s.title,s.picture,s.content,s.play,s.ispay,b.classname')->paginate(5);
        $count = count($result);
        $this->assign('result',$result);
        $this->assign('count',$count);
        /*$result = Db::table('video_shipin s,video_block b')->where('s.cid=b.bid')->field('s.id,s.title,s.picture,s.content,s.play,s.ispay,b.classname')->paginate(5);
            $count = count($result);
            $this->assign('result',$result);
            $this->assign('count',$count);*/
        return $this->fetch();
    }
    // 添加视频
    public function videoadd()
    {
        // 查询版块
        $result = Db::name('block')->where('pid','>','0')->select();
        $this->assign('result',$result);
        return $this->fetch();
    }
    // 上传视频
    // $accessKey ="PnMSrh7raiJw22TYoxgNBogr6oAmH7njpuNisSuZ";
    // $secretKey = "HmKZ4jRefUa6z29xg1Y2uLf_o1tWg4EkY42lLH3O";
    public function addvideo()
    {
        $vname = $_FILES['url']['type'];
        //获取文件的名字
        $key = $_FILES['url']['name'];
        $filePath=$_FILES['url']['tmp_name'];
        //获取token值
        $accessKey = 'PnMSrh7raiJw22TYoxgNBogr6oAmH7njpuNisSuZ';
        $secretKey = 'HmKZ4jRefUa6z29xg1Y2uLf_o1tWg4EkY42lLH3O';
        // 初始化签权对象
        $auth = new Auth($accessKey, $secretKey);
        $bucket = 'video';
        // 生成上传Token
        $token = $auth->uploadToken($bucket);
        $uploadMgr = new UploadManager();

        // 调用 UploadManager 的 putFile 方法进行文件的上传。
        list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
        //if ($err) {

        // 获取视频的时长
        // 第一步先获取到到的是关于视频所有信息的json字符串
        $shichang = file_get_contents('http://'.WAILIAN.$key.'?avinfo');
        // 第二部转化为对象
        $shi =json_decode($shichang);
        // 第三部从中取出视频的时长
        $chang = $shi->format->duration;


        $fengmian = 'http://'.WAILIAN.$key.'?vframe/jpg/offset/1';
        $url ='http://'.WAILIAN.$ret['key'];

        $data['url'] = $url;
        $data['title'] = input('post.name');
        $data['picture'] = $fengmian;
        $data['cid'] = input('post.bid');
        $data['content'] = input('post.desc');
        // 判断是否付费
        if (!empty($_POST['grade'])){
            $data['ispay'] = input('post.grade');
        }
        // 添加
        $result = Db::name('shipin')->insert($data);
        if ($result){
            $this->success('添加成功');
        }else {
            $this->error('添加失败');
        }
    }
    // 批量删除视频
    public function alldel()
    {
        foreach ($_GET['all'] as $key => $value){
            $result = Db::name('shipin')->delete($value);
        }
        if ($result){
            return json_encode(['state'=>1]);
        }else {
            return json_encode(['state'=>0]);
        }
    }
    // 删除视频
    public function delvideo()
    {
        $id = input('get.id');
        $result = db('shipin')->where('id',$id)->delete();
        if ($result){
            return json_encode(['state'=>1]);
        }else {
            return json_encode(['state'=>0]);
        }
    }




    // 退出
    public function exit()
    {
        session(null);
        $this->success('退出成功','/admin/index/login');
    }

    // 会员管理
    // 会员列表
    public function memberlist()
    {
        return $this->fetch();
    }
    // 会员删除列表
    public function memberdel()
    {
        return $this->fetch();
    }
    // 会员等级管理
    public function memberlevel()
    {
        return $this->fetch();
    }

}