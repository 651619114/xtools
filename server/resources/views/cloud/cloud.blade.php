<!doctype html>
<html class="x-admin-sm">

<head>
    <meta charset="UTF-8">
    <title>文件列表</title>
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="stylesheet" href="{{ URL::asset('css/font.css') }}">
    <script type="text/javascript" src="{{ URL::asset('js/jquery.min.js') }}"></script>

    <link rel="stylesheet" href="{{ URL::asset('css/xadmin.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/webuploader.css') }}">
    <script src="{{ URL::asset('lib/layui/layui.js') }}" charset="utf-8"></script>
    <script type="text/javascript" src="{{ URL::asset('js/xadmin.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/webuploader.min.js') }}"></script>
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
                                <form method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <div class="layui-card-header">
                                        <button class="layui-btn" id="aa">上传文件</button>
                                    </div>
                                </form>

                                <div class="layui-card-body ">
                                    <div style="display:none;" id="tab">上传中：</div>
                                    <div style="display:none" id="num"></div>
                                    <table class="layui-table layui-form">
                                        <thead>
                                            <tr>
                                                <th>文件名</th>
                                                <th>文件大小</th>
                                                <th>创建时间</th>
                                                <th width="300">下载直链(云端)</th>
                                                <th>是否同步</th>
                                                <th>操作</th>
                                        </thead>
                                        <tbody>
                                            @foreach($lists as $key => $value)
                                            <tr>
                                                <td>{{$value['real_name']}}</td>
                                                <td>{{$value['file_size']}}</td>
                                                <td>{{$value['created_at']}}</td>
                                                <td width="300"><a href="{{$value['remote_path']}}">{{$value['remote_path']}}</a></td>
                                                <td class=" td-status">
                                                    @if($value['is_sync'] == 1)
                                                    <span class="layui-btn layui-btn-success layui-btn-mini">未同步</span>
                                                    @elseif($value['is_sync'] == 2)
                                                    <span class="layui-btn layui-btn-normal layui-btn-mini">同步成功</span>
                                                    @else
                                                    <span class="layui-btn layui-btn-danger layui-btn-mini">同步失败</span>
                                                    @endif
                                                </td>
                                                <td class="td-manage">
                                                    <button class="layui-btn layui-btn layui-btn-xs" onclick="member_create(this,{{$value['id']}})">同步云存储</button>
                                                    <button class="layui-btn layui-btn layui-btn-xs" onclick="member_download(this,{{$value['id']}})">生成下载链接</button>
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
                url: "/cloud/cloud/delete", //路径 
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

    function member_create(obj, id) {
        layer.confirm('确定同步文件到云空间?', function(index) {
            $.ajax({
                type: "POST", //提交方式 
                url: "/cloud/cloud/create", //路径 
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


    function member_download(obj, id) {
        layer.confirm('此操作将生成远程云端下载直链，有效期为十天，是否继续?', function(index) {
            $.ajax({
                type: "POST", //提交方式 
                url: "/cloud/cloud/download", //路径 
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
<script>
    $(document).ready(function() {
        var uploader = WebUploader.create({
            auto: true,
            // swf文件路径
            swf: "{{ URL::asset('js/Uploader.swf') }}",

            // 文件接收服务端。
            server: "{{url('/cloud/cloud/upload')}}",

            // 选择文件的按钮。可选。
            // 内部根据当前运行是创建，可能是input元素，也可能是flash.
            pick: '#aa',

            // 不压缩image, 默认如果是jpeg，文件上传前会压缩一把再上传！
            resize: false,
            formData: {
                _token: '{{csrf_token()}}'
            },
            // 开起分片上传。
            chunked: true,
            chunkSize: 2 * 1024 * 1024,
            threads: 1, //上传并发数
        });

        // 文件上传过程中创建进度条实时显示。
        uploader.on('uploadProgress', function(file, percentage) {

            $("#tab").css('display', 'block');
            $("#num").css('display', 'block');
            $("#num").html((percentage * 100).toFixed(2) + '%');
        });

        uploader.on('uploadSuccess', function(file) {});

        uploader.on('uploadError', function(file) {
            $("#tab").css('display', 'none');
            $("#num").css('display', 'none');
            alert("上传失败");
        });

        uploader.on('uploadComplete', function(file) {
            $("#tab").css('display', 'none');
            $("#num").css('display', 'none');
            location.reload();

        });

        $("#ctlBtn").click(function() {
            uploader.retry();
        });
    });
</script>

</html>