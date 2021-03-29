<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
include('skidrowreloaded.php');

class clonegame extends Controller
{
    public function Home(Request $request)
    {
        $skidrow = new skidrowreloaded();
        $page = $request->route('page');
        $skidrowData = $skidrow->getHomeData($page);
        $numpage = (int)$skidrowData[1];
        $topdownload = $skidrowData[2];
        $skidrowData = $skidrowData[0];
        foreach($skidrowData as $link)
        {
            $skidrow->saveData($link);
        }
        return view('home_page', compact('skidrowData', 'page', 'numpage', 'topdownload'));
    }

    public function selectGame(Request $request)
    {
        $skidrow = new skidrowreloaded();
        $link = $request['link'];
        if($skidrow->checkPageData($link))
        {
            $db = DB::select('select `id`,`link_image`,`update`,`update_at` from skidrowreloaded where link = ?', [$link]);
            $id = $db[0]->id;
            $update = $db[0]->update;
            $timestamp = $db[0]->update_at;
            $timestamp = strtotime($timestamp);
            $currenttime = time();
            $spacetime = $currenttime-$timestamp;
            if($update < 10)
            {
                if($spacetime/43200 < 1)
                return redirect('/home/game/'.$id)->with(['linkdataskidrow' => $link]);
                else goto action;
            }
            elseif($update >= 10 && $update <= 20)
            {
                if($spacetime/302400 < 1)
                return redirect('/home/game/'.$id)->with(['linkdataskidrow' => $link]);
                else goto action;
            }
            elseif($update > 20)
            {
                if($spacetime/1298000 < 1)
                return redirect('/home/game/'.$id)->with(['linkdataskidrow' => $link]);
                else goto action;
            }
            action:
            {
                try{
                    $data = $skidrow->getPageData($link);
                    $data = json_encode($data);
                    DB::table('skidrowreloaded')->where('link','=',$link)->update(['data'=>$data]);
                    if(DB::update('update skidrowreloaded set `update` = ? where id = ?', [$update+1,$id]))
                        return redirect('/home/game/'.$id)->with(['linkdataskidrow' => $link]);
                }catch(\Exception $e)
                {
                    if(DB::update('update skidrowreloaded set `update` = ? where id = ?', [$update+1,$id]))
                        return redirect('/home/game/'.$id)->with(['linkdataskidrow' => $link]);
                }
            }
        }
        else
        {
            try{
                $data = $skidrow->getPageData($link);
                $skidrow->savePageData($link,$data);
                $id = DB::select('select id from skidrowreloaded where link = ?', [$link]);
                $id = $id[0]->id;
                return redirect('/home/game/'.$id)->with(['linkdataskidrow' => $link]);
            }
            catch(\Exception $e)
            {
                echo "LINK DIE";
            }
        } 
    }
    public function getFullPageData()
    {
        $skidrow = new skidrowreloaded();
        $db = DB::select('select id, link from skidrowreloaded');
        foreach($db as $ndata)
        {
            if($ndata->id > 5754)
            {
                try{
                    $data = $skidrow->getPageData($ndata->link);
                $skidrow->savePageData($ndata->link,$data);
                echo "id: ".$ndata->id." => saved<br>";
                } catch (\Exception $e)
                {
                    echo "id: ".$ndata->id." => error<br>";
                }
            }
        }
    }
    public function GameID(Request $request)
    {
        $id = $request->route('id');
        $db = DB::select('select `link`,`title`,`link_image`,`data` from skidrowreloaded where id = ?', [$id]);
        $data = (array)json_decode($db[0]->data);
        $linkdataskidrow = $db[0]->link;
        $title = $db[0]->title;
        $link_image = $db[0]->link_image;
        return view('selected_game', compact('data', 'title', 'id', 'linkdataskidrow', 'link_image'));
    }
    public function waitdownload(Request $request)
    {
        $skidrow = new skidrowreloaded();
        $link = $request['linkdata'];
        if(!empty($link))
        {
            try{
                $downlink = $skidrow->getDownLink($link);
                $content = $skidrow->get_content($downlink['link']);
                $name = $downlink['name'];
                header("Content-type: text/plain");
                header("Content-Disposition: attachment; filename=$name");
                echo $content;
            }catch(\Exception $e){
                try{
                    $linkdataskidrow = $request['linkdataskidrow'];
                    $linkdatacontent = $skidrow->get_content($linkdataskidrow);
                    $another_torrent_link = $skidrow->get_another_torrent_link($linkdatacontent);
                    echo "SORRY, THIS FILE NOT FOUND!<br>";
                    echo "IF ANOTHER LINK SHOW UP! U CAN TRY WITH IT<br>";
                    foreach($another_torrent_link as $another)
                    {
                        echo '<a href="'.$another.'">'.$another.'</a><br>';
                    }
                }catch(\Exception $e)
                {
                    echo "MAYBE THIS GAME WAS DELETED!";
                }
            }
        }
        else
        {
            try{
                $linkdataskidrow = $request['linkdataskidrow'];
                $linkdatacontent = $skidrow->get_content($linkdataskidrow);
                $another_torrent_link = $skidrow->get_another_torrent_link($linkdatacontent);
                echo "SORRY, THIS FILE NOT FOUND!<br>";
                echo "IF ANOTHER LINK SHOW UP! U CAN TRY WITH IT<br>";
                foreach($another_torrent_link as $another)
                {
                    echo '<a href="'.$another.'">'.$another.'</a><br>';
                }
            }catch(\Exception $e)
            {
                echo "MAYBE THIS GAME WAS DELETED!";
            }
        }
        
    }
    public function countDownloaded(Request $request)
    {
        $id = $request['id'];
        $downloaded = DB::table('skidrowreloaded')->select('downloaded')->where('id', '=', $id)->get();
        $downloaded = $downloaded[0]->downloaded + 1;
        if(DB::table('skidrowreloaded')->where('id', '=', $id)->update(['downloaded'=>$downloaded])){
            return true;
        }
        else
        {
            return false;
        }
    }

    public function search(Request $request)
    {
        $key = $request['searchkey'];
        $page = $request->route('page');
        $data = DB::select("select * from `skidrowreloaded` where title like '%".$key."%'");
        $topdownload = DB::table('skidrowreloaded')->select('id', 'link', 'link_image', 'downloaded')->orderBy('downloaded', 'desc')->take(10)->get();
        $sumrow = count($data);
        $numpage = 1;
        $skidrowData = array();
        if($sumrow > 9 && $sumrow%9 != 0)
        {
            $numpage = floor($sumrow/9) + 1;
        } elseif ($sumrow%9 == 0)
        {
            $numpage = floor($sumrow/9);
        }
        $start = $page * 9 - 9;
        $end = $page * 9;
        if($end > $sumrow) $end = $sumrow;
        for($i = $start; $i < $end; $i++)
        {
            $skidrowData[count($skidrowData)] = (array)$data[$i];
        }
        $issearch = true;
        return view('home_page', compact('skidrowData', 'page', 'numpage', 'topdownload', 'issearch', 'key'));
    }
}
