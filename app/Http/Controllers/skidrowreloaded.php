<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class skidrowreloaded extends Controller
{
    public function getHomeData($page)
    {
        $url = "https://www.skidrowreloaded.com/page/";
        if($page == 1) $url = "https://www.skidrowreloaded.com";
        else $url = $url . $page;
        $content = $this->get_content($url);
        $list = $this->get_list_link($content);
        $numpage = $this->get_numpage($content);
        $topdownload = DB::table('skidrowreloaded')->select('id', 'link', 'link_image', 'downloaded')->orderBy('downloaded', 'desc')->take(10)->get();
        return array($list, $numpage, $topdownload);
    }

    public function getPageData($link)
    {
        $content = $this->get_content($link);
        $steam_link = $this->get_steam_link($content);
        $youtube_link = $this->get_youtube_link($content);
        $list_image = $this->get_list_image_link($content);
        $torrent_link = $this->get_torrent_link($content);
        $yandex_link = $this->get_yandex_link($content);
        $mediafire_link = $this->get_mediafire_link($content);
        $gofile_link = $this->get_gofile_link($content);
        $require = $this->get_system_require($content);
        $size = $this->get_size($content);
        $youtube_id = "";
        if(preg_match('/www.youtube.com\/embed\/([a-zA-z0-9\-]+)(.*)/', $youtube_link, $youtube))
        {
            $youtube_id = $youtube[1];
        }
        return array(
            'steam_link' => $steam_link,
            'youtube_link' => $youtube_link,
            'youtube_id' => $youtube_id,
            'list_image' => $list_image,
            'torrent_link' => $torrent_link,
            'yandex_link' => $yandex_link,
            'mediafire_link' => $mediafire_link,
            'gofile_link' => $gofile_link,
            'require_minimum' => $require['minimum'],
            'require_recommended' => $require['recommended'],
            'size' => $size
        );
    }
    public function getDownLink($torrent_link)
    {
        $zippyshare = "";
        if($torrent_link != "https://www.skidrowreloaded.com" && str_contains($torrent_link, "zippyshare.com"))
        {
            $content = $this->get_content($torrent_link);
            $zippyshare = $this->get_download_zippyshare($torrent_link,$content);
        }
        return $zippyshare;
    }
    private function get_list_link($content)
    {
        $list = array();
        if(preg_match_all('/<h2>(.*)<\/h2>/', $content, $matches))
        {
            foreach($matches[1] as $key)
            {
                $list[count($list)]['link'] = "";
                $list[count($list)-1]['title'] = "";
                if(preg_match('/href="(.*)"/', $key, $href))
                {
                    $list[count($list)-1]['link'] = $href[1];
                }
                if(preg_match('/">(.*)</', $key, $title))
                {
                    $list[count($list)-1]['title'] = $title[1];
                }
            }
            if(preg_match_all('/<img[\040](|id="imgElement"[\040])class="(aligncenter|lazy[\040]aligncenter[\040]lazy-loaded|lazy[\040]|alignnone|)"[\040](|title="Click[\040]for[\040]a[\040]larger[\040]view"[\040])src=("|)(.*?)"/', $content, $image))
            {
                for($i=0; $i < count($list); $i++)
                {
                    $list[$i]['link_image'] = $image[5][$i];
                }
            }
        }
        
        return $list;
    }
    public function get_numpage($content)
    {
        $numpage = "";
        if(preg_match('/Page(.*)of[\040](.*)/',$content, $last))
        {
            $numpage = $last[2];
        }
        return $numpage;
    }
    public function saveData($link)
    {
        $slink = $link['link'];
        if(!$this->checkData($slink))
        {
            $stitle = $link['title'];
            $simage = $link['link_image'];
            DB::table('skidrowreloaded')->insert(['link'=>$slink, 'title'=>$stitle, 'link_image'=>$simage]);
            if(!empty($data = $this->getPageData($slink))){
                $this->savePageData($slink,$data);
            }
        }
    }
    public function savePageData($link,$data)
    {
        if(!$this->checkPageData($link))
        {
            $data = json_encode($data);
            DB::table('skidrowreloaded')->where('link','=',$link)->update(['data'=>$data]);
        }
    }
    private function checkData($link)
    {
        if(DB::select('select * from skidrowreloaded where link = ?', [$link]))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    public function checkPageData($link)
    {
        $data = DB::select('select data from skidrowreloaded where link = ?', [$link]);
        if($data[0]->data == NULL)
        {
            return false;
        }
        else
        {
            return true;
        }
    }
    public function get_content($link)
    {
        $context = stream_context_create(array('https' => array('header' => 'Connection: close\r\n')));
        return file_get_contents($link, false, $context);
    }

    private function get_system_require($content)
    {
        $system_require = array(
            'minimum' => '',
            'recommended' => '',
        );
        if(preg_match('/(MINIMUM)(.*)<ul[\040]class="bb_ul">(.*)<\/ul>/s', $content, $minium))
        {
            $system_require['minimum'] = $minium[3];
        }
        elseif(preg_match('/Minimum:<\/p>(.*?)<\/div>/s', $content, $minium))
        {
            $system_require['minimum'] = $minium[1];
        }
        elseif(preg_match('/Minimum:(.*?)<\/p>/s', $content, $minium))
        {
            $system_require['minimum'] = substr_replace($minium[1],"", 0, 6);
        }
        if(preg_match('/RECOMMENDED(.*)<ul[\040]class="bb_ul">(.*)<\/ul>/s', $content, $recommended))
        {
            $system_require['recommended'] = $recommended[2];
        }
        elseif(preg_match('/Recommended:<\/p>(.*?)<\/div>/s', $content, $recommended))
        {
            $system_require['recommended'] = $recommended[1];
        }
        elseif(preg_match('/Recommended:(.*?)<\/p>/s', $content, $recommended))
        {
            $system_require['recommended'] = substr_replace($recommended[1],"", 0, 6);
        }

        return $system_require;
        
    }
    private function get_size($content)
    {
        $size = "";
        if(preg_match('/(Size|SIZE):[\040](.*)[\040](MB|GB?)/', $content, $nsize))
        {
            $size = $nsize[2] . " " . $nsize[3];
        }
        return $size;
    }
    private function get_steam_link($content)
    {
        $steam_link="";
        if(preg_match('/<a href="https:\/\/store.steampowered.com\/app\/(.*?)"/s', $content, $steam))
        {
            $steam_link = $steam[1];
        }
        return $steam_link;
    }
    private function get_youtube_link($content)
    {
        $youtube_link="";
        if(preg_match('/<iframe[\040]src="(.*?)"/s', $content, $youtube))
        {
            $youtube_link = $youtube[1];
        }
        return $youtube_link;
    }
    private function get_list_image_link($content)
    {
        $list_image_link=array();
        if(preg_match_all('/<img[\040]class="aligncenter"[\040]src="(.*)"[\040]alt/', $content, $list_image))
        {
            $list_image_link = $list_image[1];
        }
        return $list_image_link;
    }
    private function get_torrent_link($content)
    {
        $torrent_link="";
        if(preg_match('/<strong>TORRENT<\/strong>(.*?)<\/a>/s', $content, $temp))
        {
            if(preg_match('/href="(.*?)"/s', $temp[1], $torrent))
            {
                if($torrent[1] != "https://www.skidrowreloaded.com") $torrent_link = $torrent[1];
                else $torrent_link = '';
            }
        }
        return $torrent_link;
    }
    public function get_another_torrent_link($content)
    {
        $another_torrent_link=array();
        if(preg_match_all('/<strong>ANOTHER[\040]TORRENT<\/strong>(.*?)<\/a>/s', $content, $temp))
        {
            for($i = 1; $i <= count($temp); $i++)
            {
                if(preg_match('/href="(.*?)"/s', $temp[1][$i], $torrent))
                {
                    if($torrent[1] != "https://www.skidrowreloaded.com") $another_torrent_link[count($another_torrent_link)] = $torrent[1];
                    else $another_torrent_link[count($another_torrent_link)] = '';
                    
                }
            }
        }
        return $another_torrent_link;
    }
    private function get_yandex_link($content)
    {
        $yandex_link="";
        if(preg_match('/<strong>YANDEX<\/strong>(.*?)<\/a>/s', $content, $temp))
        {
            if(preg_match('/href="(.*?)"/s', $temp[1], $yandex))
            {
                if($yandex[1] != "https://www.skidrowreloaded.com") $yandex_link = $yandex[1];
                else $yandex_link = '';
            }
        }
        return $yandex_link;
    }
    private function get_mediafire_link($content)
    {
        $mediafire_link="";
        if(preg_match('/<strong>MEDIAFIRE<\/strong>(.*?)<\/a>/s', $content, $temp))
        {
            if(preg_match('/href="(.*?)"/s', $temp[1], $mediafire))
            {
                if($mediafire[1] != "https://www.skidrowreloaded.com") $mediafire_link = $mediafire[1];
                else $mediafire_link = '';
            }
        }
        return $mediafire_link;
    }
    private function get_gofile_link($content)
    {
        $gofile_link="";
        if(preg_match('/<strong>GOFILE<\/strong>(.*?)<\/a>/s', $content, $temp))
        {
            if(preg_match('/href="(.*?)"/s', $temp[1], $gofile))
            {
                if($gofile[1] != "https://www.skidrowreloaded.com") $gofile_link = $gofile[1];
                else $gofile_link = '';
            }
        }
        return $gofile_link;
    }
    private function get_download_zippyshare($torrent_link, $content)
    {
        $torrent_link_download = "";
        $id="";
        $name="";
        if(preg_match('/var[\040]a[\040]=[\040](.*);[\n][\s]+var[\040]b[\040]=[\040](.*);/', $content, $download))
        {
            $a = $download[1];
            $b = $download[2];
            $id = floor($a/3) + $a%$b;
        }
        if(preg_match('/<font[\040]style="line-height:20px;[\040]font-size:[\040]14px;">(.*)<\/font>/', $content, $nname))
        {
            $name = $nname[1];
        }
        preg_match('/(.*)\/v\/(.*)\//', $torrent_link, $key);
        $torrent_link_download = $key[1] . "/d/" . $key[2] . "/" . $id . "/" .$name;
        return array(
            "link" => $torrent_link_download,
            "name" => $name
        );
    }
}
