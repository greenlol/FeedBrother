<?php
/*

Зачатки роутинга 


 $routeArray = explode('/', $_SERVER['REQUEST_URI']);
    // удаляем пустые элементы массива (элементы образованные начальным и конечным слэшами URI)
    // тут можно было обойтить array_shift и array_pop - но мне способ с foreach кажется более "универсальным"
    $route = array();
    foreach ($routeArray as $value) {
        if (!empty($value)) {
            $route[] = trim($value);
        }

    }
// вводим в адресную строку всякий бред, смотрим что нам показывают
echo "<pre>";
//print_r($route);
echo "</pre>";

switch ($route['0']) 
{
    case 'hui':
        echo "hui";
        break;
    
    case 'pizda':

       echo "pizda";
       break;

    default:
        echo "bred";
        break;
}
exit;
*/

xdebug_start_trace();

if (isset($_GET['act']) AND $_GET['act']=="logout") {
  session_start();
  session_destroy();
  header("Location: http://".$_SERVER['HTTP_HOST']."/index.php");
  exit;
}

set_time_limit(3600);
//замер времени выполнения кода 


// пока не используется PDO, используется обычный драйвер, отлвоа ошибок ничего нет
$host = "localhost";   
$user = "root";   
$pass = "13";    
 if(!mysql_connect($host, $user, $pass)) exit(mysql_error()); 
mysql_query("SET character_set_client='UTF8'"); 
mysql_query("SET character_set_results='UTF8'"); 
mysql_query("SET collation_connection='UTF8'");
mysql_query("SET NAMES UTF8");
mysql_select_db("frfeed") or die(mysql_error()); 

 //Создаём новый объект. Также можно писать и в процедурном стиле
    $memcache_obj = new Memcache;
 
    //Соединяемся с нашим сервером
    $memcache_obj->connect('127.0.0.1', 11211) or die("could not connect");


session_start();


$vk = new VkApi(array(
    'apiKey' => '',
    'appId' => '',
    'authRedirectUrl' => 'http://192.168.1.141/index.php',
));
 
 // строка для отправки запроса в виде строки с gids групп для groups.get 
$GroupIdsStr = "";
$FriendFeedarray = [];
$sessionid = $_SESSION['id'];

                //$friendid['0']['last_name'] = "";
               // $friendid['0']['first_name'] = $_SESSION['fullname'];
                //$friendid['0']['photo_medium'] = $_SESSION['img'];

$urlMyPage = "http://192.168.1.141/index.php?back=".$sessionid;
session_write_close();
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>FriendFeed просматривай ленты друзей</title>
     <link href='http://fonts.googleapis.com/css?family=Hammersmith+One' rel='stylesheet' type='text/css'>
    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/agency.css" rel="stylesheet">


     <link rel="stylesheet" type="text/css" href="source/jquery.fancybox.css?v=2.1.5" media="screen" />
  <link rel="stylesheet" type="text/css" href="source/helpers/jquery.fancybox-buttons.css?v=1.0.5" />
  <link rel="stylesheet" type="text/css" href="source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" />



    <!-- Custom Fonts -->
    <link href="font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href='http://fonts.googleapis.com/css?family=Kaushan+Script' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700' rel='stylesheet' type='text/css'>
   



    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>

body {
    padding-top: 100px; /* Required padding for .navbar-fixed-top. Remove if using .navbar-static-top. Change if height of navigation changes. */
     background-color: #E8E8E8;
}

</style>

 

</head>
<body>

    <!-- Navigation -->
   <nav class="navbar navbar-default navbar-fixed-top navbar-shrink">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="http://192.168.1.141/index.php">  <font class="menutexttopglyph"> <span class="glyphicon glyphicon-th-list "></span></font><font class="menutexttop">&nbsp;FriendFeed</a></font> 
            </div>
            <?php 
            session_start();
            ?>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                <li>
                 <center><a href=<?php echo $urlMyPage; ?>>   <img src=<?php echo $_SESSION['img']; ?> width="50px" heigth="50px"></a>&nbsp;&nbsp;&nbsp;&nbsp;</center>
                </li>
                    <li  class="toppullrightlink">  

                      <a href="http://192.168.1.141/index.php?act=logout" class="toppullrightlink"><font class="toppullrightlink smalarrow">выйти</font></a>
                        </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
        <?php
        session_write_close();
        ?>

    </nav>
    



<?php
// здесь пока будет блок вспомогательных функций, потом в отдельный файл вынесу

function link_it($s)
{
       return preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1" target="_blank">$1</a>', $s); 
}

function FeedDiffarray($a, $b) {
    if ($a === $b) { return 0; }
    return ($a > $b)? 1:-1;
}

function FeedArraySlayer($array)
    {
        return array_slice($array, 0, 100);
    }

function checkdatearr($datear)
{
    $diff = time() - $datear;
    if($diff > 832000)
    {
        return 0;
    }
    else
    {
        return 1;
    }
}



/**
 * @class VkApi
 * @author Maslakov Alexander <jmas.ukraine@gmail.com> класс для работы с VK написал вот этот чувачек
 */

class VkApi
{
    public $apiKey;
    public $appId;
    public $login;
    public $password;
    public $authRedirectUrl;
    public $apiUrl = 'https://api.vk.com/method/';
    public $v = '2.0';
    private $_sid;
   
   
    public function __construct($options)
    {
        foreach ($options as $key=>$value) {
            $this->{$key} = $value;
        }
       
        $this->_auth();
    }
   
   
    private function _auth()
    {


       // $token = file_get_contents('./Cookies.txt');
       $token = $_SESSION['tok'];
        if (isset($_GET['code'])) {
            $url  = 'https://oauth.vk.com/access_token?client_id='.$this->appId.'&client_secret='. $this->apiKey .'&code=' . $_GET['code'] . '&redirect_uri=' . $this->authRedirectUrl;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_REFERER, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
           
            $responce = curl_exec($ch);
           
            curl_close($ch);
   
            $responce = json_decode($responce);
       
            if (isset($responce->access_token)) {
                $_SESSION['tok'] = $responce->access_token;
             //    file_put_contents('./Cookies.txt', $responce->access_token);
                 $token = $responce->access_token;
                 $owner_id = $responce->user_id;
                 //поулчаем данные о себе чтобы записать нас в сессию
                 $GetProfile = file_get_contents("https://api.vk.com/method/users.get?fields=nickname,screen_name,photo_medium,photo_big,city,bdate,sex&uid=$owner_id&access_token=$token");
                 $profile = json_decode($GetProfile , true);

                 $fullName = $profile['response']['0']['first_name']." ".$profile['response']['0']['last_name'];
                 $profileimg = $profile['response']['0']['photo_medium'];
                 $profilescreenname = $profile['response']['0']['nickname'];
                 // скидываем себя самого в сессию, чтобы быстро извлекать после авторизации
                 $_SESSION['id'] = $owner_id;
                $_SESSION['fullname'] = $fullName;
                $_SESSION['img'] = $profileimg;
            // если такой есть в базе то ничего, идем дальше, то есть мы
            if(empty(mysql_fetch_assoc(mysql_query("SELECT * FROM users WHERE id_vk = '$owner_id'"))))
               {                
                    mysql_query("INSERT INTO users VALUES (null, '$owner_id', '$profileimg', '$fullName', '$profilescreenname')") or die(mysql_error());
                    mysql_close();                
            
                }

            } else  throw new Exception('VK API error.');             
            
        }
       
        if (empty($token)) {

            $url = "https://oauth.vk.com/authorize?client_id="
                   . $this->appId . "&redirect_uri=http://192.168.1.141/index.php&display=page&response_type=code&scope=video,offline,groups,friends,photos,notify";
 
            header('Location: ' . $url); 
      
        }
       
        $this->_accessToken = $token;

    }
 
    public function get($method, $params=false)
    {

                if (! $params) $params = array();
 
                $params['format'] = 'json';
        $url = $this->apiUrl . $method;
        $params['access_token'] = $this->_accessToken;
               
        ksort($params);
               
        $sig = '';
               
        foreach ($params as $k=>$v) {
                        $sig .= $k.'='.$v;
                }
               
        $sig .= $this->apiKey;
               
        $params['sig'] = md5($sig);
               
        $query = $url . '?' . $this->_params($params);
       
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_URL, $query);
        curl_setopt($ch, CURLOPT_REFERER, $query);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322)");
        $res = curl_exec($ch);
        curl_close($ch);
 
        return json_decode($res, true);

        }
   
 
    public function getLikesCountByUrl($pageUrl)
    {
        if (parse_url($pageUrl, PHP_URL_HOST) != parse_url($this->authRedirectUrl, PHP_URL_HOST)) {
            throw new Exception('Page URL not valid!');
        }
       
        $request = $this->get('likes.getList', array(
            'type' => 'sitepage',
            'owner_id' => $this->appId,
            'page_url' => $pageUrl,
        ));
       
        $this->_responce($request);
    }
 
 
    public function searchVideo($q, $offset)
    {        
        $request = $this->get('video.search', array(
            'q' => $q,
            'offset' => $offset,
        ));
 
        return $this->_responce($request);
    }
   
   
    public function getWallGroups($owner_id)
    {
        
        $request = $this->get('wall.get', array(
            'owner_id' => "-".$owner_id, 
            'count' => "4",          
            
        ));
       
        return $this->_responce($request);
    }

    public function getGroups($owner_id)
    {
        $request = $this->get('groups.get', array(
            'user_id' => $owner_id,
            'fields' => 'description,activity,members_count',
            'extended' => '1',            
        ));
 
        return $this->_responce($request);
    }

     public function getGroupsforWall($owner_id)
    {
        $request = $this->get('groups.get', array(
            'user_id' => $owner_id,                      
        ));
 
        return $this->_responce($request);
    }

     public function getFriends($owner_id)
    {
        $request = $this->get('friends.get', array(
            'owner_id' => $owner_id,
            'fields' => 'photo_medium',
            
        ));
 
        return $this->_responce($request);
    }

     public function getUsers($owner_id)
    {
        $request = $this->get('users.get', array(
            'uids' => $owner_id,
            'fields' => 'uid, first_name, last_name, nickname, photo_medium',
            
        ));
 
        return $this->_responce($request);
    }
       public function Friendsonline($owner_id)
    {
        $request = $this->get('friends.getOnline', array(
            'user_id' => $owner_id,            
            
        ));
 
        return $this->_responce($request);
    }


      public function getFeed($owner_id)
    {
        $request = $this->get('newsfeed.get', array(
            'owner_id' => $owner_id,
            
        ));
 
        return $this->_responce($request);
    }
    
       public function getGroupsById($ids)
    {
        $request = $this->get('groups.getById', array(
            'group_ids' => $ids,            
            
        ));
 
        return $this->_responce($request);
    }
 
 
        private function _params($params) {
                $pice = array();
                foreach($params as $k=>$v) {
                        $pice[] = $k.'='.urlencode($v);
                }
                return implode('&',$pice);
        }
 
 
    private function _responce($request)
    {
        if (isset($request['response'])) {
            return $request['response'];
        } else if (isset($request['error'])) {
            throw new Exception($request['error']['error_msg']);
        }
       
        return null;
    } 

    public function getExecuteFeedFriends($code)
    {
        $request = $this->get('execute', array(
            'code' => $code,                     
        ));
 
        return $this->_responce($request);
    }

}
//  класс с методами для формирования конечного вида ленты
class FriendFeed
{

