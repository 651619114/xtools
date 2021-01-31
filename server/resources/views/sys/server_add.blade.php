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
            <form class="layui-form ">

                <div class="layui-form-item">
                    <label for="server_name" class="layui-form-label">
                        <span class="x-red">*</span>所属用户
                    </label>
                    <div class="layui-input-inline">

                        <select name="user_id">
                            <option value="">请选择</option>

                            @foreach($user as $key => $value)
                            <option value="{{$key}}">{{$value}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="server_name" class="layui-form-label">
                        <span class="x-red">*</span>服务名
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="server_name" name="server_name" required="" lay-verify="server_name" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="code" class="layui-form-label">
                        <span class="x-red">*</span>服务代码
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="code" name="code" required="" lay-verify="code" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="server_name" class="layui-form-label">
                        <span class="x-red">*</span>服务日期
                    </label>
                    <div class="layui-input-inline">
                        <input class="layui-input" autocomplete="off" placeholder="开始日" name="start_time" id="start_time">
                        <input class="layui-input" autocomplete="off" placeholder="截止日" name="end_time" id="end_time">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="L_repass" class="layui-form-label">
                    </label>
                    <button class="layui-btn" lay-filter="add" lay-submit="" id="sub">
                        增加
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
                // form.verify({
                //     pass: [/(.+){6,12}$/, '密码必须6到12位'],
                //     repass: function(value) {
                //         if ($('#L_pass').val() != $('#L_repass').val()) {
                //             return '两次密码不一致';
                //         }
                //     }
                // });

                form.on('submit(add)',
                    function(data) {
                        //发异步，把数据提交给php
                        $.ajax({
                            type: "post",
                            url: '/sys/server/add',
                            data: {
                                "server_name": $("input[name='server_name']").val(),
                                "start_time": $("input[name='start_time']").val(),
                                "end_time": $("input[name='end_time']").val(),
                                "code": $("input[name='code']").val(),
                                "user_id": $("select[name='user_id']").val(),
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

        layui.use(['laydate', 'form'], function() {
            var laydate = layui.laydate;
            var form = layui.form;

            //执行一个laydate实例
            laydate.render({
                elem: '#start_time' //指定元素
            });

            //执行一个laydate实例
            laydate.render({
                elem: '#end_time' //指定元素
            });
        });
    </script>
</body>

</html>