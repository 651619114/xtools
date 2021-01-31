<div class="left-nav">
    <div id="side-nav">
        <ul id="nav">

            @foreach (Session::get('menu') as $key=>$value)
            <li>
                <a href="javascript:;">
                    <cite>{{$key}}</cite>
                    <i class="iconfont nav_right">&#xe697;</i></a>
                @if(!empty($value))
                <ul class="sub-menu">
                    @foreach ($value as $k=>$v)
                    <li>
                        <a href="{{$v['class_func']}}" @if($func==$v['class_func'])class="active" @else class1 @endif>
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>{{$v['name']}}</cite></a>
                    </li>
                    @endforeach
                </ul>
                @endif
            </li>
            @endforeach
        </ul>
    </div>
</div>