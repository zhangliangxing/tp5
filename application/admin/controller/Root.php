<?php
namespace app\admin\controller;

use app\index\model\User as UserModel;
use think\Controller;
use think\Request;
use think\Db;
use think\Model;
use think\Loader;

class Root extends Controller
{
    // 无限极分类
    function getTree($list, $pid=0, $level=1)
    {
        static $newlist = array();
        foreach($list as $key => $value)
            {
            if($value['pid']==$pid)
                {
                $value['level'] = $level;
                $newlist[] = $value;
                unset($list[$key]);
                $this->getTree($list, $value['nid'], $level+1);
                }
            }
        return $newlist;
    }


/*---------------------------角色管理-------------------------------------------*/

    public function adminrole()
    {
        $result = Db::name('urole')->select();
        $count = count($result);
        $this->assign('count',$count);
        $this->assign('result',$result);
        return $this->fetch();
    }
    // 添加角色页面
    public function roleadd()
    {
        return $this->fetch();
    }
    // 执行添加角色
    public function addrole()
    {
        $rolename = input('post.name');
        $roledesc = input('post.desc');
        $data = ['rolename'=>$rolename,'roledesc'=>$roledesc];
        $result = Db::name('urole')->insert($data);
        if ($result){
            echo json_encode(['state'=>1]);
        }else {
            echo json_encode(['state'=>0]);
        }
    }
    // 多删
    public function duodel()
    {
        foreach ($_GET['all'] as $key => $value){
            $result = Db::name('urole')->delete($value);
        }
        if ($result){
            return json_encode(['state'=>1]);
        }else {
            return json_encode(['state'=>0]);
        }
    }
    // 删除角色
    public function delrole()
    {
        $rid = input('get.rid');
        $result = db('urole')->where('rid',$rid)->delete();
        if ($result){
            return json_encode(['state'=>1]);
        }else {
            return json_encode(['state'=>0]);
        }
    }

    // 角色编辑
    public function roleedit()
    {
        $rid = $_GET['rid'];
        $result = Db::name('urole')->where('rid',$rid)->select();
        $name = $result[0]['rolename'];
        $desc = $result[0]['roledesc'];
        $this->assign('name',$name);
        $this->assign('desc',$desc);
        $this->assign('rid',$rid);
        // 查询所有的权限
        $roles = Db::name('unode')->select();
        $roles = $this->getTree($roles);
        $this->assign('roles',$roles);
        // 查询点击的角色拥有的权限
        // 先查询角色用户表
        $nids = Db::name('uaccess')->where('rid',$rid)->select();
        $nid = [];
        foreach ($nids as $k=>$v){
            $nid[] = $v['nid'];
        }
        $this->assign('nid',$nid);
        return $this->fetch();
    }
    // 执行角色修改
    public function updrole()
    {
        $nid = $_POST['nid'];
        $rid = input('post.rid');
        $rolename = input('post.name');
        $roledesc = input('post.desc');
        // 修改角色表
        $result = Db::name('urole')->where('rid',$rid)->update(['rolename'=>$rolename,'roledesc'=>$roledesc]);
        // 删除角色权限表中的该角色的内容
        $del = Db::name('uaccess')->where('rid',$rid)->delete();
        // 根据小的权限查询大权限的id
        $pid = Db::name('unode')->where('nid','in',$nid)->field('pid')->select();
        $pids = [];
        foreach ($pid as $k=>$v){
            foreach ($v as $k=>$v){
                $pids[] = $v;
            }
        }
        // 合并nid和pid
        $npid = array_merge($pids,$nid);
        // 向角色权限表中添加
        $data = [];
        foreach ($npid as $k=>$v){
            $data[] = array(
                'rid' => $rid,
                'nid' => $v
            );
        }
        $res = Db::name('uaccess')->insertAll($data);
        if ($result || $res){
            echo json_encode(['state'=>1]);
        }else {
            echo json_encode(['state'=>0]);
        }
    }



/*-------------------------权限管理开始----------------------------------------*/


    public function adminrule()
    {
        $result = Db::name('unode')->select();
        $result = $this->getTree($result);
        $count = count($result);
        $this->assign('count',$count);
        $this->assign('result',$result);
        return $this->fetch();
    }
    // 添加权限
    public function addrule()
    {
        if (!empty(input('post.name')&&input('post.uris')&&input('post.level'))){
            $name = input('post.name');
            // 查询权限是否存在
            $info = Db::name('unode')->where('name',$name)->select();
            if ($info){
                return json_encode(['state'=>0]);
            }

            $uris = input('post.uris');
            $level = input('post.level');
            $pid = input('post.pid');
            $data = ['name'=>$name,'uris'=>$uris,'level'=>$level,'pid'=>$pid];
            $result = Db::name('unode')->insert($data);
            if ($result){
                echo json_encode(['state'=>1]);
            }else {
                echo json_encode(['state'=>0]);
            }
        }

    }
    // 多删权限
    public function delall()
    {
        foreach ($_GET['all'] as $key => $value){
            $result = Db::name('unode')->delete($value);
        }
        if ($result){
            return json_encode(['state'=>1]);
        }else {
            return json_encode(['state'=>0]);
        }
    }
    // 删除权限
    public function delrule()
    {
        $nid = input('get.nid');
        $result = db('unode')->where('nid',$nid)->delete();
        if ($result){
            return json_encode(['state'=>1]);
        }else {
            return json_encode(['state'=>0]);
        }
    }
    // 修改权限
    public function updrule()
    {
        $nid = input('post.id');
        $name = input('post.name');
        $result = Db::name('unode')->where('nid',$nid)->update(['name'=>$name]);
        if ($result){
            echo json_encode(['state'=>1]);
        }else {
            echo json_encode(['state'=>0]);
        }
    }

/*-------------------------------------管理员管理开始-------------------------------------*/