    public function timeAgo($timestamp, $granularity=2, $format='Y-m-d H:i:s')
    { 
        $difference = time() - $timestamp; 
        if($difference < 0) return 'только что'; 
        elseif($difference < 864000)
            { 
                $periods = array('нд' => 604800,'дн' => 86400,'ч' => 3600,'м' => 60,'с' => 1); 
                $output = ''; 
                foreach($periods as $key => $value)
                    { if($difference >= $value)
                        { $time = round($difference / $value); 
                            $difference %= $value; $output .= ($output ? ' ' : '').$time.' '; 
                            $output .= (($time > 1 && $key == 'дней') ? $key.'секунд' : $key); 
                            $granularity--; 
                        } if($granularity == 0) break; 
                    } 
                    return ($output ? $output : '0 с').' назад'; 
            } 
                else return date($format, $timestamp); 
    }

    public function TimeFeedSort($FriendFeedarray)
    {
        for ($iii=0; $iii < count($FriendFeedarray); $iii++) 
            { 
                $TimeDatearray[] = $FriendFeedarray[$iii]['date'];
            }
         array_multisort($TimeDatearray,SORT_DESC,$FriendFeedarray);
         return $FriendFeedarray;
    }
     public function Timestamparray($FriendFeedarray)
    {
        for ($iii=0; $iii < count($FriendFeedarray); $iii++) 
            { 
                $TimeDatearray[] = $FriendFeedarray[$iii]['date'];
            }
       //  array_multisort($TimeDatearray,SORT_DESC,$FriendFeedarray);
         return $TimeDatearray;
    }
    // функция нужна для подкачки большего количества элементов ленты, мы будем пересчитывать смещение и строить куски ленты вручную
    public function Feedoffset($current, $now = 4)
    {


    }

 
 

} 


//$CurrentUsrarray = mysql_fetch_assoc(mysql_query("SELECT * from users WHERE id_vk='$_SESSION[id]'"));
if(isset($_GET['back']))
{
    $FriendFeedarray = $memcache_obj->get($sessionid."me"); 
  
  $FF = new FriendFeed();
 // $FriendFeedarray = $memcache_obj->get($_SESSION['id'].$_SESSION['id']);
 // $NewmessageCount = $memcache_obj->get($_SESSION['id']."countnewmessage");
 
     // кешируем список друзей пользователя 
  
    if(empty($memcache_obj->get($sessionid."friends")))
    {
        $listFriends[] = $vk->getFriends();
       
        $memcache_obj->set($sessionid."friends", $listFriends, false, 1200);
    }
    else
    {
        $listFriends = $memcache_obj->get($sessionid."friends");
    }
    

 // определение текущей активной ленты 
      $friendid = [];
      $frlist[] = $memcache_obj->get($sessionid."friends");    
       if($_GET['back'] == $sessionid)
            {
                  session_start();
                $friendid['0']['last_name'] = "";
                $friendid['0']['first_name'] = $_SESSION['fullname'];
                $friendid['0']['photo_medium'] = $_SESSION['img'];
                session_write_close();
               // var_dump($friendid);
               // break;
            }
            else
            {

                 for ($fr=0; $fr < count($frlist['0']['0']); $fr++) 
                    { 
                        if($_GET['back'] == $frlist['0']['0'][$fr]['uid'])
                        {
                         $friendid[] = $frlist['0']['0'][$fr];
                            break;
                        }

                     }
            }

    ?>

<div class="container">

        <div class="row">

                <!-- Blog Sidebar Widgets Column -->
            <div class="col-md-2 friendlistblock">
                 <div class="row">
                <div class="feedactiveprofile">
                 &nbsp;&nbsp;<h5>Активная лента</h5>
                
                <div class="media">
                <a class="pull-left" href=<?php echo "http://192.168.1.141/index.php?id=".$_GET['back']; ?>>
                  
                <img class="media-object" src=<?php echo $friendid['0']['photo_medium']; ?> width="80px" heigth="60px">
                </a>
                <div class="media-body"><br>
                 
                   
                    </div>

                    </div>
                      <h6><strong>&nbsp;<?php echo $friendid['0']['first_name']." ".$friendid['0']['last_name']; ?>&nbsp;</strong></h6><br>
            </div>
            </div>

                <!-- Blog Categories Well -->
               
                    <h5>Ленты друзей</h5>
                   
                    <div class="row">
                    <div class="col-md-12">

                     <?php
                         
                     for ($i=0; $i <1 ; $i++) for ($j=0; $j < count($listFriends['0']) ; $j++) 
                        {

                        ?> <div class="row"><div class="friends"><a href=<?php echo "http://192.168.1.141/index.php?id=".$listFriends[$i][$j]['uid']; ?> class="friendsfont"> <strong><b><?php echo $listFriends[$i][$j]['first_name']." ".$listFriends[$i][$j]['last_name']; ?></b></strong></a><br><a href=<?php echo "http://192.168.1.141/index.php?id=".$listFriends[$i][$j]['uid']; ?>><img src=<?php echo $listFriends[$i][$j]['photo_medium']; ?>></a><br></div></div>
                        <?php
                         } 
                         ?>             
                    </div>
                </div>

            </div>
            <!-- Blog Entries Column -->
<?php
$urlFeedupdate = "http://192.168.1.141/index.php?news=".$_GET['back'];
$urlFeedCountUpdate = "http://192.168.1.141/index.php?groups=".$_GET['back'];
$urlMyProfile = "http://192.168.1.141/index.php?id=".$sessionid;
session_write_close();
$FF = new FriendFeed();
?>


   <div class="col-md-7">
    <h5>
        Новостная лента 
    </h5><br>
    <?php
   // var_dump($FriendFeedarray);
   // var_dump($FriendFeedarray);
    ?>

  <center> <button class="btn" onclick="Intercooler.refresh($('#manual-update'));">Показать <font ic-src=<?php echo $urlFeedCountUpdate; ?> ic-poll="2s"></font> новых записей </button></center><br>

      <!--   -->



      <div id="manual-update" ic-src=<?php echo $urlFeedupdate; ?>>
                 


        <?php
       
            for ($iiii=0; $iiii < count($FriendFeedarray); $iiii++) 
                { 
      
                
        ?>
         <table class="table table-bordered row-fluid leftprofile1">
        <tr>
            <td >
                

                 <div class="media">
                <a class="pull-left" href=<?php echo "http://vk.com/".$FriendFeedarray[$iiii]['screen']; ?> target="_blank">
                  
                <img class="media-object" src= <?php echo $FriendFeedarray[$iiii]['groupphoto'];   ?>>
                </a>
                <div class="media-body">
                 &nbsp;<a href=<?php echo "http://vk.com/".$FriendFeedarray[$iiii]['screen']; ?> target="_blank"><strong> <?php echo $FriendFeedarray[$iiii]['groupname'];   ?></strong></a>
                   <?php  
                        if(!empty($FriendFeedarray[$iiii]['text']))
                        {
                            ?>
                           
                           <?php echo " "; ?> &nbsp; &nbsp; <div class="cutstring" data-display="none" data-max-length="200" data-show-text="Показать полностью.." data-hide-text="Свернуть..">                  
                       <?php echo " &nbsp;".$FriendFeedarray[$iiii]['text']; ?></div>
                        
                            <?php
                    
                        }
                   ?>

                        <?php
                         if(!empty($FriendFeedarray[$iiii]['photo']))
                        {

                         ?>
                            <br>
                            <?php
                         for ($ii=0; $ii < count($FriendFeedarray[$iiii]['photo']); $ii++) 
                            { 
                                 if($FriendFeedarray[$iiii]['photo'][$ii] != "")
                                            {
                            ?>               

        <a class="fancybox-effects-a" href=<?php echo $FriendFeedarray[$iiii]['photo'][$ii];   ?> data-fancybox-group="gallery" title=""><img src=<?php echo $FriendFeedarray[$iiii]['photo'][$ii];   ?> width="90%" alt="" /></a>
<br> 
                        <br>
                            <?php
                        }
                            }
                             } 
                                ?>
                            
                    

                            

                        </div>
                        
               

                    </div>
                    <br>
                     <div class="row">

                <!-- Blog Sidebar Widgets Column -->

                 <div class="col-md-3">
                         &nbsp;&nbsp; <font class="timetextago"><span class="glyphicon glyphicon-time"> </span>&nbsp;<?php echo $FF->timeAgo($FriendFeedarray[$iiii]['date']);   ?></font> 
                            </div>
                                <div class="col-md-5 col-md-offset-4">
                        <a href=<?php echo "http://vk.com/".$FriendFeedarray[$iiii]['screen']; ?> target="_blank"><font class="groupslink">Открыть группу  <?php echo iconv_substr($FriendFeedarray[$iiii]['groupname'], 0, 10, 'UTF-8')."...";  ?>&nbsp;<span class="glyphicon glyphicon-share-alt"> </span></font></a>
                            </div>
                            
                               

                        </div>                        
                                
                  
               </td>
               </tr>  
                 </table> 
     

<?php
}

$memcache_obj->set($sessionid."offset", 4, false, 1200);

$urlFeedupdateold = "http://192.168.1.141/index.php?old=".$_GET['back'];
$urlFeedupdateoldcache = "http://192.168.1.141/index.php?oldcache=".$_GET['back'];

?>



</div>
 
 <font ic-src=<?php echo $urlFeedupdateoldcache; ?> ic-poll="10s">Более старые записи</font>
  <div ic-src=<?php echo $urlFeedupdateold; ?> ic-trigger-on="scrolled-into-view" ic-indicator="mars">
   
  </div>


     


</div>

 <div class="col-md-3">
   
    <div class="row">

        <div class="col-md-12 leftprofile disabled"> 
       <?php 
      $friendid = [];
      $frlist[] = $memcache_obj->get($sessionid."friends");
      //var_dump($frlist);
    //  echo $_GET['id']."\n";
    //  echo $sessionid;
     // echo $_SESSION['fullname'];
      session_start();
       if($_GET['back'] == $sessionid)
            {
                $friendid['0']['last_name'] = "";
                $friendid['0']['first_name'] = $_SESSION['fullname'];
                $friendid['0']['photo_medium'] = $_SESSION['img'];
               // var_dump($friendid);
               // break;
            }
            else
            {

                 for ($fr=0; $fr < count($frlist['0']['0']); $fr++) 
                    { 
                        if($_GET['back'] == $frlist['0']['0'][$fr]['uid'])
                        {
                         $friendid[] = $frlist['0']['0'][$fr];
                            break;
                        }

                     }
            }
         
       ?>

      &nbsp;&nbsp;<h5>Профиль</h5>




       
       <div class="row">     
              <div class="media">
                <a class="pull-left" href=<?php echo $urlMyProfile; ?>>
                  
              <img class="media-object" src=<?php echo $_SESSION['img']; ?> width="80px" heigth="60px">
               
                <div class="media-body">
                  <a href=<?php echo $urlMyProfile; ?> class="profilelink"><h6><strong>&nbsp;<b><?php echo $_SESSION['fullname']; ?>&nbsp;</b></strong></h6></a>
                   
                </div>

            </div>

        </div>

     <br><br>
    
    
          
</div>

    </div>





<?php    session_write_close(); ?>
 </div>
<br>
  


 <hr>

        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <br>
                    <p>FriendFeed</p>

                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
        </footer>


</div>
</div>
<?php









}



