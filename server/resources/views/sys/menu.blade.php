<!doctype html>
<html class="x-admin-sm">

<head>
    <meta charset="UTF-8">
    <title>菜单管理</title>
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="stylesheet" href="{{ URL::asset('css/font.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/xadmin.css') }}">
    <script src="{{ URL::asset('lib/layui/layui.js') }}" charset="utf-8"></script>
    <script type="text/javascript" src="{{ URL::asset('js/xadmin.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/jquery.min.js') }}"></script>

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
                                    <button class="layui-btn" onclick="xadmin.open('添加用户','/sys/menu/add',600,600)"><i class="layui-icon"></i>添加</button>
                                </div>
                                <div class="layui-card-body ">
                                    <table class="layui-table layui-form">
                                        <thead>
                                            <tr>
                                                <th width="70">ID</th>
                                                <th>栏目名</th>
                                                <th width="50">排序</th>
                                                <th width="50">权限所属</th>
                                                <th width="250">操作</th>
                                        </thead>
                                        <tbody class="x-cate">
                                            @foreach($lists as $key => $value)

                                            <tr cate-id='{{$value["menu_id"]}}' fid='{{$value["root_id"]}}'>
                                                <td>{{$value["menu_id"]}}</td>
                                                <td>
                                                    <i class="layui-icon x-show" status='true'>&#xe623;</i>
                                                    @if($value['root_id'] == 0)
                                                    {{$value["name"]}}
                                                    @else
                                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                                    ├{{$value["name"]}}
                                                    @endif
                                                </td>
                                                <td>{{$value['display']}}</td>
                                                <td>超管\{{$value['checkrole']}}</td>
                                                <td class="td-manage">
                                                    <button class="layui-btn layui-btn layui-btn-xs" onclick="xadmin.open('修改菜单','/sys/menu/modify?menu_id={{$value['menu_id']}}',600,600)"><i class="layui-icon">&#xe642;</i>编辑</button>
                                                    <button class="layui-btn-danger layui-btn layui-btn-xs" onclick="member_del(this,{{$value['menu_id']}})" href="javascript:;"><i class="layui-icon">&#xe640;</i>删除</button>
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
                url: "/sys/menu/delete", //路径 
                data: {
                    "menu_id": id,
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


    $(function() {
        $("tbody.x-cate tr[fid!='0']").hide();
        // 栏目多级显示效果
        $('.x-show').click(function() {
            if ($(this).attr('status') == 'true') {
                $(this).html('&#xe625;');
                $(this).attr('status', 'false');
                cateId = $(this).parents('tr').attr('cate-id');
                $("tbody tr[fid=" + cateId + "]").show();
            } else {
                cateIds = [];
                $(this).html('&#xe623;');
                $(this).attr('status', 'true');
                cateId = $(this).parents('tr').attr('cate-id');
                getCateId(cateId);
                for (var i in cateIds) {
                    $("tbody tr[cate-id=" + cateIds[i] + "]").hide().find('.x-show').html('&#xe623;').attr('status', 'true');
                }
            }
        })
    })

    var cateIds = [];

    function getCateId(cateId) {
        $("tbody tr[fid=" + cateId + "]").each(function(index, el) {
            id = $(el).attr('cate-id');
            cateIds.push(id);
            getCateId(id);
        });
    }
</script>

</html>