    // 管理员列表
    public function adminlist()
    {
        $result = Db::table('video_user u,video_urole r,video_urole_user ur')->where('u.uid = ur.uid and ur.rid = r.rid and u.level<3')->field('r.rolename,u.uid,u.username,u.phone,u.email,u.ctime,u.allowlogin,u.level')->paginate(3);
        $count = count($result);
        $this->assign('count',$count);
        $this->assign('result',$result);
        return $this->fetch();
    }
    // 停用
    public function stop()
    {
        $uid = input('get.uid');
        $result = Db::name('user')->where('uid',$uid)->update(['allowlogin'=>1]);
        if ($result){
            return json_encode(['state'=>1]);
        }else {
            return json_encode(['state'=>0]);
        }
    }
    // 启用
    public function start()
    {
        $uid = input('get.uid');
        $result = Db::name('user')->where('uid',$uid)->update(['allowlogin'=>0]);
        if ($result){
            return json_encode(['state'=>1]);
        }else {
            return json_encode(['state'=>0]);
        }
    }
    // 删除管理员
    public function deladmin()
    {
        $uid = input('get.uid');
        // 判断是否为超管
        $level = Db::name('user')->where('uid',$uid)->value('level');
        if ($level==1){
            return json_encode(['state'=>0]);
        }
        $result = db('user')->where('uid',$uid)->delete();
        $result2 = db('urole_user')->where('uid',$uid)->delete();
        if ($result){
            return json_encode(['state'=>1]);
        }else {
            return json_encode(['state'=>0]);
        }
    }
    // 批量删除管理员
    public function alldel()
    {
        foreach ($_GET['all'] as $key => $value){
            $result = Db::name('user')->delete($value);
            $result2 = Db::name('urole_user')->delete($value);
        }
        if ($result){
            return json_encode(['state'=>1]);
        }else {
            return json_encode(['state'=>0]);
        }
    }

    // 添加管理员
    // 判断是否存在
    public function isadmin()
    {
        //登录时提交过来的名字
        $username= $this->request->post('username');
        //判断数据库是否有这个名字
        $result = UserModel::getByUsername($username);
        if ($result) {
            return json_encode(['state'=>1]);
        } else {
            return json_encode(['state'=>0]);
        }
    }

    public function adminadd()
    {
        // 查询角色表
        $result = Db::name('urole')->where('rid','<','3')->select();
        $this->assign('result',$result);
        return $this->fetch();
    }
    // 执行添加管理员
    public function doadmin()
    {
        $name = input('post.username');
        $phone = input('post.phone');
        $email = input('post.email');
        $level = input('post.level');
        $pwd = md5(input('post.pwd'));
        $data = ['username'=>$name,'password'=>$pwd,'email'=>$email,'level'=>$level,'phone'=>$phone];
        $uid = Db::name('user')->insertGetId($data);
        if ($uid){
            $arr['uid'] = $uid;
            $arr['rid'] = $level;
            $res = Db::name('urole_user')->insert($arr);
            if ($res){
                echo json_encode(['state'=>1]);
            }else {
                echo json_encode(['state'=>0]);
            }
        }else {
            echo json_encode(['state'=>0]);
        }
    }
    // 编辑管理员
    public function adminedit()
    {
        // 根据你点击的进行查询
        $uid = input('get.uid');
        $result = Db::query("select u.uid,username,rolename,level from video_user u,video_urole r,video_urole_user ur where u.uid=$uid and u.uid=ur.uid and ur.rid=r.rid");
        // 查询出角色
        $role = Db::name('urole')->where('rid','<','3')->select();
        $this->assign('result',$result);
        $this->assign('role',$role);
        return $this->fetch();
    }
    // 进行管理员角色修改
    public function adminupd()
    {
        $uid = input('post.uid');
        $level = input('post.level');
        // 修改用户表
        $result = Db::name('user')->where('uid',$uid)->update(['level'=>$level]);
        // 修改用户角色表
        $result2 = Db::name('urole_user')->where('uid',$uid)->update(['rid'=>$level]);
        if ($result){
            echo json_encode(['state'=>1]);
        }else {
            echo json_encode(['state'=>0]);
        }
    }
}