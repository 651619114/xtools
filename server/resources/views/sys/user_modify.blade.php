<!DOCTYPE html>
<html class="x-admin-sm">

<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <link rel="stylesheet" href="{{ URL::asset('css/font.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/xadmin.css') }}">
    <script src="{{ URL::asset('lib/layui/layui.js') }}" charset="utf-8"></script>
    <script type="text/javascript" src="{{ URL::asset('js/xadmin.js') }}"></script>
</head>

<body>
    <div class="layui-fluid">
        <div class="layui-row">
            <form class="layui-form">

                <div class="layui-form-item">
                    <label for="name" class="layui-form-label">
                        <span class="x-red">*</span>登录名
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="name" name="name" required="" lay-verify="required" autocomplete="off" class="layui-input" value="{{$info['name']}}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="phone" class="layui-form-label">
                        <span class="x-red">*</span>手机
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="phone" name="phone" required="" lay-verify="phone" autocomplete="off" class="layui-input" value="{{$info['phone']}}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="L_email" class="layui-form-label">
                        <span class="x-red">*</span>邮箱
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="L_email" name="email" required="" lay-verify="email" autocomplete="off" class="layui-input" value="{{$info['email']}}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"><span class="x-red">*</span>角色</label>
                    <div class="layui-input-block">
                        <input type="radio" name="role_id" lay-skin="primary" title="超级管理员" value="1" @if($info['role_id']==1)checked="" @endif>
                        <input type="radio" name="role_id" lay-skin="primary" title="商家" value="2" @if($info['role_id']==2)checked="" @endif>
                    </div>
                </div>
                <div class=" layui-form-item">
                    <label for="L_pass" class="layui-form-label">
                        <span class="x-red">*</span>密码
                    </label>
                    <div class="layui-input-inline">
                        <input type="password" id="L_pass" name="password" required="" lay-verify="pass" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="L_repass" class="layui-form-label">
                        <span class="x-red">*</span>确认密码
                    </label>
                    <div class="layui-input-inline">
                        <input type="password" id="L_repass" name="repass" required="" lay-verify="repass" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="L_repass" class="layui-form-label">
                    </label>
                    <button class="layui-btn" lay-filter="add" lay-submit="" id="sub">
                        修改
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
        layui.use(['form', 'layer'],
            function() {
                $ = layui.jquery;
                var form = layui.form,
                    layer = layui.layer;

                //自定义验证规则
                form.verify({
                    // pass: [/(.+){6,12}$/, '密码必须6到12位'],
                    repass: function(value) {
                        if ($('#L_pass').val() != $('#L_repass').val()) {
                            return '两次密码不一致';
                        }
                    }
                });

                form.on('submit(add)',
                    function(data) {
                        //发异步，把数据提交给php
                        $.ajax({
                            type: "post",
                            url: '/sys/user/modify',
                            data: {
                                "id": "{{$info['id']}}",
                                "name": $("input[name='name']").val(),
                                "phone": $("input[name='phone']").val(),
                                "email": $("input[name='email']").val(),
                                "role_id": $("input[name='role_id']:checked").val(),
                                "password": $("input[name='password']").val(),
                                "_token": "{{csrf_token()}}",
                            },
                            success: function(data) {
                                layer.msg(data.message, {
                                        time: 1000,
                                        icon: 6
                                    },
                                    function() {
                                        //关闭当前frame
                                        xadmin.close();
                                        // 可以对父窗口进行刷新 
                                        xadmin.father_reload();
                                    });

                            },
                            error: function(data) {
                                if (data.status == 422) {
                                    layer.msg(data.responseJSON.message, {
                                        time: 1000,
                                        icon: 2
                                    });
                                } else {
                                    layer.alert(data.message, {
                                            icon: 2
                                        },
                                        function() {
                                            //关闭当前frame
                                            xadmin.close();
                                            // 可以对父窗口进行刷新 
                                            // xadmin.father_reload();
                                        });
                                }
                            }
                        });
                        return false;
                    });

            });
    </script>
</body>

</html>