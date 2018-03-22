<?php
namespace app\admin\controller;

use app\index\model\User as UserModel;
use think\Controller;
use think\Db;

class User extends Controller
{
    // 用户管理
    public function userlist()
    {
        $result = Db::name('user')->where('level','>','2')->paginate(3);
        $count = count($result);
        $this->assign('count',$count);
        $this->assign('result',$result);
        return $this->fetch();
    }
    // 查询用户
    public function sea()
    {
        $user = input('post.username');
        // dump($user);
        $result = Db::query("select * from video_user where level>2 and username like '%$user%' limit 5");
        $count = count($result);
        $this->assign('result',$result);
        $this->assign('count',$count);
        return $this->fetch();
    }

    // 添加用户
    // 判断是否存在
    public function isuser()
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
    public function useradd()
    {
        // 查询角色表
        $result = Db::name('urole')->where('rid','>','2')->select();
        $this->assign('result',$result);
        return $this->fetch();
    }
    // 添加用户
    public function douser()
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

    // 删除用户
    public function deluser()
    {
        $uid = input('get.uid');
        $result = db('user')->where('uid',$uid)->delete();
        $result2 = db('urole_user')->where('uid',$uid)->delete();
        if ($result){
            return json_encode(['state'=>1]);
        }else {
            return json_encode(['state'=>0]);
        }
    }
    // 批量删除用户
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

    // 修改用户
    public function useredit()
    {
        // 根据你点击的进行查询
        $uid = input('get.uid');
        $result = Db::query("select u.uid,username,grade,sex,rolename,level from video_user u,video_urole r,video_urole_user ur where u.uid=$uid and u.uid=ur.uid and ur.rid=r.rid");
        // 查询出角色
        $role = Db::name('urole')->where('rid','>','2')->select();
        $this->assign('result',$result);
        $this->assign('role',$role);
        return $this->fetch();
    }
    // 执行修改用户
    public function userupd()
    {
        $uid = input('post.uid');
        $grade = input('post.grade');
        $level = input('post.level');
        // 修改用户表
        $result = Db::name('user')->where('uid',$uid)->update(['level'=>$level,'grade'=>$grade]);
        // 修改用户角色表
        $result2 = Db::name('urole_user')->where('uid',$uid)->update(['rid'=>$level]);
        if ($result){
            echo json_encode(['state'=>1]);
        }else {
            echo json_encode(['state'=>0]);
        }
    }
}