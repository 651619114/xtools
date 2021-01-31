<div class="container">
    <div class="logo">
        <a href="./index.html">xTools</a></div>
    <div class="left_open">
        <a><i title="展开左侧栏" class="iconfont">&#xe699;</i></a>
    </div>
    <ul class="layui-nav right" lay-filter="">
        <li class="layui-nav-item">
            <a href="javascript:;">欢迎您{{Session::get('name')}}，当前身份为@if(Session::get('role_id') == 1)超级管理员@else商家@endif</a>
        </li>
        <li class="layui-nav-item to-index">
            <a href="/logout">退出</a>
    </ul>
</div>