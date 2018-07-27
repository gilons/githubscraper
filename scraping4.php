<?php

try {
    $pdo = new  PDO('mysql:host=localhost;dbname=scrapp', 'root', 'password');

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //  echo 'Connected successfully';
    $pdo->exec('SET NAMES "utf8"');
} catch (PDOException $e) {
    echo $e->getMessage();
    exit();
}

try {
    $sql = 'CREATE TABLE scraping (
        id int not null auto_increment primary key,
        project_name text,
        shrt_descrtion text,
        project_url  text,
        source_url text,
        long_descrtion text) DEFAULT CHARACTER SET utf8mb4 ENGINE=InnoDB';
    // var_dump($sql);
    //exit();
    $pdo->exec($sql);
} catch (PDOExeption $e) {
    echo $e->getMessqge();
    die();
}
try {
    $sql = 'ALTER TABLE scraping CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_bin';
    // var_dump($sql);
    //exit();
    $pdo->exec($sql);
} catch (PDOExeption $e) {
    echo $e->getMessqge();
    die();
}
try {
    $sql = 'SET NAMES utf8mb4';
    // var_dump($sql);
    //exit();
    $pdo->exec($sql);
} catch (PDOExeption $e) {
    echo $e->getMessqge();
    die();
}

class CheckResult
{
    private $statue;
    private $index;

    public function getStatue($c)
    {
        $this->statue = $c;
    }

    public function getIndex($c)
    {
        $this->index = $c;
    }

    public function returnIndex()
    {
        return $this->index;
    }

    public function returnStatue()
    {
        return $this->statue;
    }

    public function showIndex()
    {
        echo $this->index;
    }

    public function showStatue()
    {
        echo $this->statue;
    }
}
class TestResult
{
    private $status;
    private $addr;

    public function modifStatus($c)
    {
        $this->status = $c;
    }

    public function modifAddr($c)
    {
        $this->addr = $c;
    }

    public function getAddr()
    {
        return $this->addr;
    }

    public function getStatus()
    {
        return $this->status;
    }
}
function remove($addr2, &$arr)
{
    for ($i = $addr2; $i < count($arr); ++$i) {
        $arr[$i] = '';
    }
}

 function tester($addr, $string, &$arr)
 {
     $matches1 = null;
     $test1 = new TestResult();
     for ($i = $addr; $i < count($arr); ++$i) {
         preg_match_all("/(1|2|3)><a .+\><\/a>(.+)<(\/h1|\/h2|\/h3)>/i", $arr[$i], $matches1[$i]);

         if (strpos(rtrim(strtolower($matches1[$i][2][0])), $string) !== false) {
             $test1->modifStatus(true);
             $test1->modifAddr($i);

             return $test1;
         }
     }
     $test1->modifAddr(false);
     $test1->modifAddr(null);

     return $test1;
 }

 function image_remover($addr, $num, &$arr)
 {
     $temp = null;
     //  echo impode('', $arr);
     preg_match_all('/<img.+src=\"(.+)\">/', implode('', $arr), $temp);
     for ($i = 0; $i < $num; ++$i) {
         for ($j = 0; $j = count($temp[0]); ++$j) {
             if (strpos($arr[$i], $temp[1][$j])) {
                 echo$i.'######################################################################################################'.'<br/>';
                 $arr[$i] = '';
             }
         }
     }
 }

 function add_special_chars(&$arr)
 {
     $arr = explode('"', $arr);
     for ($i = 1; $i < count($arr); ++$i) {
         $arr[$i] = '\\"'.$arr[$i];
     }

     return implode('', $arr);
 }
 $temp = null;
$addr1 = null;
$p = null;
$result = null;
$pro_url = null;
$short = null;
$supli_adder = null;
$addr = null;
$url_adder = '&utf8=%E2%9C%93&after=';
$test = null;
$num_element = null;
$inner_url = null;
$matches = [[]];
$matches2 = null;
$matches1 = null;
$last = null;

$check_result = new CheckResult();

function check_for_project(&$match)
{
    $check = new CheckResult();
    for ($i = 5; $i < 30; ++$i) {
        if (strpos($match[0][$i], 'Projects') == 7) {
            $check->getStatue(true);
            $check->getIndex($i);

            return $check;
        } elseif ($i === 19) {
            $check->getStatue(false);
            $check->getIndex($i);

            return $check;
        }
    }
}

function proj_url(&$match, &$c)
{
    $temp = null;
    preg_match("/(http:\/\/|https:\/\/|ftp:\/\/|ftps:\/\/).+/", $match[0][$c->returnIndex() + 2], $temp);
    if ($temp) {
        return true;
    }

    return false;
}
    function geturl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }
    $id = 0;
    $v = 0;
    $project_name = null;
    $home_page = 'https://github.com/';
    $initial_point_url = 'https://github.com/topics/laravel';
    $url_fix = null;
    $inner_page = null;
    $inner_url = null;
    $next_page = [[]];
    $maches = [[]];
    $shrt_descrp = [[]];
    $nex_page = null;
