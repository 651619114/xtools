<!doctype html>
<html class="x-admin-sm">

<head>
    <meta charset="UTF-8">
    <title>OCR列表</title>
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
                                    <button class="layui-btn" onclick="xadmin.open('上传待识别图片','/tool/ocrtool/add',600,600)"><i class="layui-icon"></i>上传待识别图片</button>
                                </div>
                                <div class="layui-card-body ">
                                    <table class="layui-table layui-form">
                                        <thead>
                                            <tr>
                                                <th>缩略图</th>
                                                <th>状态</th>
                                                <th>创建时间</th>
                                                <th>更新时间</th>
                                                <th>操作</th>
                                        </thead>
                                        <tbody>
                                            @foreach($lists as $key => $value)
                                            <tr>
                                                <td><img src="{{asset($value['source_path'])}}" height="80px" /></td>
                                                <td class=" td-status">
                                                    @if($value['status'] == 1)
                                                    <span class="layui-btn layui-btn-normal layui-btn-mini">待识别</span>
                                                    @elseif($value['status'] == 2)
                                                    <span class="layui-btn layui-btn-seccess layui-btn-mini">识别成功</span>
                                                    @else
                                                    <span class="layui-btn layui-btn-danger layui-btn-mini">识别失败</span>
                                                    @endif
                                                </td>
                                                <td>{{$value['created_at']}}</td>
                                                <td>{{$value['updated_at']}}</td>
                                                <td class="td-manage">
                                                    <button class="layui-btn layui-btn layui-btn-xs" onclick="member_create(this,{{$value['id']}})">开始识别</button>
                                                    @if($value['status'] == 2)<button class="layui-btn layui-btn layui-btn-xs"><a href="/tool/ocrtool/download?id={{$value['id']}}" style="color:white">下载</a></button>@endif
                                                    <button class="layui-btn-danger layui-btn layui-btn-xs" onclick="member_del(this,{{$value['id']}})">删除</button>
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
    /*用户-删除*/
    function member_del(obj, id) {
        layer.confirm('确认要删除吗？', function(index) {
            $.ajax({
                type: "POST", //提交方式 
                url: "/tool/ocrtool/delete", //路径 
                data: {
                    "id": id,
                    "_token": "{{ csrf_token() }}"
                }, //数据，这里使用的是Json格式进行传输 
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
        });
    }

    function member_create(obj, id) {
        layer.confirm('文字识别需要时间 请耐心等待', function(index) {
            $.ajax({
                type: "POST", //提交方式 
                url: "/tool/ocrtool/create", //路径 
                data: {
                    "id": id,
                    "_token": "{{ csrf_token() }}"
                }, //数据，这里使用的是Json格式进行传输 
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
        });
    }
</script>
</html>