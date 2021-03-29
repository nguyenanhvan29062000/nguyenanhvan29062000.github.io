@extends('master')
@section('title')
<title>{{$title}}</title>
@endsection
@section('headp')
@endsection
@section('content')
<div>
    <div class="d-flex justify-content-center" >
        <img hidden src="{{$link_image}}" alt="">  
        <a href="{{url('/')}}">
            <img src="{{asset('/images/lg.png')}}" width="100%" alt="">
        </a>
    </div>
</div>
<div class="w-50 m-auto card shadow-lg" style="background-color: rgba(255, 255, 255, 0.8)">
    @if (!empty($data))
        <div class="card-header"><span class="h3 font-weight-bold"><?php echo $title; ?></span></div>
        <div class="d-flex justify-content-center mt-3 mb-3">
            <div id="media-preview" style="width: 854px; position: relative;">
                <script>
                    $(document).ready(function(){
                        var width = $('#media-preview').width();
                        $('#media-preview').height(width/1.77777);
                        $(window).resize(function() {
                            var width = $('#media-preview').width();
                            $('#media-preview').height(width/1.77777);
                        });
                    });
                </script>
                @if (!empty($data['youtube_link']))
                    <iframe class="shadow" style="position: absolute; top: 0; left: 0; display: none; border-radius: 8px" src="{{$data['youtube_link']}}" width="100%" height="100%" allowfullscreen="true" frameborder="0"></iframe>
                @endif
                @if (!empty($data['list_image']))
                    <img style="cursor: pointer; position: absolute; top: 0px; left: -3px; display: none;border-radius: 8px" class="m-1 mt-0" src="" height="100%" width="100%"/>
                @endif
            </div>
        </div>
        @if (!empty($data['list_image']))
            <div class="d-flex justify-content-center mb-3">
                <div id="list-media" style="height: 170px; width: 854px; overflow-x: auto;" class="d-flex">
                    @if (!empty($data['youtube_id']))
                        <div class="preview-video m-1 mt-0 position-relative">
                            <img class="shadow-sm" style="cursor: pointer; border-radius: 8px;" src="https://i.ytimg.com/vi/{{$data['youtube_id']}}/sddefault.jpg" height="150px"/>
                            <img class="position-absolute" style="top: 55px; left: 66px; cursor: pointer;" src="{{asset('/images/playbtn.png')}}" height="40px" alt="">
                        </div>
                    @endif
                    @for($i = 1; $i < count($data['list_image']); $i++)
                        <img style="cursor: pointer; border-radius: 8px;" class="preview-img m-1 mt-0 shadow-sm" src="{{$data['list_image'][$i]}}" height="150px"/>
                    @endfor
                </div>
            </div>
        @endif
        <script>
            $(document).ready(function(){
                if($('#list-media').children()[0].getAttribute('class') == "preview-video m-1 mt-0 position-relative")
                {
                    $('#media-preview').find('iframe').css('display', 'block');
                }
                else if($('#list-media').children()[0].getAttribute('class') == "preview-img m-1 mt-0 shadow-sm")
                {
                    var source = $('#list-media').children()[0].getAttribute('src');
                    $('#media-preview').find('img').attr('src', source).css('display', 'block'); 
                }
                $('.preview-img').click(function(){
                    var source = $(this).attr('src');
                    $('#media-preview').find('iframe').css('display', 'none');
                    $('#media-preview').find('img').attr('src', source).css('display', 'block');               
                });
                $('.preview-video').click(function(){
                    $('#media-preview').find('img').css('display', 'none');
                    $('#media-preview').find('iframe').css('display', 'block');
                });
            });
        </script>
        <div class="card bg-transparent mb-3">
            <div class="card-header"><strong>SYSTEM REQUIREMENT</strong></div>
            <div class="card-body p-5 pt-0 pr-0 pb-0">
                <strong>MINIMUM:</strong>
                <ul>
                    <?php print($data['require_minimum']); ?>
                </ul>
                <strong>RECOMMENDED:</strong>
                <ul>
                    <?php print($data['require_recommended']); ?>
                </ul>
            </div>
        </div>
        <div class="card bg-transparent mb-3">
            <div class="card-header"><strong>DOWNLOAD </strong><span>(Size: {{$data['size']}})</span></div>
            <div class="card-body p-5 pt-0 pr-0 pb-0">
                <div class="mt-2 mb-2">
                    <strong>TORRENT:</strong>
                    <a id="torrent-link" class="link-prime" href="javascript:{}" onclick='document.getElementById("frmdownload").submit(); $.post("{{url('/countdownloaded')}}",{"_token": "{{ csrf_token() }}","id": "{{$id}}"})' style="text-decoration: none">{{$title}}</a>
                    <form action="{{url('/wait_download')}}" method="POST" id="frmdownload">
                        @csrf
                        <input type="hidden" name="linkdata" value="{{$data['torrent_link']}}"/>
                        <input type="hidden" name="linkdataskidrow" value="@if(!empty($linkdataskidrow)){{$linkdataskidrow}}@else{{session()->get('linkdataskidrow')}}@endif">
                    </form>
                    @if(!empty($data['yandex_link']))
                        <strong>YANDEX:</strong>
                        <a class="link-prime dlbtn" href="javascript:{}" linkdata="{{$data['yandex_link']}}" style="text-decoration: none">{{$title.' - YANDEX.COM'}}</a><br>
                    @endif
                    @if(!empty($data['mediafire_link']))
                        <strong>MEDIAFIRE:</strong>
                        <a class="link-prime dlbtn" href="javascript:{}" linkdata="{{$data['mediafire_link']}}" style="text-decoration: none">{{$title.' - MEDIAFIRE.COM'}}</a><br>
                    @endif
                    @if(!empty($data['mediafire_link']))
                        <strong>GOFILE:</strong>
                        <a class="link-prime dlbtn" href="javascript:{}" linkdata="{{$data['gofile_link']}}" style="text-decoration: none">{{$title.' - GOFILE.IO'}}</a>
                    @endif
                </div>
            </div>
        </div>
        <script>
            $('.dlbtn').click(function(){
                var link = $(this).attr('linkdata');
                $.post(
                    "{{url('/countdownloaded')}}",
                    {
                        "_token": "{{ csrf_token() }}",
                        "id": "{{$id}}"
                    }
                ).done(function(data){
                    window.location.href = link;
                });
            });
        </script>
    @endif
</div>
<script>
    $(window).on('load', function(){
    window.scrollTo(0, 255);
    var img = $('.preview-video').children().eq(0);
    var originWidth = img.prop('naturalWidth');
    var originHeight = img.prop('naturalHeight');
    var id = "{{$data['youtube_id']}}";
    if(originWidth == 120 && originHeight == 90)
    {
        img.attr('src', 'https://i.ytimg.com/vi_webp/' + id + '/hqdefault.webp');
    }

});
</script>
@endsection