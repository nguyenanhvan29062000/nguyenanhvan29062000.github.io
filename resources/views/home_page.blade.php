@extends('master')
@section('title')
<title>HOME: FREE PC GAMES FOR YOU - PCGAMESCRACK.CF</title>
@endsection
@section('content')
<div style="margin-left: 5%">
    <div>
        <div style="width: 35%; margin-left: 19%" >    
        <a href="{{url('/')}}"><img src="{{asset('/images/lg.png')}}" width="120%" alt=""></a>
        </div>
    </div>
    <div class="flex-row d-flex justify-content-center">
        <div style="width: 35%;">
            @if(!empty($skidrowData))
            <?php $i = 0 ?>
                @foreach ($skidrowData as $skidrow)
                <?php $i++ ?>
                    <div class="card mb-5 post position-relative" style="background-color: rgba(255, 255, 255, 0.8)">
                        <img src="{{asset('/images/top-right.png')}}" class="position-absolute" style="top: -1px; right: -1px;" alt="" width="30%">
                        <img src="{{asset('/images/bottom-left.png')}}" class="position-absolute" style="bottom: -1px; left: -1px;" alt="" width="30%">
                        <div class="card-body shadow-lg" style="cursor: pointer;">
                            <form method="POST" action="{{url('/home/view')}}" id="{{$i}}">
                                @csrf
                                <input type="hidden" name="title" value="<?php echo $skidrow['title']?>">
                                <input type="hidden" name="link" value="{{$skidrow['link']}}">
                                <a href="javascript:{}" class="h5 link-secondary" style="text-decoration: none" onclick="document.getElementById('{{$i}}').submit()"><?php echo $skidrow['title']?></a>
                            </form>
                        </div>
                        <div class="card-body d-flex justify-content-center shadow-lg" style="cursor: pointer;">
                            <img class="shadow-lg" style="border-radius: 3px;" src="{{$skidrow['link_image']}}" width="241px" height="339px" on alt="">
                        </div>
                    </div>
                @endforeach
            @endif
            <script>
                $(document).ready(function(){
                    $('.post').mousedown(function(b){
                        if(b.which == 1){
                            $(this).find('form').attr('target', '');
                            $(this).find('a').click();
                        }
                        else if(b.which == 2){
                            $(this).find('form').attr('target', '_blank');
                            $(this).find('a').click();
                        }
                    });
                });
            </script>
            <div style="margin-top: -40px">
                @if(empty($issearch))
                    @if($numpage!=1)
                        <ul class="pagination d-flex justify-content-center pt-3">
                            @if ($page >= 5)
                                <li class="page-item"><a style="background-color: rgba(255, 255, 255, 0.5);" class="page-link link-secondary text-dark p-2 pt-0 pb-0" href="{{url('/home/page/1')}}">« First</a></li>
                                <li class="page-item"><a style="background-color: rgba(255, 255, 255, 0.5);" class="page-link link-secondary text-dark p-2 pt-0 pb-0" href="javascript:{}">...</a></li>
                                <li class="page-item"><a style="background-color: rgba(255, 255, 255, 0.5);" class="page-link link-secondary text-dark p-2 pt-0 pb-0" href="{{url('/home/page/'.$n=$page-1)}}">«</a></li>
                            @endif
                            @if($page > 2)
                                <li class="page-item"><a style="background-color: rgba(255, 255, 255, 0.5);" class="page-link link-secondary text-dark p-2 pt-0 pb-0" href="{{url('/home/page/'.$n=$page-2)}}">{{$page-2}}</a></li>
                            @endif
                            @if($page > 1)
                                <li class="page-item"><a style="background-color: rgba(255, 255, 255, 0.5);" class="page-link link-secondary text-dark p-2 pt-0 pb-0" href="{{url('/home/page/'.$n=$page-1)}}">{{$page-1}}</a></li>
                            @endif
                            <li class="page-item active"><a style="background-color: rgba(255, 255, 255, 0.8); border-color: rgba(255, 255, 255, 0.8);"  class="page-link link-secondary text-dark p-2 pt-0 pb-0" href="javascript:{}">{{$page}}</a></li>
                            @if($page <= $numpage-1)
                                <li class="page-item"><a style="background-color: rgba(255, 255, 255, 0.5)" class="page-link link-secondary text-dark p-2 pt-0 pb-0" href="{{url('/home/page/'.$n=$page+1)}}">{{$page+1}}</a></li>
                            @endif
                            @if($page <= $numpage-2)
                                <li class="page-item"><a style="background-color: rgba(255, 255, 255, 0.5)" class="page-link link-secondary text-dark p-2 pt-0 pb-0" href="{{url('/home/page/'.$n=$page+2)}}">{{$page+2}}</a></li>
                            @endif
                            @if ($page <= $numpage-5)
                                <li class="page-item"><a style="background-color: rgba(255, 255, 255, 0.5);" class="page-link link-secondary text-dark p-2 pt-0 pb-0" href="{{url('/home/page/'.$n=$page+1)}}">»</a></li>
                                <li class="page-item"><a style="background-color: rgba(255, 255, 255, 0.5);" class="page-link link-secondary text-dark p-2 pt-0 pb-0" href="javascript:{}">...</a></li>
                                <li class="page-item"><a style="background-color: rgba(255, 255, 255, 0.5)" class="page-link link-secondary text-dark p-2 pt-0 pb-0" href="{{url('/home/page/'.$n=$numpage-2)}}">{{$numpage-2}}</a></li>
                                <li class="page-item"><a style="background-color: rgba(255, 255, 255, 0.5)" class="page-link link-secondary text-dark p-2 pt-0 pb-0" href="{{url('/home/page/'.$n=$numpage-1)}}">{{$numpage-1}}</a></li>
                                <li class="page-item"><a style="background-color: rgba(255, 255, 255, 0.5)" class="page-link link-secondary text-dark p-2 pt-0 pb-0" href="{{url('/home/page/'.$numpage)}}">{{$numpage}}</a></li>
                                <li class="page-item"><a style="background-color: rgba(255, 255, 255, 0.5);" class="page-link link-secondary text-dark p-2 pt-0 pb-0" href="{{url('/home/page/'.$numpage)}}">Last »</a></li>
                            @endif
                        </ul>
                    @endif
                @else
                    @if($issearch)
                        @if($numpage != 0 && $numpage != 1)
                            <ul class="pagination d-flex justify-content-center pt-3">
                                @if ($page >= 5)
                                    <li class="page-item"><a style="background-color: rgba(255, 255, 255, 0.5);" class="btnmovepage page-link link-secondary text-dark p-2 pt-0 pb-0" datalink="{{url('/home/search/1')}}">« First</a></li>
                                    <li class="page-item"><a style="background-color: rgba(255, 255, 255, 0.5);" class="btnmovepage page-link link-secondary text-dark p-2 pt-0 pb-0" datalink="javascript:{}">...</a></li>
                                    <li class="page-item"><a style="background-color: rgba(255, 255, 255, 0.5);" class="btnmovepage page-link link-secondary text-dark p-2 pt-0 pb-0" datalink="{{url('/home/search/'.$n=$page-1)}}">«</a></li>
                                @endif
                                @if($page > 2)
                                    <li class="page-item"><a style="background-color: rgba(255, 255, 255, 0.5);" class="btnmovepage page-link link-secondary text-dark p-2 pt-0 pb-0" datalink="{{url('/home/search/'.$n=$page-2)}}">{{$page-2}}</a></li>
                                @endif
                                @if($page > 1)
                                    <li class="page-item"><a style="background-color: rgba(255, 255, 255, 0.5);" class="btnmovepage page-link link-secondary text-dark p-2 pt-0 pb-0" datalink="{{url('/home/search/'.$n=$page-1)}}">{{$page-1}}</a></li>
                                @endif
                                <li class="page-item active"><a style="background-color: rgba(255, 255, 255, 0.8); border-color: rgba(255, 255, 255, 0.8);"  class="page-link link-secondary text-dark p-2 pt-0 pb-0" href="javascript:{}">{{$page}}</a></li>
                                @if($page <= $numpage-1)
                                    <li class="page-item"><a style="background-color: rgba(255, 255, 255, 0.5)" class="btnmovepage page-link link-secondary text-dark p-2 pt-0 pb-0" datalink="{{url('/home/search/'.$n=$page+1)}}">{{$page+1}}</a></li>
                                @endif
                                @if($page <= $numpage-2)
                                    <li class="page-item"><a style="background-color: rgba(255, 255, 255, 0.5)" class="btnmovepage page-link link-secondary text-dark p-2 pt-0 pb-0" datalink="{{url('/home/search/'.$n=$page+2)}}">{{$page+2}}</a></li>
                                @endif
                                @if ($page <= $numpage-5)
                                    <li class="page-item"><a style="background-color: rgba(255, 255, 255, 0.5);" class="btnmovepage page-link link-secondary text-dark p-2 pt-0 pb-0" datalink="{{url('/home/search/'.$n=$page+1)}}">»</a></li>
                                    <li class="page-item"><a style="background-color: rgba(255, 255, 255, 0.5);" class="btnmovepage page-link link-secondary text-dark p-2 pt-0 pb-0" datalink="javascript:{}">...</a></li>
                                    <li class="page-item"><a style="background-color: rgba(255, 255, 255, 0.5)" class="btnmovepage page-link link-secondary text-dark p-2 pt-0 pb-0" datalink="{{url('/home/search/'.$n=$numpage-2)}}">{{$numpage-2}}</a></li>
                                    <li class="page-item"><a style="background-color: rgba(255, 255, 255, 0.5)" class="btnmovepage page-link link-secondary text-dark p-2 pt-0 pb-0" datalink="{{url('/home/search/'.$n=$numpage-1)}}">{{$numpage-1}}</a></li>
                                    <li class="page-item"><a style="background-color: rgba(255, 255, 255, 0.5)" class="btnmovepage page-link link-secondary text-dark p-2 pt-0 pb-0" datalink="{{url('/home/search/'.$numpage)}}">{{$numpage}}</a></li>
                                    <li class="page-item"><a style="background-color: rgba(255, 255, 255, 0.5);" class="btnmovepage page-link link-secondary text-dark p-2 pt-0 pb-0" datalink="{{url('/home/search/'.$numpage)}}">Last »</a></li>
                                @endif
                            </ul>
                        @endif
                        <form action="" id="pageformmove" method="POST" style="display: none">
                            @csrf
                            <input type="hidden" name="searchkey" value="{{$key}}">
                        </form>
                        <script>
                            $(document).ready(function()
                            {
                                $('.btnmovepage').attr('href', 'javascript:{}');
                                $('.btnmovepage').click(function(){
                                    $('#pageformmove').attr('action', $(this).attr('datalink')).submit();
                                })
                            })
                        </script>
                    @endif
                @endif
            </div>
        </div>
        @if (!empty($topdownload))
            <div style="width: 15%;" class="m-5 mt-0 mr-0">
                <div class="d-flex position-relative" style="background-color: rgba(255, 255, 255, 0.8); width: fit-content; border-radius: 10px; border-top-right-radius: 0px">
                    <div class="flex-row" style="padding-right: 35px"> 
                        <form action="{{url('/home/search/1')}}" method="POST" id="frmsearch">
                            @csrf
                            <input required type="text" pattern="[a-zA-z0-9'- ]+" name="searchkey" id="" placeholder="Search" style="border-radius: 10px; border-top-right-radius: 0px;border-bottom-right-radius: 0px ;height: 35px;" class="bg-transparent form-control border-0" @if(!empty($key)) value="{{$key}}" @endif>
                            <div type="submit" class="shadow-sm" style="border-bottom-right-radius: 10px; background-color: rgba(255, 255, 255, 0.2); position: absolute; top: 0; right: 0; width: fit-content; padding: 5px">
                                <img id="searchimg" style="cursor: pointer;" src="{{asset('/icons/search.svg')}}" width="24px" height="24px">
                                <input type="submit" style="display: none" id="frmsearchbtnsubmit">
                            </div>
                        </form>
                        <script>
                            $('#searchimg').click(function()
                            {
                                $('#frmsearchbtnsubmit').click();
                            });
                        </script>
                    </div>
                </div>
                <hr class="text-white">
                <div class="card" style="background-color: rgba(255, 255, 255, 0.8)">
                    <div class="card-header">
                        <span class="h5 text-dark" >TOP DOWNLOADED</span>
                    </div>
                </div>
                <hr width="90%" style="margin-left: 5%" class="mb-2 text-white">
                @foreach ($topdownload as $value)
                    <div class="d-flex justify-content-center">
                        <a href="{{url('/home/game/'.$value->id)}}">
                            <img src="{{$value->link_image}}" width="241px" height="339px" class="img-thumbnail" alt=""/>
                        </a>
                    </div>
                    <hr width="90%" style="margin-left: 5%" class="mt-2 mb-2 text-white">
                @endforeach
            </div>
        @endif
    </div>
    <div>
        <div align="center" class="mb-2" style="margin-top: 100px" >    
            <hr width="60%" style="height: 2px; background-color: white">
            <a href="{{url('/')}}" style="text-decoration: none"><span class="h6 text-muted">PCGAMESCRACK.CF</span></a>
        </div>
    </div>
</div>
@endsection