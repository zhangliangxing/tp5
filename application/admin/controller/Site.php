<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;

class Site extends Controller
{
    // 系统设置
    // 站点信息
    public function siteinfo()
    {
        $result = Db::name('site')->select();
        $this->assign('result',$result);
        return $this->fetch();
    }
    // 站点修改
    public function siteupd()
    {
        $sitename = input('post.title');
        $tag = input('post.tag');
        $descript = input('post.descript');
        $copy = input('post.copy');
        $record = input('post.record');
        $close = input('post.close');
        $result = Db::name('site')->where('sid',1)->update(['sitename'=>$sitename,'tag'=>$tag,'descript'=>$descript,'copy'=>$copy,'record'=>$record,'close'=>$close]);
        if ($result){
            echo json_encode(['state'=>1]);
        }else {
            echo json_encode(['state'=>0]);
        }
    }





    // 友情链接
    public function sitelink()
    {
        $result = Db::name('link')->order('sort')->select();
        $this->assign('result',$result);
        return $this->fetch();
    }
    // 添加链接
    public function addlink()
    {
        if (!empty(input('post.name') && input('post.url'))){
            $lname = input('post.name');
            $url = input('post.url');
            $data = ['lname'=>$lname,'url'=>$url];
            $result = Db::name('link')->insert($data);
            if ($result){
                echo json_encode(['state'=>1]);
            }else {
                echo json_encode(['state'=>0]);
            }
        }

    }
    // 批量删除链接
    public function delall()
    {
        foreach ($_GET['all'] as $key => $value){
            $result = Db::name('link')->delete($value);
        }
        if ($result){
            return json_encode(['state'=>1]);
        }else {
            return json_encode(['state'=>0]);
        }
    }
    // 删除链接
    public function dellink()
    {
        $lid = input('get.lid');
        $result = db('link')->where('lid',$lid)->delete();
        if ($result){
            return json_encode(['state'=>1]);
        }else {
            return json_encode(['state'=>0]);
        }
    }
    // 修改链接
    public function linkedit()
    {
        // 根据你点击的进行查询
        $lid = input('get.lid');
        $result = Db::name('link')->where('lid',$lid)->select();
        $this->assign('result',$result);
        return $this->fetch();
    }
    // 执行修改链接
    public function linkupd()
    {
        $lid = input('post.lid');
        $lname = input('post.lname');
        $url = input('post.url');
        $sort = input('post.sort');
        $result = Db::name('link')->where('lid',$lid)->update(['lname'=>$lname,'url'=>$url,'sort'=>$sort]);
        if ($result){
            echo json_encode(['state'=>1]);
        }else {
            echo json_encode(['state'=>0]);
        }
    }
}