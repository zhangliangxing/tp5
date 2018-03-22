// obj是一个对象，对象包含一下属性
/*{
    method:请求方式
    url：请求地址
    async：同步或者异步    true是异步，false是同步
    data：{'username=旺旺'} 参数
    success：成功之后要执行的方法
}
{
    method:'get',
    url:'wang.php',
    async:true,
    data:{pwd:111,name:'旺旺'},
    success:success
}*/

function ajax(obj)
{
    // 创建ajax对象
    var xhr = new XMLHttpRequest();
    // 创建url，为了防止缓存，随机一下
    obj.url += '?rand' + Math.random();
    // 绑定函数的处理
    xhr.onreadystatechange = function (){
        if (xhr.readyState == 4){
            if (xhr.status == 200){
                obj.success(xhr.responseText);
            }
        }
    };
    // 处理函数
    var params = [];
    for (var name in obj.data){
        var key = encodeURIComponent(name);
        var value = encodeURIComponent(obj.data[name]);

        params.push(key + '=' + value);
        // 将参数拼接成想要的形式  name='旺旺&pwd=132'

    }
    obj.data = params.join('&');
    // 判断是否是get或者post
    if (obj.method == 'get'){
        obj.url += '&' + obj.data;
    }
    // 接着进行open和send的操作
    xhr.open(obj.method,obj.url,obj.async);
    // 执行send的方法
    if (obj.method == 'get'){
        xhr.send();
    }else {
        xhr.setRequestHeader('content-type','application/x-www-form-urlencoded');
        xhr.send(obj.data);
    }
}