if(!empty($_GET['news']))
{



//echo "ok";



  $FF = new FriendFeed();
  $FriendFeedarray = $memcache_obj->get($sessionid.$sessionid);
  $NewmessageCount = $memcache_obj->get($sessionid."countnewmessage");
  session_write_close();
  if(!empty($FriendFeedarray))
  {

   // 
   //var_dump($FriendFeedarray);
   // exit;
        
        
//echo count($FriendFeedarray);
?>

<?php
 for ($iiii=0; $iiii < count($FriendFeedarray); $iiii++) 
    {       
         
        // код который подсвечивает новые сообщения в ленте пользователя


        if(!empty($NewmessageCount))
        {
            if($iiii < $NewmessageCount)
            {
                ?>
                                     <table class="table table-bordered row-fluid newfeed">
                                        <tr>
                                         <td >
                                

                                 <div class="media">
                                <a class="pull-left" href=<?php echo "http://vk.com/".$FriendFeedarray[$iiii]['screen']; ?> target="_blank">
                                  
                                <img class="media-object" src= <?php echo $FriendFeedarray[$iiii]['groupphoto'];   ?>>
                                </a>
                                <div class="media-body">
                                 &nbsp;<a href=<?php echo "http://vk.com/".$FriendFeedarray[$iiii]['screen']; ?> target="_blank"><strong> <?php echo $FriendFeedarray[$iiii]['groupname'];   ?></strong></a>
                                   <?php  
                                        if(!empty($FriendFeedarray[$iiii]['text']))
                                        {
                                            ?>
                                           
                                           <?php echo " "; ?> &nbsp; &nbsp; <div class="cutstring" data-display="none" data-max-length="200" data-show-text="Показать полностью.." data-hide-text="Свернуть..">                  
                                       <?php echo " &nbsp;".$FriendFeedarray[$iiii]['text']; ?></div>
                                        
                                            <?php
                                    
                                        }
                                   ?>

                                        <?php
                                         if(!empty($FriendFeedarray[$iiii]['photo']))
                                        {

                                         ?>
                                            <br>
                                            <?php
                                         for ($ii=0; $ii < count($FriendFeedarray[$iiii]['photo']); $ii++) 
                                            { 
                                                 if($FriendFeedarray[$iiii]['photo'][$ii] != "")
                                            {
                                            ?>
                                    
                                           <a class="fancybox-effects-a" href=<?php echo $FriendFeedarray[$iiii]['photo'][$ii];   ?> data-fancybox-group="gallery" title=""><img src=<?php echo $FriendFeedarray[$iiii]['photo'][$ii];   ?> width="90%" alt="" /></a>
                                        <br>
                                            <?php
                                        }
                                            }
                                             } 
                                                ?>
                                            
                                    

                                            

                                        </div>
                                        
                               

                                    </div>
                                    <br>
                                     <div class="row">

                                <!-- Blog Sidebar Widgets Column -->

                                 <div class="col-md-3">
                                         &nbsp;&nbsp; <font class="timetextago"><span class="glyphicon glyphicon-time"> </span>&nbsp;<?php echo $FF->timeAgo($FriendFeedarray[$iiii]['date']);   ?></font> 
                                            </div>
                                                <div class="col-md-5 col-md-offset-4">
                                        <a href=<?php echo "http://vk.com/".$FriendFeedarray[$iiii]['screen']; ?> target="_blank"><font class="groupslink">Открыть группу  <?php echo iconv_substr($FriendFeedarray[$iiii]['groupname'], 0, 10, 'UTF-8')."...";  ?>&nbsp;<span class="glyphicon glyphicon-share-alt"> </span></font></a>
                                            </div>
                                            
                                               

                                        </div>
                                              
                                  
                                 
                                  
                               </td>
                               </tr>  
                                 </table>  

                 <?php                
            }
            else
            {
                ?>
                                     <table class="table table-bordered row-fluid leftprofile1">
                                        <tr>
                                        <td >
                                

                                 <div class="media">
                                <a class="pull-left" href=<?php echo "http://vk.com/".$FriendFeedarray[$iiii]['screen']; ?> target="_blank">
                                  
                                <img class="media-object" src= <?php echo $FriendFeedarray[$iiii]['groupphoto'];   ?>>
                                </a>
                                <div class="media-body">
                                 &nbsp;<a href=<?php echo "http://vk.com/".$FriendFeedarray[$iiii]['screen']; ?> target="_blank"><strong> <?php echo $FriendFeedarray[$iiii]['groupname'];   ?></strong></a>
                                   <?php  
                                        if(!empty($FriendFeedarray[$iiii]['text']))
                                        {
                                            ?>
                                           
                                           <?php echo " "; ?> &nbsp; &nbsp; <div class="cutstring" data-display="none" data-max-length="200" data-show-text="Показать полностью.." data-hide-text="Свернуть..">                  
                                       <?php echo " &nbsp;".$FriendFeedarray[$iiii]['text']; ?></div>
                                        
                                            <?php
                                    
                                        }
                                   ?>

                                        <?php
                                         if(!empty($FriendFeedarray[$iiii]['photo']))
                                        {

                                         ?>
                                            <br>
                                            <?php
                                         for ($ii=0; $ii < count($FriendFeedarray[$iiii]['photo']); $ii++) 
                                            { 
                                                 if($FriendFeedarray[$iiii]['photo'][$ii] != "")
                                            {
                                            ?>
                                    
                                            <a class="fancybox-effects-a" href=<?php echo $FriendFeedarray[$iiii]['photo'][$ii];   ?> data-fancybox-group="gallery" title=""><img src=<?php echo $FriendFeedarray[$iiii]['photo'][$ii];   ?> width="90%" alt="" /></a>
                                        <br>
                                            <?php
                                        }
                                            }
                                             } 
                                                ?>
                                            
                                    

                                            

                                        </div>
                                        
                               

                                    </div>
                                    <br>
                                     <div class="row">

                                <!-- Blog Sidebar Widgets Column -->

                                 <div class="col-md-3">
                                         &nbsp;&nbsp; <font class="timetextago"><span class="glyphicon glyphicon-time"> </span>&nbsp;<?php echo $FF->timeAgo($FriendFeedarray[$iiii]['date']);   ?></font> 
                                            </div>
                                                <div class="col-md-5 col-md-offset-4">
                                        <a href=<?php echo "http://vk.com/".$FriendFeedarray[$iiii]['screen']; ?> target="_blank"><font class="groupslink">Открыть группу  <?php echo iconv_substr($FriendFeedarray[$iiii]['groupname'], 0, 10, 'UTF-8')."...";  ?>&nbsp;<span class="glyphicon glyphicon-share-alt"> </span></font></a>
                                            </div>
                                            
                                               

                                        </div>
                                              
                                  
                                 
                                  
                               </td>
                               </tr>  
                                 </table> 
                                 <?php
            }
        }
        else
        {



        ?>
        <table class="table table-bordered row-fluid leftprofile1">
        <tr>
            <td >
                

                 <div class="media">
                <a class="pull-left" href=<?php echo "http://vk.com/".$FriendFeedarray[$iiii]['screen']; ?> target="_blank">
                  
                <img class="media-object" src= <?php echo $FriendFeedarray[$iiii]['groupphoto'];   ?>>
                </a>
                <div class="media-body">
                 &nbsp;<a href=<?php echo "http://vk.com/".$FriendFeedarray[$iiii]['screen']; ?> target="_blank"><strong> <?php echo $FriendFeedarray[$iiii]['groupname'];   ?></strong></a>
                   <?php  
                        if(!empty($FriendFeedarray[$iiii]['text']))
                        {
                            ?>
                           
                           <?php echo " "; ?> &nbsp; &nbsp; <div class="cutstring" data-display="none" data-max-length="200" data-show-text="Показать полностью.." data-hide-text="Свернуть..">                  
                       <?php echo " &nbsp;".$FriendFeedarray[$iiii]['text']; ?></div>
                        
                            <?php
                    
                        }
                   ?>

                        <?php
                         if(!empty($FriendFeedarray[$iiii]['photo']))
                        {

                         ?>
                            <br>
                            <?php
                         for ($ii=0; $ii < count($FriendFeedarray[$iiii]['photo']); $ii++) 
                            { 
                 
                            ?>
                    
                           <a class="fancybox-effects-a" href=<?php echo $FriendFeedarray[$iiii]['photo'][$ii];   ?> data-fancybox-group="gallery" title=""><img src=<?php echo $FriendFeedarray[$iiii]['photo'][$ii];   ?> width="90%" alt="" /></a>
                        <br>
                            <?php
                            }
                             } 
                                ?>
                            
                    

                            

                        </div>
                        
               

                    </div>
                    <br>
                     <div class="row">

                <!-- Blog Sidebar Widgets Column -->

                 <div class="col-md-3">
                         &nbsp;&nbsp; <font class="timetextago"><span class="glyphicon glyphicon-time"> </span>&nbsp;<?php echo $FF->timeAgo($FriendFeedarray[$iiii]['date']);   ?></font> 
                            </div>
                                <div class="col-md-5 col-md-offset-4">
                        <a href=<?php echo "http://vk.com/".$FriendFeedarray[$iiii]['screen']; ?> target="_blank"><font class="groupslink">Открыть группу  <?php echo iconv_substr($FriendFeedarray[$iiii]['groupname'], 0, 10, 'UTF-8')."...";  ?>&nbsp;<span class="glyphicon glyphicon-share-alt"> </span></font></a>
                            </div>
                            
                               

                        </div>
                              
                  
                 
                  
               </td>
               </tr>  
                 </table> 

     

<?php
}
//echo $iiii;
}


$memcache_obj->set($sessionid, $FriendFeedarray, false, 86400);
  }
  else
  {
    $FriendFeedarray = $memcache_obj->get($sessionid);

//var_dump($FriendFeedarray);
        for ($iiii=0; $iiii < count($FriendFeedarray); $iiii++) 
                { 
      
                
                            ?>
                         <table class="table table-bordered row-fluid leftprofile1">
        <tr>
            <td >
                

                 <div class="media">
                <a class="pull-left" href="#">
                  
                <img class="media-object" src= <?php echo $FriendFeedarray[$iiii]['groupphoto'];   ?>>
                </a>
                <div class="media-body">
                 <strong>&nbsp; <?php echo $FriendFeedarray[$iiii]['groupname'];   ?></strong>
                   <?php  
                        if(!empty($FriendFeedarray[$iiii]['text']))
                        {
                            ?>
                           
                           <?php echo " "; ?> &nbsp; &nbsp; <div class="cutstring" data-display="none" data-max-length="200" data-show-text="Показать полностью.." data-hide-text="Свернуть..">                  
                        &nbsp;<?php echo link_it($FriendFeedarray[$iiii]['text']); ?></div>
                        
                            <?php
                    
                        }
                   ?>

                        <?php
                         if(!empty($FriendFeedarray[$iiii]['photo']))
                        {

                         ?>
                            <br>
                            <?php
                         for ($ii=0; $ii < count($FriendFeedarray[$iiii]['photo']); $ii++) 
                            { 
                 
                            ?>
                    
                          <a class="fancybox-effects-a" href=<?php echo $FriendFeedarray[$iiii]['photo'][$ii];   ?> data-fancybox-group="gallery" title=""><img src=<?php echo $FriendFeedarray[$iiii]['photo'][$ii];   ?> width="90%" alt="" /></a>
                        <br>
                            <?php
                            }
                             } 
                                ?>
                            
                    

                            

                        </div>
                        
               

                    </div>
                    <br>
                     <div class="row">

                <!-- Blog Sidebar Widgets Column -->

                 <div class="col-md-3">
                         &nbsp;&nbsp; <font class="timetextago"><?php echo $FF->timeAgo($FriendFeedarray[$iiii]['date']);   ?></font> 
                            </div>
                                <div class="col-md-5 col-md-offset-4">
                        <a href="#"><font class="groupslink">Открыть группу  <?php echo iconv_substr($FriendFeedarray[$iiii]['groupname'], 0, 10, 'UTF-8')."...";  ?>&nbsp;<span class="glyphicon glyphicon-share-alt"> </span></font></a>
                            </div>                        
                         </div>                    
                                  
                  
                           </td>
                           </tr>  
                             </table>                                         

                         <?php
                        }

                    }  


              $memcache_obj->set($sessionid."countnewmessage", 0, false, 300);                    
                      ?>
 <script>
$(function() {
    $('.cutstring').cutstring();
});
</script>

<?php
//session_write_close();
exit;
}





