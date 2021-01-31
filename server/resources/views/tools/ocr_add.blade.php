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
                    <label for="source_path" class="layui-form-label">
                        <span class="x-red">*</span>图片
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="source_path" required="" name="source_path" lay-verify="source_path" autocomplete="off" class="layui-input">
                    </div>
                    <input type="file" name="file" id="file" onchange="doUpload()">
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

                form.on('submit(add)',
                    function(data) {
                        //发异步，把数据提交给php
                        $.ajax({
                            type: "post",
                            url: '/tool/ocrtool/add',
                            data: {
                                "source_path": $("input[name='source_path']").val(),
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

        function doUpload() {
            var formData = new FormData();
            formData.append('file', $('#file')[0].files[0]);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            $.ajax({
                type: "post",
                url: '/tool/ocrtool/upload',
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    $("#source_path").val(data.data.filepath)
                },
                error: function(data) {
                    alert('失败');
                }
            });
        }
    </script>
</body>

</html>