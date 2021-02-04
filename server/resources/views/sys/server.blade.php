<!doctype html>
<html class="x-admin-sm">

<head>
    <meta charset="UTF-8">
    <title>服务管理</title>
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="stylesheet" href="{{ URL::asset('css/font.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/xadmin.css') }}">
    <script src="{{ URL::asset('lib/layui/layui.js') }}" charset="utf-8"></script>
    <script type="text/javascript" src="{{ URL::asset('js/xadmin.js') }}"></script>
    <script>
        // 是否开启刷新记忆tab功能
        // var is_remember = false;
    </script>
</head>

<body class="index">
    @include('tab_bar')
    @include('nav_bar')
    <!-- 右侧主体开始 -->
    <div class="page-content">
        <div class="layui-tab tab" lay-filter="xbs_tab" lay-allowclose="false">
            <ul class="layui-tab-title">
                <li class="home">
                    <i class="layui-icon">&#xe68e;</i>我的桌面
                </li>
            </ul>
            <div class="layui-unselect layui-form-select layui-form-selected" id="tab_right">
                <dl>
                    <dd data-type="this">关闭当前</dd>
                    <dd data-type="other">关闭其它</dd>
                    <dd data-type="all">关闭全部</dd>
                </dl>
            </div>
            <div class="layui-tab-content">
                <div class="layui-fluid">
                    <div class="layui-row layui-col-space15">
                        <div class="layui-col-md12">
                            <div class="layui-card">
                                <div class="layui-card-header">
                                    <button class="layui-btn" onclick="xadmin.open('添加用户','/sys/server/add',600,600)"><i class="layui-icon"></i>添加</button>
                                </div>
                                <div class="layui-card-body ">
                                    <table class="layui-table layui-form">
                                        <thead>
                                            <tr>
                                                <th>所属用户</th>
                                                <th>服务名称</th>
                                                <th>服务代码</th>
                                                <th>开始时间</th>
                                                <th>结束时间</th>
                                                <th>创建时间</th>
                                                <th>修改时间</th>
                                                <th>状态</th>
                                                <th>操作</th>
                                        </thead>
                                        <tbody>
                                            @foreach($lists as $key => $value)

                                            <tr>
                                                <td>{{$value['aname']}}</td>
                                                <td>{{$value['server_name']}}</td>
                                                <td>{{$value['code']}}</td>
                                                <td>{{ date( "Y-n-j", + $value['start_time'])}}</td>
                                                <td>{{ date( "Y-n-j", + $value['end_time'])}}</td>
                                                <td>{{$value['created_at']}}</td>
                                                <td>{{$value['updated_at']}}</td>
                                                <td class="td-status">
                                                    @if($value['status'] == 1)
                                                    <span class="layui-btn layui-btn-normal layui-btn-mini">已启用</span>
                                                </td>
                                                @else
                                                <span class="layui-btn layui-btn-danger layui-btn-mini">已禁用</span></td>

                                                @endif
                                                <td class="td-manage">
                                                    <button class="layui-btn layui-btn layui-btn-xs" onclick="xadmin.open('修改用户','/sys/server/modify?user_id={{$value['user_id']}}',600,600)"><i class="layui-icon">&#xe642;</i>编辑</button>
                                                    <button class="layui-btn-danger layui-btn layui-btn-xs" onclick="member_del(this,{{$value['user_id']}})" href="javascript:;"><i class="layui-icon">&#xe640;</i>删除</button>
                                                </td>
                                            </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>

                                <div class="layui-card-body ">
                                    <div class="page">
                                        <div>
                                            {{ $lists->links() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="tab_show"></div>
    </div>

    </div>

    <div class="page-content-bg"></div>
    <style id="theme_style"></style>

</body>
<script>
    /*用户-停用*/
    function member_stop(obj, id) {
        layer.confirm('确认要更改状态吗？', function(index) {
            $.ajax({
                type: "POST", //提交方式 
                url: "/sys/server/change", //路径 
                data: {
                    "user_id": id,
                    "_token": "{{ csrf_token() }}"
                }, //数据，这里使用的是Json格式进行传输 
                success: function(data) {
                    layer.msg(data.message, {
                            time: 1000,
                            icon: 6
                        },
                        function() {
                            location.reload();
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
        });
    }

    /*用户-删除*/
    function member_del(obj, id) {
        layer.confirm('确认要删除吗？', function(index) {
            $.ajax({
                type: "POST", //提交方式 
                url: "/sys/server/delete", //路径 
                data: {
                    "user_id": id,
                    "_token": "{{ csrf_token() }}"
                }, //数据，这里使用的是Json格式进行传输 
                success: function(data) {
                    layer.msg(data.message, {
                            time: 1000,
                            icon: 6
                        },
                        function() {
                            location.reload();
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
        });
    }
</script>

</html>