if(!empty($_GET['groups']))
{


if($memcache_obj->get($sessionid."countnewmessage") == 0)
{
    echo "0";
    
    $memcache_obj->set($sessionid."countnewmessage", 1, false, 300);
   
    exit;
}
else
{
     if(rand(0,12) == 4)
     {
             // задача №1 по оптимизации это првоерять дату до выгребания переменных

        $GroupIds[] = $vk->getGroupsforWall($_GET['groups']);
for($mm=0;$mm<count($GroupIds['0']);$mm++)
{
    if($GroupIdsStr == "") $GroupIdsStr = $GroupIds['0'][$mm];
    else $GroupIdsStr = $GroupIdsStr.",".$GroupIds['0'][$mm];
}
//echo $GroupIdsStr;
$Groupinfo[] = $vk->getGroupsById($GroupIdsStr);

// целое число запросов к группам 

$CountDivGroups = floor(count($Groupinfo['0'])/24);

// остаток от деления на 24, максимальное число запросов в группам

$CounterModGroups = count($Groupinfo['0']) % 24;
$CounterWallget = 0;
$CounterWallget24 = 24;

while($ccc<$CountDivGroups)
{

    $codeStr = 'var a=API.groups.get({"user_id":"'.$_GET['groups'].'"}); var b=a; var d='.$CounterWallget.'; var v='.$CounterWallget24.';
        var c = [];
        while (d < v)
        {
         c.push(API.wall.get({"owner_id":-b[d],"count":"4"}));
         d = d+1; 
        };
        return c;';       

    $viewMyFeed[] = $vk->getExecuteFeedFriends($codeStr);

    for($cc=0; $cc<count($viewMyFeed['0']); $cc++)
    {
        
        for($jj = 1; $jj<5; $jj++)
        { 
               for($vv=0; $vv<count($Groupinfo['0']);$vv++)
               {

                    if("-".$Groupinfo['0'][$vv]['gid'] == $viewMyFeed['0'][$cc][$jj]['from_id']) 
                    {   
                        $gidscreen = $Groupinfo['0'][$vv]['screen_name'];     
                        $gidd = $Groupinfo['0'][$vv]['gid'];              
                        $Groupphoto = $Groupinfo['0'][$vv]['photo'];
                        $Groupname = $Groupinfo['0'][$vv]['name'];                                           
                        break;
                    }
               }
             for ($vv=0; $vv < count($viewMyFeed['0'][$cc][$jj]['attachments']); $vv++) 
                { 
                    $Feedphotoarray[] = $viewMyFeed['0'][$cc][$jj]['attachments'][$vv]['photo']['src_big'];
                }
                if(checkdatearr($viewMyFeed['0'][$cc][$jj]['date']) == 1)
                {
                    $FriendFeedarray[] = array("groupname" => $Groupname, "groupphoto" => $Groupphoto, "text" => $viewMyFeed['0'][$cc][$jj]['text'], "photo" => $Feedphotoarray, "date" => $viewMyFeed['0'][$cc][$jj]['date'],"gid" => $gidd, "screen" => $gidscreen); 
                }
              
          unset($Feedphotoarray); 
        } 
    

    }
    unset($viewMyFeed);

    $CounterWallget = $CounterWallget + 24;
    $CounterWallget24 = $CounterWallget24 + 24;
    $ccc++;
}

if($CounterModGroups != 0)
{
    $CounterWallget = 0;
    $CounterWallget24 = 24;
    unset($viewMyFeed);

    $CounterMod = $ccc*24;
    $CounterModGroups = $CounterMod + $CounterModGroups;

    $codeStr = 'var a=API.groups.get({"user_id":"'.$_GET['groups'].'"}); var b=a; var d='.$CounterMod.'; var v='.$CounterModGroups.';
        var c = [];
        while (d < v)
        {
         c.push(API.wall.get({"owner_id":-b[d],"count":"4"}));
         d = d+1; 
        };
        return c;';       

    $viewMyFeed[] = $vk->getExecuteFeedFriends($codeStr);
    //var_dump($viewMyFeed);
    for($cc=0; $cc<count($viewMyFeed['0']); $cc++)
    {
        
        for($jj = 1; $jj<5; $jj++)
        {   
               for($vv=0; $vv<count($Groupinfo['0']);$vv++)
               {

                    if("-".$Groupinfo['0'][$vv]['gid'] == $viewMyFeed['0'][$cc][$jj]['from_id']) 
                    {  
                         $gidscreen = $Groupinfo['0'][$vv]['screen_name'];   
                        $gidd = $Groupinfo['0'][$vv]['gid'];             
                        $Groupphoto = $Groupinfo['0'][$vv]['photo'];
                        $Groupname = $Groupinfo['0'][$vv]['name']; 
                        break;
                    }
               }
            for ($vv=0; $vv < count($viewMyFeed['0'][$cc][$jj]['attachments']); $vv++) 
                { 
                    $Feedphotoarray[] = $viewMyFeed['0'][$cc][$jj]['attachments'][$vv]['photo']['src_big'];
                }
                  if(checkdatearr($viewMyFeed['0'][$cc][$jj]['date']) == 1)
                {
                    $FriendFeedarray[] = array("groupname" => $Groupname, "groupphoto" => $Groupphoto, "text" => $viewMyFeed['0'][$cc][$jj]['text'], "photo" => $Feedphotoarray, "date" => $viewMyFeed['0'][$cc][$jj]['date'],"gid" => $gidd, "screen" => $gidscreen); 
                }
               //$FriendFeedarray[] = array("groupname" => $Groupname, "groupphoto" => $Groupphoto, "text" => $viewMyFeed['0'][$cc][$jj]['text'], "photo" => $Feedphotoarray, "date" => $viewMyFeed['0'][$cc][$jj]['date'],"gid" => $gidd,"screen" => $gidscreen);
          unset($Feedphotoarray); 
        } 
    

    }

}

// Этот блок нужен для выполнения многих фоновых задач кеширования, обновление счетчика новых постов в ленте, кеширование друзей и кеширвоание ленты, 
//склейка и обрезование кеширвоанной ленты
  
      
   if(empty($memcache_obj->get($sessionid."friends")))
   {
   
        $listFriends[] = $vk->getFriends();
        $memcache_obj->set($sessionid."friends", $listFriends, false, 1200);

    }
    else
    {
        $listFriends = $memcache_obj->get($sessionid."friends");
    }

// наш главный класс в котором пока есть методы только для сортировки и работы с датами
$FF = new FriendFeed();
//$oldFeedarray = [];
$FriendFeednewarr = $FF->TimeFeedSort($FriendFeedarray);
//$FriendFeedarray = $FF->TimeFeedSort($FriendFeedarray);
$oldFeedarray = $memcache_obj->get($sessionid);
//$FriendFeedarray = $FF->Newsdiffarray($FriendFeedarray,$oldFeedarray);

$FriendFeedarray = array_udiff($FriendFeednewarr, $oldFeedarray, "FeedDiffarray");
//sleep(2);
echo count($FriendFeedarray);
$memcache_obj->set($sessionid."countnewmessage", count($FriendFeedarray), false, 300);


$memcache_obj->set($sessionid.$sessionid, $FriendFeednewarr, false, 86400);

if($sessionid == $_GET['groups'])
{
    $memcache_obj->set($sessionid."me", $FriendFeedarray, false, 86400);
}


// Для модуля обработки статистики решил оставить этот код и только, когда приложение не работает с лентой, хотя если на главной будет выводиться моя лена, то уберу
// удаление из базы в случае если ты выписался из сообществ, пока не реализовано
 /*
    $viewMyGroups[] = $vk->getGroups($sessionid);  //список групп
for ($i=1; $i <count($viewMyGroups['0']) ; $i++) 
    { 
        $myid = $sessionid;
        $gidGroup = $viewMyGroups['0'][$i]['gid'];
       if(empty(mysql_fetch_assoc(mysql_query("SELECT id FROM Cachegroups WHERE id_user='$myid' and id_group='$gidGroup'"))))
        {
         $gidGroup = $viewMyGroups['0'][$i]['gid'];       
        $nameGroup = strip_tags(str_replace("'","",$viewMyGroups['0'][$i]['name']));       
        $descriptionGroup = strip_tags(str_replace("'","",$viewMyGroups['0'][$i]['description']));      
        $screen_nameGroup = $viewMyGroups['0'][$i]['screen_name'];
        $activityGroup = $viewMyGroups['0'][$i]['is_closed'];
        $members_countGroup = $viewMyGroups['0'][$i]['members_count'];

// ошибка, добавляются группы повторно или по несколько штук, проблема не решена
        mysql_query("INSERT INTO Cachegroups VALUES (null, '$_SESSION[id]', '$nameGroup', '$descriptionGroup', '$screen_nameGroup', '$activityGroup', '$members_countGroup','$gidGroup')") or die(mysql_error());       
        }

    }
*/






//$memcache_obj->set('our_var');

//var_dump($FriendFeedarray);
//$FriendFeedarray[] = $oldFeedarray;
//var_dump($FriendFeedarray);
//$memcache_obj->set('our_var1', $FriendFeednewarr, false, 1200);

     }  
     else
     {
        // ошибка если новых записей 1, будет вечно 1 менятся на 0, нужно потом исправить
        if($memcache_obj->get($sessionid."countnewmessage") == 1)
        {
            echo "0";
            $memcache_obj->set($sessionid."countnewmessage",0,false,300);
        }
        else
        {
           // sleep(2);
            echo $memcache_obj->get($sessionid."countnewmessage");
        }
     }     


}


   
exit;
}

