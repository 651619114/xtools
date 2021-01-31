<!doctype html>
<html class="x-admin-sm">

<head>
    <meta charset="UTF-8">
    <title>xTools</title>
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="stylesheet" href="{{ URL::asset('css/font.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/login.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/xadmin.css') }}">
    <script type="text/javascript" src="{{ URL::asset('js/jquery.min.js') }}"></script>
    <script src=" {{ URL::asset('lib/layui/layui.js') }}" charset="utf-8"></script>
</head>

<body class="login-bg">

    <div class="login layui-anim layui-anim-up">
        <div class="message">xTools</div>
        <div id="darkbannerwrap"></div>

        <form method="post" class="layui-form" action="login">
            @csrf
            <input name="email" placeholder="邮箱" type="text" lay-verify="required" class="layui-input">
            <hr class="hr15">
            <input name="password" lay-verify="required" placeholder="密码" type="password" class="layui-input">
            <hr class="hr15">
            <input value="登录" lay-submit lay-filter="login" style="width:100%;" type="submit">
            <hr class="hr20">
            @if(isset($msg))
            <p>{{ $msg }}</p>
            @endif
        </form>
    </div>
</body>

</html>