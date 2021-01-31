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
                        <span class="x-red">*</span>目录名
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="name" name="name" required="" lay-verify="required" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="server_name" class="layui-form-label">
                        <span class="x-red">*</span>父级
                    </label>
                    <div class="layui-input-inline">
                        <select name="root_id">
                            <option value="0">请选择</option>
                            @foreach($rootmenu as $key => $value)
                            <option value="{{$value['menu_id']}}">{{$value['name']}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="L_email" class="layui-form-label">
                        <span class="x-red">*</span>排序
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="L_email" name="display" required="" lay-verify="display" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="class_func" class="layui-form-label">
                        <span class="x-red">*</span>方法名
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="class_func" name="class_func" required="" lay-verify="class_func" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"><span class="x-red">*</span>权限分配</label>
                    <div class="layui-input-block">
                        @foreach($role as $key => $value)
                        <input type="checkbox" title="{{$value}}" value="{{$key}}" name="role_menu" />
                        @endforeach
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
                form.verify({
                    pass: [/(.+){6,12}$/, '密码必须6到12位'],
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
                            url: '/sys/menu/add',
                            data: {
                                "name": $("input[name='name']").val(),
                                "root_id": $("select[name='root_id']").val(),
                                "display": $("input[name='display']").val(),
                                "class_func": $("input[name='class_func']").val(),
                                "role_menu": $("input[name='role_menu']:checked").val(),
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