if(isset($_GET['oldcache']))
{
 
  
                                                $offset = $memcache_obj->get($sessionid."offset");
                                   // $FriendFeedarray = $memcache_obj->get($sessionid.$sessionid);
                                    //$FriendFeedarray1 = $memcache_obj->get($sessionid);
                                            $myid = $memcache_obj->get($sessionid."idpage");
                                               // echo $myid."\n";
                                               // exit;
                                   // if(empty($FriendFeedarray)) $FriendFeedarray = $FriendFeedarray1;
                                 //  echo "bitch".$offset."bitch";
                                   //echo "nyid".$myid."fucks";
                                            // дебагерские переменные
                                           $memcache_obj->set($sessionid."debid",$myid,false,7200);

                                                                $GroupIds[] = $vk->getGroupsforWall($myid);

                                                                  //echo "Group IDS ".count($GroupIds)."\n";
                                                                for($mm=0;$mm<count($GroupIds['0']);$mm++)
                                                                {
                                                             if($GroupIdsStr == "") $GroupIdsStr = $GroupIds['0'][$mm];
                                                                    else $GroupIdsStr = $GroupIdsStr.",".$GroupIds['0'][$mm];
                                                                }
                                                    //echo $GroupIdsStr;
                                                            $Groupinfo[] = $vk->getGroupsById($GroupIdsStr);
                                                     
                                                         // дебагерская переменаня
                                                          $memcache_obj->set($sessionid."debgroup",$Groupinfo,false,7200);
                                                    // целое число запросов к группам 
 
                                                    $CountDivGroups = floor(count($Groupinfo['0'])/24);

                                                    // остаток от деления на 24, максимальное число запросов в группам

                                                    $CounterModGroups = count($Groupinfo['0']) % 24;
                                                    $CounterWallget = 0;
                                                    $CounterWallget24 = 24;

                                                    while($ccc<$CountDivGroups)
                                                    {

                                                        $codeStr = 'var a=API.groups.get({"user_id":"'.$myid.'"}); var b=a; var d='.$CounterWallget.'; var v='.$CounterWallget24.';
                                                            var c = [];
                                                            while (d < v)
                                                            {
                                                             c.push(API.wall.get({"owner_id":-b[d],"count":"4","offset":"'.$offset.'"}));
                                                             d = d+1; 
                                                            };
                                                            return c;';       

                                                        $viewMyFeed[] = $vk->getExecuteFeedFriends($codeStr);

                                                        //var_dump($viewMyFeed);
                                                         // var_dump($viewMyFeed);
                                                        for($cc=0; $cc<count($viewMyFeed['0']); $cc++)
                                                        {
                                                            
                                                            for($jj = 1; $jj<5; $jj++)
                                                            {    
                                                                   for($vv=0; $vv<count($Groupinfo['0']);$vv++)
                                                                   {

                                                                        if("-".$Groupinfo['0'][$vv]['gid'] == $viewMyFeed['0'][$cc][$jj]['from_id']) 
                                                                        {
                                                                            $gidscreen = $Groupinfo['0'][$vv]['screen_name'];
                                                                            $gidd = $Groupinfo['0'][$vv]['gid'];
                                                                            $Groupphoto = $Groupinfo['0'][$vv]['photo'];
                                                                            $Groupname = $Groupinfo['0'][$vv]['name'];                                           
                                                                            break;
                                                                        }
                                                                   }
                                                                    for ($vv=0; $vv < count($viewMyFeed['0'][$cc][$jj]['attachments']); $vv++) 
                                                                    { 
                                                                        $Feedphotoarray[] = $viewMyFeed['0'][$cc][$jj]['attachments'][$vv]['photo']['src_big'];
                                                                    }
                                                                     if(checkdatearr($viewMyFeed['0'][$cc][$jj]['date']) == 1)
                                                                   {
                                                                        $FriendFeedarray[] = array("groupname" => $Groupname, "groupphoto" => $Groupphoto, "text" => $viewMyFeed['0'][$cc][$jj]['text'], "photo" => $Feedphotoarray, "date" => $viewMyFeed['0'][$cc][$jj]['date'],"gid" => $gidd, "screen" => $gidscreen); 
                                                                    }
                                                                //   $FriendFeedarray[] = array("groupname" => $Groupname, "groupphoto" => $Groupphoto, "text" => $viewMyFeed['0'][$cc][$jj]['text'], "photo" => $Feedphotoarray, "date" => $viewMyFeed['0'][$cc][$jj]['date'],"gid" => $gidd, "screen" => $gidscreen);
                                                              unset($Feedphotoarray);  
                                                            //  echo $viewMyFeed['0'][$cc][$jj]['date']."\n";   
                                                            } 
                                                        

                                                        }
                                                       // echo "viewmyfeed1 ".count($viewMyFeed)."\n";
                                                        unset($viewMyFeed);

                                                        $CounterWallget = $CounterWallget + 24;
                                                        $CounterWallget24 = $CounterWallget24 + 24;
                                                        $ccc++;
                                                    }

                                                    if($CounterModGroups != 0)
                                                    {
                                                        $CounterWallget = 0;
                                                        $CounterWallget24 = 24;
                                                        unset($viewMyFeed);

                                                        $CounterMod = $ccc*24;
                                                        $CounterModGroups = $CounterMod + $CounterModGroups;

                                                        $codeStr = 'var a=API.groups.get({"user_id":"'.$myid.'"}); var b=a; var d='.$CounterMod.'; var v='.$CounterModGroups.';
                                                            var c = [];
                                                            while (d < v)
                                                            {
                                                             c.push(API.wall.get({"owner_id":-b[d],"count":"4","offset":"'.$offset.'"}));
                                                             d = d+1; 
                                                            };
                                                            return c;';       

                                                        $viewMyFeed[] = $vk->getExecuteFeedFriends($codeStr);
                                                        //  var_dump($viewMyFeed);
                                                        for($cc=0; $cc<count($viewMyFeed['0']); $cc++)
                                                        {
                                                            
                                                            for($jj = 1; $jj<5; $jj++)
                                                            {    
                                                                   for($vv=0; $vv<count($Groupinfo['0']);$vv++)
                                                                   {

                                                                        if("-".$Groupinfo['0'][$vv]['gid'] == $viewMyFeed['0'][$cc][$jj]['from_id']) 
                                                                        {
                                                                            $gidscreen = $Groupinfo['0'][$vv]['screen_name'];
                                                                            $gidd = $Groupinfo['0'][$vv]['gid'];
                                                                            $Groupphoto = $Groupinfo['0'][$vv]['photo'];
                                                                            $Groupname = $Groupinfo['0'][$vv]['name']; 
                                                                            break;
                                                                        }
                                                                   }
                                                                    for ($vv=0; $vv < count($viewMyFeed['0'][$cc][$jj]['attachments']); $vv++) 
                                                                    { 
                                                                        $Feedphotoarray[] = $viewMyFeed['0'][$cc][$jj]['attachments'][$vv]['photo']['src_big'];
                                                                    }
                                                                     if(checkdatearr($viewMyFeed['0'][$cc][$jj]['date']) == 1)
                                                                    {
                                                                        $FriendFeedarray[] = array("groupname" => $Groupname, "groupphoto" => $Groupphoto, "text" => $viewMyFeed['0'][$cc][$jj]['text'], "photo" => $Feedphotoarray, "date" => $viewMyFeed['0'][$cc][$jj]['date'],"gid" => $gidd, "screen" => $gidscreen); 
                                                                    }
                                                                //   $FriendFeedarray[] = array("groupname" => $Groupname, "groupphoto" => $Groupphoto, "text" => $viewMyFeed['0'][$cc][$jj]['text'], "photo" => $Feedphotoarray, "date" => $viewMyFeed['0'][$cc][$jj]['date'],"gid" => $gidd,"screen" => $gidscreen);
                                                                   unset($Feedphotoarray);
                                                                    // echo $viewMyFeed['0'][$cc][$jj]['date']."\n";  
                                                            } 
                                                   

                                                    //var_dump($FriendFeedarray);
                                                  //  $memcache_obj->set($sessionid., $FriendFeedarray, false, 86400);
                                                    //$memcache_obj->set($sessionid.$sessionid, $FriendFeedarray, false, 86400);
                                                        }
                                                    } 
                                                     $FF = new FriendFeed();
                                                   //  var_dump($FriendFeedarray);
                                                    $FriendFeedarray = $FF->TimeFeedSort($FriendFeedarray); 

                                                   // var_dump($FriendFeedarray)."\n"; 
                                              
                                                       $memcache_obj->set($sessionid."oldentriescache", $FriendFeedarray, false, 86400);
                                                     
                                              exit;            
                                          

}

if(isset($_GET['old']))
{   

   // echo $memcache_obj->get($sessionid."offset");
    //exit;
    while(true)
    {
         $FriendFeedarray = $memcache_obj->get($sessionid."oldentriescache");  
         if(!empty($FriendFeedarray)) break;
    }
     //$myid = $memcache_obj->get($sessionid."idpage");
      // echo $myid."\n";
      $FF = new FriendFeed();
                  
                 
                        for ($iiii=0; $iiii < count($FriendFeedarray); $iiii++) 
                            { 
                  
                            
                    ?>
                     <table class="table table-bordered row-fluid leftprofile1">
                    <tr>
                        <td >
                            

                             <div class="media">
                            <a class="pull-left" href=<?php echo "http://vk.com/".$FriendFeedarray[$iiii]['screen']; ?> target="_blank">
                              
                            <img class="media-object" src= <?php echo $FriendFeedarray[$iiii]['groupphoto'];   ?>>
                            </a>
                            <div class="media-body">
                             &nbsp;<a href=<?php echo "http://vk.com/".$FriendFeedarray[$iiii]['screen']; ?> target="_blank"><strong> <?php echo $FriendFeedarray[$iiii]['groupname'];   ?></strong></a>
                               <?php  
                                    if(!empty($FriendFeedarray[$iiii]['text']))
                                    {
                                        ?>
                                       
                                       <?php echo " "; ?> &nbsp; &nbsp; <div class="cutstring" data-display="none" data-max-length="200" data-show-text="Показать полностью.." data-hide-text="Свернуть..">                  
                                   <?php echo " &nbsp;".$FriendFeedarray[$iiii]['text']; ?></div>
                                    
                                        <?php
                                
                                    }
                               ?>

                                    <?php
                                     if(!empty($FriendFeedarray[$iiii]['photo']))
                                    {

                                     ?>
                                        <br>
                                        <?php
                                     for ($ii=0; $ii < count($FriendFeedarray[$iiii]['photo']); $ii++) 
                                        { 
                                            if($FriendFeedarray[$iiii]['photo'][$ii] != "")
                                            {
                                        ?>
                                
                                        <img src=<?php echo $FriendFeedarray[$iiii]['photo'][$ii];   ?> width="98%"> <br> 
                                    <br>
                                        <?php
                                            }
                                        }
                                         } 
                                            ?>                                    
                                                                      

                                    </div>
                                    
                           

                                </div>
                                <br>
                                 <div class="row">

                            <!-- Blog Sidebar Widgets Column -->

                             <div class="col-md-3">
                                     &nbsp;&nbsp; <font class="timetextago"><span class="glyphicon glyphicon-time"> </span>&nbsp;<?php echo $FF->timeAgo($FriendFeedarray[$iiii]['date']);   ?></font> 
                                        </div>
                                            <div class="col-md-5 col-md-offset-4">
                                    <a href=<?php echo "http://vk.com/".$FriendFeedarray[$iiii]['screen']; ?> target="_blank"><font class="groupslink">Открыть группу  <?php echo iconv_substr($FriendFeedarray[$iiii]['groupname'], 0, 10, 'UTF-8')."...";  ?>&nbsp;<span class="glyphicon glyphicon-share-alt"> </span></font></a>
                                        </div>
                                        
                                           

                                    </div>                        
                                            
                              
                           </td>
                           </tr>  
                             </table> 
                 

            <?php
            
            }
             $myid = $memcache_obj->get($sessionid."idpage");
             $offset = $memcache_obj->get($sessionid."offset");
             $offset = $offset+4; 
                                   
             $memcache_obj->set($sessionid."offset", $offset, false, 86400);
             $memcache_obj->set($sessionid."idpage", $myid, false, 86400);
            
             $urlFeedupdateold = "http://192.168.1.141/index.php?old=".$myid;
?>

  <div ic-src=<?php echo $urlFeedupdateold; ?> ic-trigger-on="scrolled-into-view" ic-indicator="#mars">
   
  </div>
   <script>
$(function() {
    $('.cutstring').cutstring();
});
</script>
  <?php

exit;
}



if(!empty($_GET['id']))
{

    // кешируем список друзей пользователя 
  
    if(empty($memcache_obj->get($sessionid."friends")))
    {
        $listFriends[] = $vk->getFriends();
       
        $memcache_obj->set($sessionid."friends", $listFriends, false, 1200);
    }
    else
    {
        $listFriends = $memcache_obj->get($sessionid."friends");
    }
    
   

?>


 <?php 
 // определение текущей активной ленты 
      $friendid = [];
      $frlist[] = $memcache_obj->get($sessionid."friends");    
       if($_GET['id'] == $sessionid)
            {
                $friendid['0']['last_name'] = "";
                $friendid['0']['first_name'] = $_SESSION['fullname'];
                $friendid['0']['photo_medium'] = $_SESSION['img'];
               // var_dump($friendid);
               // break;
            }
            else
            {

                 for ($fr=0; $fr < count($frlist['0']['0']); $fr++) 
                    { 
                        if($_GET['id'] == $frlist['0']['0'][$fr]['uid'])
                        {
                         $friendid[] = $frlist['0']['0'][$fr];
                            break;
                        }

                     }
            }

    ?>

<div class="container">

        <div class="row">

                <!-- Blog Sidebar Widgets Column -->
            <div class="col-md-2 friendlistblock">
                 <div class="row">
                <div class="feedactiveprofile">
                 &nbsp;&nbsp;<h5>Активная лента</h5>
                
                <div class="media">
                <a class="pull-left" href="#">
                  
                <img class="media-object" src=<?php echo $friendid['0']['photo_medium']; ?> width="80px" heigth="60px">
                </a>
                <div class="media-body"><br>
                 
                   
                    </div>

                    </div>
                      <h6><strong>&nbsp;<?php echo $friendid['0']['first_name']." ".$friendid['0']['last_name']; ?>&nbsp;</strong></h6><br>
            </div>
            </div>

                <!-- Blog Categories Well -->
               
                    <h5>Ленты друзей</h5>
                   
                    <div class="row">
                    <div class="col-md-12">

                     <?php
                         
                     for ($i=0; $i <1 ; $i++) for ($j=0; $j < count($listFriends['0']) ; $j++) 
                        {

                        ?> <div class="row"><div class="friends"><a href=<?php echo "http://192.168.1.141/index.php?id=".$listFriends[$i][$j]['uid']; ?> class="friendsfont"> <strong><b><?php echo $listFriends[$i][$j]['first_name']." ".$listFriends[$i][$j]['last_name']; ?></b></strong></a><br><a href=<?php echo "http://192.168.1.141/index.php?id=".$listFriends[$i][$j]['uid']; ?>><img src=<?php echo $listFriends[$i][$j]['photo_medium']; ?>></a><br></div></div>
                        <?php
                         } 
                         ?>             
                    </div>
                </div>

            </div>
            <!-- Blog Entries Column -->



<?php

//$memcache_obj->set($sessionid."idpage", $listFriends, false, 1200);
$FriendFeedarray = $memcache_obj->get($sessionid.$sessionid);
$FriendFeedarray1 = $memcache_obj->get($sessionid);
//var_dump($FriendFeedarray);
//var_dump($FriendFeedarray1);

if(empty($memcache_obj->get($sessionid."idpage")))
{
   $memcache_obj->set($sessionid."idpage", $_GET['id'], false, 1200);

    // echo $_GET['id']."\n";
      //  echo $memcache_obj->get($sessionid."idpage");
   unset($FriendFeedarray);
   unset($FriendFeedarray1);
   if(empty($FriendFeedarray))
    {
    if(empty($FriendFeedarray1))
    {
        //unset($FriendFeedarray);
        $GroupIds[] = $vk->getGroupsforWall($_GET['id']);
for($mm=0;$mm<count($GroupIds['0']);$mm++)
{
    if($GroupIdsStr == "") $GroupIdsStr = $GroupIds['0'][$mm];
    else $GroupIdsStr = $GroupIdsStr.",".$GroupIds['0'][$mm];
}
//echo $GroupIdsStr;
$Groupinfo[] = $vk->getGroupsById($GroupIdsStr);

// целое число запросов к группам 

$CountDivGroups = floor(count($Groupinfo['0'])/24);

// остаток от деления на 24, максимальное число запросов в группам

$CounterModGroups = count($Groupinfo['0']) % 24;
$CounterWallget = 0;
$CounterWallget24 = 24;

while($ccc<$CountDivGroups)
{

    $codeStr = 'var a=API.groups.get({"user_id":"'.$_GET['id'].'"}); var b=a; var d='.$CounterWallget.'; var v='.$CounterWallget24.';
        var c = [];
        while (d < v)
        {
         c.push(API.wall.get({"owner_id":-b[d],"count":"4"}));
         d = d+1; 
        };
        return c;';       

    $viewMyFeed[] = $vk->getExecuteFeedFriends($codeStr);

     // var_dump($viewMyFeed);
    for($cc=0; $cc<count($viewMyFeed['0']); $cc++)
    {
        
        for($jj = 1; $jj<5; $jj++)
        {    
               for($vv=0; $vv<count($Groupinfo['0']);$vv++)
               {

                    if("-".$Groupinfo['0'][$vv]['gid'] == $viewMyFeed['0'][$cc][$jj]['from_id']) 
                    {
                        $gidscreen = $Groupinfo['0'][$vv]['screen_name'];
                        $gidd = $Groupinfo['0'][$vv]['gid'];
                        $Groupphoto = $Groupinfo['0'][$vv]['photo'];
                        $Groupname = $Groupinfo['0'][$vv]['name'];                                           
                        break;
                    }
               }
                for ($vv=0; $vv < count($viewMyFeed['0'][$cc][$jj]['attachments']); $vv++) 
                { 
                    $Feedphotoarray[] = $viewMyFeed['0'][$cc][$jj]['attachments'][$vv]['photo']['src_big'];
                }
                 if(checkdatearr($viewMyFeed['0'][$cc][$jj]['date']) == 1)
                {
                    $FriendFeedarray[] = array("groupname" => $Groupname, "groupphoto" => $Groupphoto, "text" => $viewMyFeed['0'][$cc][$jj]['text'], "photo" => $Feedphotoarray, "date" => $viewMyFeed['0'][$cc][$jj]['date'],"gid" => $gidd, "screen" => $gidscreen); 
                }
               //$FriendFeedarray[] = array("groupname" => $Groupname, "groupphoto" => $Groupphoto, "text" => $viewMyFeed['0'][$cc][$jj]['text'], "photo" => $Feedphotoarray, "date" => $viewMyFeed['0'][$cc][$jj]['date'],"gid" => $gidd,"screen" => $gidscreen);
          unset($Feedphotoarray);     
        } 
    

    }

    unset($viewMyFeed);

    $CounterWallget = $CounterWallget + 24;
    $CounterWallget24 = $CounterWallget24 + 24;
    $ccc++;
}

if($CounterModGroups != 0)
{
    $CounterWallget = 0;
    $CounterWallget24 = 24;
    unset($viewMyFeed);

    $CounterMod = $ccc*24;
    $CounterModGroups = $CounterMod + $CounterModGroups;

    $codeStr = 'var a=API.groups.get({"user_id":"'.$_GET['id'].'"}); var b=a; var d='.$CounterMod.'; var v='.$CounterModGroups.';
        var c = [];
        while (d < v)
        {
         c.push(API.wall.get({"owner_id":-b[d],"count":"4"}));
         d = d+1; 
        };
        return c;';       

    $viewMyFeed[] = $vk->getExecuteFeedFriends($codeStr);
    //  var_dump($viewMyFeed);
    for($cc=0; $cc<count($viewMyFeed['0']); $cc++)
    {
        
        for($jj = 1; $jj<5; $jj++)
        {    
               for($vv=0; $vv<count($Groupinfo['0']);$vv++)
               {

                    if("-".$Groupinfo['0'][$vv]['gid'] == $viewMyFeed['0'][$cc][$jj]['from_id']) 
                    {
                        $gidscreen = $Groupinfo['0'][$vv]['screen_name'];
                        $gidd = $Groupinfo['0'][$vv]['gid'];
                        $Groupphoto = $Groupinfo['0'][$vv]['photo'];
                        $Groupname = $Groupinfo['0'][$vv]['name']; 
                        break;
                    }
               }
                for ($vv=0; $vv < count($viewMyFeed['0'][$cc][$jj]['attachments']); $vv++) 
                { 
                    $Feedphotoarray[] = $viewMyFeed['0'][$cc][$jj]['attachments'][$vv]['photo']['src_big'];
                }
                 if(checkdatearr($viewMyFeed['0'][$cc][$jj]['date']) == 1)
                {
                    $FriendFeedarray[] = array("groupname" => $Groupname, "groupphoto" => $Groupphoto, "text" => $viewMyFeed['0'][$cc][$jj]['text'], "photo" => $Feedphotoarray, "date" => $viewMyFeed['0'][$cc][$jj]['date'],"gid" => $gidd, "screen" => $gidscreen); 
                }
               // $FriendFeedarray[] = array("groupname" => $Groupname, "groupphoto" => $Groupphoto, "text" => $viewMyFeed['0'][$cc][$jj]['text'], "photo" => $Feedphotoarray, "date" => $viewMyFeed['0'][$cc][$jj]['date'],"gid" => $gidd,"screen" => $gidscreen);
               unset($Feedphotoarray);
        } 
    

    }

}
//var_dump($viewMyFeed);
// наш главный класс в котором пока есть методы только для сортировки и работы с датами
$FF = new FriendFeed();
$FriendFeedarray = $FF->TimeFeedSort($FriendFeedarray);

$memcache_obj->set($sessionid, $FriendFeedarray, false, 86400);

    }
    else
    {
        $FF = new FriendFeed();
        $FriendFeedarray = $FriendFeedarray1;
    }
}

$FF = new FriendFeed();
//var_dump($FriendFeedarray);
//$FriendFeedarray = $FF->FeedArraySlayer($FriendFeedarray);

$memcache_obj->set($sessionid, $FriendFeedarray, false, 86400);

}
else
{
    if($_GET['id'] == $memcache_obj->get($sessionid."idpage"))
    {

       // echo "hui";
         // echo $_GET['id']."\n";
        //echo $memcache_obj->get($sessionid."idpage");
        // тут находится спорный код кеширования
      // unset($FriendFeedarray);
      // unset($FriendFeedarray1);
       /// exit;
            if(empty($FriendFeedarray))
             {
             if(empty($FriendFeedarray1))
            {
               // unset($FriendFeedarray);
                 $GroupIds[] = $vk->getGroupsforWall($_GET['id']);
                 for($mm=0;$mm<count($GroupIds['0']);$mm++)
                     {
                        if($GroupIdsStr == "") $GroupIdsStr = $GroupIds['0'][$mm];
                         else $GroupIdsStr = $GroupIdsStr.",".$GroupIds['0'][$mm];
                    }
//echo $GroupIdsStr;
                    $Groupinfo[] = $vk->getGroupsById($GroupIdsStr);

// целое число запросов к группам 

                        $CountDivGroups = floor(count($Groupinfo['0'])/24);

// остаток от деления на 24, максимальное число запросов в группам

                        $CounterModGroups = count($Groupinfo['0']) % 24;
                        $CounterWallget = 0;
                        $CounterWallget24 = 24;

                     while($ccc<$CountDivGroups)
                        {

                            $codeStr = 'var a=API.groups.get({"user_id":"'.$_GET['id'].'"}); var b=a; var d='.$CounterWallget.'; var v='.$CounterWallget24.';
                                var c = [];
                                while (d < v)
                                {
                                 c.push(API.wall.get({"owner_id":-b[d],"count":"4"}));
                                 d = d+1; 
                                };
                                return c;';       

                            $viewMyFeed[] = $vk->getExecuteFeedFriends($codeStr);

     // var_dump($viewMyFeed);
                            for($cc=0; $cc<count($viewMyFeed['0']); $cc++)
                            {
                                
                                for($jj = 1; $jj<5; $jj++)
                                {    
                                       for($vv=0; $vv<count($Groupinfo['0']);$vv++)
                                       {

                                            if("-".$Groupinfo['0'][$vv]['gid'] == $viewMyFeed['0'][$cc][$jj]['from_id']) 
                                            {
                                                $gidscreen = $Groupinfo['0'][$vv]['screen_name'];
                                                $gidd = $Groupinfo['0'][$vv]['gid'];
                                                $Groupphoto = $Groupinfo['0'][$vv]['photo'];
                                                $Groupname = $Groupinfo['0'][$vv]['name'];                                           
                                                break;
                                            }
                                       }
                                        for ($vv=0; $vv < count($viewMyFeed['0'][$cc][$jj]['attachments']); $vv++) 
                                        { 
                                            $Feedphotoarray[] = $viewMyFeed['0'][$cc][$jj]['attachments'][$vv]['photo']['src_big'];
                                        }
                                         if(checkdatearr($viewMyFeed['0'][$cc][$jj]['date']) == 1)
                                            {
                                                $FriendFeedarray[] = array("groupname" => $Groupname, "groupphoto" => $Groupphoto, "text" => $viewMyFeed['0'][$cc][$jj]['text'], "photo" => $Feedphotoarray, "date" => $viewMyFeed['0'][$cc][$jj]['date'],"gid" => $gidd, "screen" => $gidscreen); 
                                            }
                                      // $FriendFeedarray[] = array("groupname" => $Groupname, "groupphoto" => $Groupphoto, "text" => $viewMyFeed['0'][$cc][$jj]['text'], "photo" => $Feedphotoarray, "date" => $viewMyFeed['0'][$cc][$jj]['date'],"gid" => $gidd, "screen" => $gidscreen);
                                  unset($Feedphotoarray);     
                                }                             

                            }
                      //      var_dump($viewMyFeed);
                        unset($viewMyFeed);

                        $CounterWallget = $CounterWallget + 24;
                        $CounterWallget24 = $CounterWallget24 + 24;
                        $ccc++;
                        }

                    if($CounterModGroups != 0)
                    {
                        $CounterWallget = 0;
                        $CounterWallget24 = 24;
                        unset($viewMyFeed);

                        $CounterMod = $ccc*24;
                        $CounterModGroups = $CounterMod + $CounterModGroups;

                        $codeStr = 'var a=API.groups.get({"user_id":"'.$_GET['id'].'"}); var b=a; var d='.$CounterMod.'; var v='.$CounterModGroups.';
                            var c = [];
                            while (d < v)
                            {
                             c.push(API.wall.get({"owner_id":-b[d],"count":"4"}));
                             d = d+1; 
                            };
                            return c;';       

                        $viewMyFeed[] = $vk->getExecuteFeedFriends($codeStr);
                        //  var_dump($viewMyFeed);
                        for($cc=0; $cc<count($viewMyFeed['0']); $cc++)
                        {
                            
                            for($jj = 1; $jj<5; $jj++)
                            {    
                                   for($vv=0; $vv<count($Groupinfo['0']);$vv++)
                                   {

                                        if("-".$Groupinfo['0'][$vv]['gid'] == $viewMyFeed['0'][$cc][$jj]['from_id']) 
                                        {
                                            $gidscreen = $Groupinfo['0'][$vv]['screen_name'];
                                            $gidd = $Groupinfo['0'][$vv]['gid'];
                                            $Groupphoto = $Groupinfo['0'][$vv]['photo'];
                                            $Groupname = $Groupinfo['0'][$vv]['name']; 
                                            break;
                                        }
                                   }
                                    for ($vv=0; $vv < count($viewMyFeed['0'][$cc][$jj]['attachments']); $vv++) 
                                    { 
                                        $Feedphotoarray[] = $viewMyFeed['0'][$cc][$jj]['attachments'][$vv]['photo']['src_big'];
                                    }
                                     if(checkdatearr($viewMyFeed['0'][$cc][$jj]['date']) == 1)
                                        {
                                            $FriendFeedarray[] = array("groupname" => $Groupname, "groupphoto" => $Groupphoto, "text" => $viewMyFeed['0'][$cc][$jj]['text'], "photo" => $Feedphotoarray, "date" => $viewMyFeed['0'][$cc][$jj]['date'],"gid" => $gidd, "screen" => $gidscreen); 
                                        }
                                   //$FriendFeedarray[] = array("groupname" => $Groupname, "groupphoto" => $Groupphoto, "text" => $viewMyFeed['0'][$cc][$jj]['text'], "photo" => $Feedphotoarray, "date" => $viewMyFeed['0'][$cc][$jj]['date'],"gid" => $gidd, "screen" => $gidscreen);
                                   unset($Feedphotoarray);
                            } 
                        

                        }

                                }
            //var_dump($viewMyFeed);
            // наш главный класс в котором пока есть методы только для сортировки и работы с датами
            $FF = new FriendFeed();
            $FriendFeedarray = $FF->TimeFeedSort($FriendFeedarray);
       
            $memcache_obj->set($sessionid, $FriendFeedarray, false, 86400);
            $memcache_obj->set($sessionid.$sessionid, $FriendFeedarray, false, 86400);
           
            //$memcache_obj->set($sessionid, $FriendFeedarray, false, 86400);
            }
        else
            {
            $FF = new FriendFeed();
            $FriendFeedarray = $FriendFeedarray1;
            }
        }

    $FF = new FriendFeed();
  //  var_dump($FriendFeedarray);
    $FriendFeedarray = $FF->TimeFeedSort($FriendFeedarray);
    //echo "vse ok";
 
   $memcache_obj->set($sessionid, $FriendFeedarray, false, 86400);
$memcache_obj->set($sessionid.$sessionid, $FriendFeedarray, false, 86400);

  //var_dump($memcache_obj->get($sessionid));
  // var_dump($memcache_obj->get($sessionid.$sessionid));
    //echo $memcache_obj->get($sessionid."friends")."\n";
    //echo $memcache_obj->get($sessionid."idpage")."\n";
    //var_dump($FriendFeedarray);
    //$memcache_obj->set($sessionid.$sessionid, $FriendFeedarray, false, 86400);
    }
    else
    {
       
          unset($FriendFeedarray);
          unset($FriendFeedarray1);
        //echo $_GET['id']."\n";
       // echo $memcache_obj->get($sessionid."idpage")."\n";
            //echo $_GET['id'];
         // echo $sessionid;
        
             $memcache_obj->set($sessionid."idpage", $_GET['id'], false, 1200);
       
          //echo $memcache_obj->get($sessionid."idpage");
            // exit;
//exit;
           //  echo $memcache_obj->get($sessionid."idpage");
            // unset($FriendFeedarray);
            $GroupIds[] = $vk->getGroupsforWall($_GET['id']);
            for($mm=0;$mm<count($GroupIds['0']);$mm++)
            {
         if($GroupIdsStr == "") $GroupIdsStr = $GroupIds['0'][$mm];
                else $GroupIdsStr = $GroupIdsStr.",".$GroupIds['0'][$mm];
            }
//echo $GroupIdsStr;
        $Groupinfo[] = $vk->getGroupsById($GroupIdsStr);

// целое число запросов к группам 

$CountDivGroups = floor(count($Groupinfo['0'])/24);

// остаток от деления на 24, максимальное число запросов в группам

$CounterModGroups = count($Groupinfo['0']) % 24;
$CounterWallget = 0;
$CounterWallget24 = 24;

while($ccc<$CountDivGroups)
{

    $codeStr = 'var a=API.groups.get({"user_id":"'.$_GET['id'].'"}); var b=a; var d='.$CounterWallget.'; var v='.$CounterWallget24.';
        var c = [];
        while (d < v)
        {
         c.push(API.wall.get({"owner_id":-b[d],"count":"4"}));
         d = d+1; 
        };
        return c;';       

    $viewMyFeed[] = $vk->getExecuteFeedFriends($codeStr);
    //var_dump($viewMyFeed);
     // var_dump($viewMyFeed);
    for($cc=0; $cc<count($viewMyFeed['0']); $cc++)
    {
        
        for($jj = 1; $jj<5; $jj++)
        {    
               for($vv=0; $vv<count($Groupinfo['0']);$vv++)
               {

                    if("-".$Groupinfo['0'][$vv]['gid'] == $viewMyFeed['0'][$cc][$jj]['from_id']) 
                    {
                        $gidscreen = $Groupinfo['0'][$vv]['screen_name'];
                        $gidd = $Groupinfo['0'][$vv]['gid'];
                        $Groupphoto = $Groupinfo['0'][$vv]['photo'];
                        $Groupname = $Groupinfo['0'][$vv]['name'];                                           
                        break;
                    }
               }
                for ($vv=0; $vv < count($viewMyFeed['0'][$cc][$jj]['attachments']); $vv++) 
                { 
                    $Feedphotoarray[] = $viewMyFeed['0'][$cc][$jj]['attachments'][$vv]['photo']['src_big'];
                }
                 if(checkdatearr($viewMyFeed['0'][$cc][$jj]['date']) == 1)
                {
                    $FriendFeedarray[] = array("groupname" => $Groupname, "groupphoto" => $Groupphoto, "text" => $viewMyFeed['0'][$cc][$jj]['text'], "photo" => $Feedphotoarray, "date" => $viewMyFeed['0'][$cc][$jj]['date'],"gid" => $gidd, "screen" => $gidscreen); 
                }
            //   $FriendFeedarray[] = array("groupname" => $Groupname, "groupphoto" => $Groupphoto, "text" => $viewMyFeed['0'][$cc][$jj]['text'], "photo" => $Feedphotoarray, "date" => $viewMyFeed['0'][$cc][$jj]['date'],"gid" => $gidd, "screen" => $gidscreen);
          unset($Feedphotoarray);     
        } 
    

    }

    unset($viewMyFeed);

    $CounterWallget = $CounterWallget + 24;
    $CounterWallget24 = $CounterWallget24 + 24;
    $ccc++;
}

if($CounterModGroups != 0)
{
    $CounterWallget = 0;
    $CounterWallget24 = 24;
    unset($viewMyFeed);

    $CounterMod = $ccc*24;
    $CounterModGroups = $CounterMod + $CounterModGroups;

    $codeStr = 'var a=API.groups.get({"user_id":"'.$_GET['id'].'"}); var b=a; var d='.$CounterMod.'; var v='.$CounterModGroups.';
        var c = [];
        while (d < v)
        {
         c.push(API.wall.get({"owner_id":-b[d],"count":"4"}));
         d = d+1; 
        };
        return c;';       

    $viewMyFeed[] = $vk->getExecuteFeedFriends($codeStr);
    //  var_dump($viewMyFeed);
    for($cc=0; $cc<count($viewMyFeed['0']); $cc++)
    {
        
        for($jj = 1; $jj<5; $jj++)
        {    
               for($vv=0; $vv<count($Groupinfo['0']);$vv++)
               {

                    if("-".$Groupinfo['0'][$vv]['gid'] == $viewMyFeed['0'][$cc][$jj]['from_id']) 
                    {
                        $gidscreen = $Groupinfo['0'][$vv]['screen_name'];
                        $gidd = $Groupinfo['0'][$vv]['gid'];
                        $Groupphoto = $Groupinfo['0'][$vv]['photo'];
                        $Groupname = $Groupinfo['0'][$vv]['name']; 
                        break;
                    }
               }
                for ($vv=0; $vv < count($viewMyFeed['0'][$cc][$jj]['attachments']); $vv++) 
                { 
                    $Feedphotoarray[] = $viewMyFeed['0'][$cc][$jj]['attachments'][$vv]['photo']['src_big'];
                }
                 if(checkdatearr($viewMyFeed['0'][$cc][$jj]['date']) == 1)
                {
                    $FriendFeedarray[] = array("groupname" => $Groupname, "groupphoto" => $Groupphoto, "text" => $viewMyFeed['0'][$cc][$jj]['text'], "photo" => $Feedphotoarray, "date" => $viewMyFeed['0'][$cc][$jj]['date'],"gid" => $gidd, "screen" => $gidscreen); 
                }
            //   $FriendFeedarray[] = array("groupname" => $Groupname, "groupphoto" => $Groupphoto, "text" => $viewMyFeed['0'][$cc][$jj]['text'], "photo" => $Feedphotoarray, "date" => $viewMyFeed['0'][$cc][$jj]['date'],"gid" => $gidd,"screen" => $gidscreen);
               unset($Feedphotoarray);
        } 
$FF = new FriendFeed();
$FriendFeedarray = $FF->TimeFeedSort($FriendFeedarray); 

$memcache_obj->set($sessionid, $FriendFeedarray, false, 86400);
$memcache_obj->set($sessionid.$sessionid, $FriendFeedarray, false, 86400);

//var_dump($FriendFeedarray);

//var_dump($FriendFeedarray);



//$memcache_obj->set($sessionid.$sessionid, $FriendFeedarray, false, 86400);
    }
}

}
}

$memcache_obj->set($sessionid, $FriendFeedarray, false, 86400);
$memcache_obj->set($sessionid.$sessionid, $FriendFeedarray, false, 86400);

// код для проверки на кешевый массив самого себя, если у нас совпадает что мы открыли сами себя то обновляем переменную с ешем
if($sessionid == $_GET['id'])
{
    $memcache_obj->set($sessionid."me", $FriendFeedarray, false, 86400);
}

$urlFeedupdate = "http://192.168.1.141/index.php?news=".$_GET['id'];
$urlFeedCountUpdate = "http://192.168.1.141/index.php?groups=".$_GET['id'];
$urlMyProfile = "http://192.168.1.141/index.php?id=".$sessionid;

$FF = new FriendFeed();
?>


   <div class="col-md-7">
    <h5>
        Новостная лента 
    </h5><br>
    <?php
   // var_dump($FriendFeedarray);
   // var_dump($FriendFeedarray);
    ?>

  <center> <button class="btn" onclick="Intercooler.refresh($('#manual-update'));">Показать <font ic-src=<?php echo $urlFeedCountUpdate; ?> ic-poll="2s"></font> новых записей </button></center><br>

      <!--   -->



      <div id="manual-update" ic-src=<?php echo $urlFeedupdate; ?>>
                 


        <?php
       
            for ($iiii=0; $iiii < count($FriendFeedarray); $iiii++) 
                { 
      
                
        ?>
         <table class="table table-bordered row-fluid leftprofile1">
        <tr>
            <td >
                

                 <div class="media">
                <a class="pull-left" href=<?php echo "http://vk.com/".$FriendFeedarray[$iiii]['screen']; ?> target="_blank">
                  
                <img class="media-object" src= <?php echo $FriendFeedarray[$iiii]['groupphoto'];   ?>>
                </a>
                <div class="media-body">
                 &nbsp;<a href=<?php echo "http://vk.com/".$FriendFeedarray[$iiii]['screen']; ?> target="_blank"><strong> <?php echo $FriendFeedarray[$iiii]['groupname'];   ?></strong></a>
                   <?php  
                        if(!empty($FriendFeedarray[$iiii]['text']))
                        {
                            ?>
                           
                           <?php echo " "; ?> &nbsp; &nbsp; <div class="cutstring" data-display="none" data-max-length="200" data-show-text="Показать полностью.." data-hide-text="Свернуть..">                  
                       <?php echo " &nbsp;".$FriendFeedarray[$iiii]['text']; ?></div>
                        
                            <?php
                    
                        }
                   ?>

                        <?php
                         if(!empty($FriendFeedarray[$iiii]['photo']))
                        {

                         ?>
                            <br>
                            <?php
                         for ($ii=0; $ii < count($FriendFeedarray[$iiii]['photo']); $ii++) 
                            { 
                                 if($FriendFeedarray[$iiii]['photo'][$ii] != "")
                                            {
                            ?>               

        <a class="fancybox-effects-a" href=<?php echo $FriendFeedarray[$iiii]['photo'][$ii];   ?> data-fancybox-group="gallery" title=""><img src=<?php echo $FriendFeedarray[$iiii]['photo'][$ii];   ?> width="90%" alt="" /></a>
<br> 
                        <br>
                            <?php
                        }
                            }
                             } 
                                ?>
                            
                    

                            

                        </div>
                        
               

                    </div>
                    <br>
                     <div class="row">

                <!-- Blog Sidebar Widgets Column -->

                 <div class="col-md-3">
                         &nbsp;&nbsp; <font class="timetextago"><span class="glyphicon glyphicon-time"> </span>&nbsp;<?php echo $FF->timeAgo($FriendFeedarray[$iiii]['date']);   ?></font> 
                            </div>
                                <div class="col-md-5 col-md-offset-4">
                        <a href=<?php echo "http://vk.com/".$FriendFeedarray[$iiii]['screen']; ?> target="_blank"><font class="groupslink">Открыть группу  <?php echo iconv_substr($FriendFeedarray[$iiii]['groupname'], 0, 10, 'UTF-8')."...";  ?>&nbsp;<span class="glyphicon glyphicon-share-alt"> </span></font></a>
                            </div>
                            
                               

                        </div>                        
                                
                  
               </td>
               </tr>  
                 </table> 
     

<?php
}

$memcache_obj->set($sessionid."offset", 4, false, 1200);

$urlFeedupdateold = "http://192.168.1.141/index.php?old=".$_GET['id'];
$urlFeedupdateoldcache = "http://192.168.1.141/index.php?oldcache=".$_GET['id'];

?>



</div>
 
 <font ic-src=<?php echo $urlFeedupdateoldcache; ?> ic-poll="10s">Более старые записи</font>
  <div ic-src=<?php echo $urlFeedupdateold; ?> ic-trigger-on="scrolled-into-view" ic-indicator="mars">
   
  </div>


     


</div>

 <div class="col-md-3">
   
    <div class="row">

        <div class="col-md-12 leftprofile disabled"> 
       <?php 
      $friendid = [];
      $frlist[] = $memcache_obj->get($sessionid."friends");
      //var_dump($frlist);
    //  echo $_GET['id']."\n";
    //  echo $sessionid;
     // echo $_SESSION['fullname'];
      session_start();
       if($_GET['id'] == $sessionid)
            {
                $friendid['0']['last_name'] = "";
                $friendid['0']['first_name'] = $_SESSION['fullname'];
                $friendid['0']['photo_medium'] = $_SESSION['img'];
               // var_dump($friendid);
               // break;
            }
            else
            {

                 for ($fr=0; $fr < count($frlist['0']['0']); $fr++) 
                    { 
                        if($_GET['id'] == $frlist['0']['0'][$fr]['uid'])
                        {
                         $friendid[] = $frlist['0']['0'][$fr];
                            break;
                        }

                     }
            }
          
       ?>

      &nbsp;&nbsp;<h5>Профиль</h5>




       
       <div class="row">     
              <div class="media">
                <a class="pull-left" href=<?php echo $urlMyProfile; ?>>
                  
              <img class="media-object" src=<?php echo $_SESSION['img']; ?> width="80px" heigth="60px">
               
                <div class="media-body">
                  <a href=<?php echo $urlMyProfile; ?> class="profilelink"><h6><strong>&nbsp;<b><?php echo $_SESSION['fullname']; ?>&nbsp;</b></strong></h6></a>
                   
                </div>

            </div>

        </div>

     <br><br>
    
    
          
</div>

    </div>



<?php   session_write_close(); ?>


 </div>
<br>
  


 <hr>

        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <br>
                    <p>FriendFeed</p>

                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
        </footer>


</div>
</div>

<?php

     $memcache_obj->set($sessionid."friends", $listFriends, false, 1200);   

//exit;
}
else
{
   // echo "Web page not created";
}


?>
  <!-- /.container -->
   <!-- jQuery Version 1.11.0 -->
    <script src="js/jquery-1.11.0.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <script src="js/agency.js"></script>
      <script src="js/cutstring.js"></script>
     <script src="https://s3.amazonaws.com/intercoolerjs.org/release/intercooler-0.4.1.min.js"></script>



    <!-- Add mousewheel plugin (this is optional) -->
    <script type="text/javascript" src="lib/jquery.mousewheel-3.0.6.pack.js"></script>

    <!-- Add fancyBox main JS and CSS files -->
    <script type="text/javascript" src="source/jquery.fancybox.js?v=2.1.5"></script>
   

    <!-- Add Button helper (this is optional) -->
  
    <script type="text/javascript" src="source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>

    <!-- Add Thumbnail helper (this is optional) -->
  
    <script type="text/javascript" src="source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>

    <!-- Add Media helper (this is optional) -->
    <script type="text/javascript" src="source/helpers/jquery.fancybox-media.js?v=1.0.6"></script>

    

<script type="text/javascript">
        $(document).ready(function() {
           /*
             *  Simple image gallery. Uses default settings
             */

            $('.fancybox').fancybox();

            /*
             *  Different effects
             */

            // Change title type, overlay closing speed
            $(".fancybox-effects-a").fancybox({
                helpers: {
                    title : {
                        type : 'outside'
                    },
                    overlay : {
                        speedOut : 0
                    }
                }
            });

            // Disable opening and closing animations, change title type
            $(".fancybox-effects-b").fancybox({
                openEffect  : 'none',
                closeEffect : 'none',

                helpers : {
                    title : {
                        type : 'over'
                    }
                }
            });

            // Set custom style, close if clicked, change title type and overlay color
            $(".fancybox-effects-c").fancybox({
                wrapCSS    : 'fancybox-custom',
                closeClick : true,

                openEffect : 'none',

                helpers : {
                    title : {
                        type : 'inside'
                    },
                    overlay : {
                        css : {
                            'background' : 'rgba(238,238,238,0.85)'
                        }
                    }
                }
            });

            // Remove padding, set opening and closing animations, close if clicked and disable overlay
            $(".fancybox-effects-d").fancybox({
                padding: 0,

                openEffect : 'elastic',
                openSpeed  : 150,

                closeEffect : 'elastic',
                closeSpeed  : 150,

                closeClick : true,

                helpers : {
                    overlay : null
                }
            });

            /*
             *  Button helper. Disable animations, hide close button, change title type and content
             */

            $('.fancybox-buttons').fancybox({
                openEffect  : 'none',
                closeEffect : 'none',

                prevEffect : 'none',
                nextEffect : 'none',

                closeBtn  : false,

                helpers : {
                    title : {
                        type : 'inside'
                    },
                    buttons : {}
                },

                afterLoad : function() {
                    this.title = 'Image ' + (this.index + 1) + ' of ' + this.group.length + (this.title ? ' - ' + this.title : '');
                }
            });


            /*
             *  Thumbnail helper. Disable animations, hide close button, arrows and slide to next gallery item if clicked
             */

            $('.fancybox-thumbs').fancybox({
                prevEffect : 'none',
                nextEffect : 'none',

                closeBtn  : false,
                arrows    : false,
                nextClick : true,

                helpers : {
                    thumbs : {
                        width  : 50,
                        height : 50
                    }
                }
            });

            /*
             *  Media helper. Group items, disable animations, hide arrows, enable media and button helpers.
            */
            $('.fancybox-media')
                .attr('rel', 'media-gallery')
                .fancybox({
                    openEffect : 'none',
                    closeEffect : 'none',
                    prevEffect : 'none',
                    nextEffect : 'none',

                    arrows : false,
                    helpers : {
                        media : {},
                        buttons : {}
                    }
                });

            /*
             *  Open manually
             */

            $("#fancybox-manual-a").click(function() {
                $.fancybox.open('1_b.jpg');
            });

            $("#fancybox-manual-b").click(function() {
                $.fancybox.open({
                    href : 'iframe.html',
                    type : 'iframe',
                    padding : 5
                });
            });

            $("#fancybox-manual-c").click(function() {
                $.fancybox.open([
                    {
                        href : '1_b.jpg',
                        title : 'My title'
                    }, {
                        href : '2_b.jpg',
                        title : '2nd title'
                    }, {
                        href : '3_b.jpg'
                    }
                ], {
                    helpers : {
                        thumbs : {
                            width: 75,
                            height: 50
                        }
                    }
                });
            });


        });
   </script> 
       
    <script>
$(function() {
    $('.cutstring').cutstring();
});
</script>

</body>

</html>
<?php
xdebug_stop_trace();
?>