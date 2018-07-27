<<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>this a scraper</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
    <script src="main.js"></script>
</head>
<body>
    <?php
  session_unset();
  session_start();
    include 'scraping4.php';
    for ($count = 0; $count < 90; ++$count) {
        if ($count === 0) {
            $page = geturl("$initial_point_url");
            preg_match_all('/<span class="text-normal">(.+)<\/span>(.+)/', $page, $maches);
            preg_match_all('/<form accept-charset="UTF-8" action="(.*)" class=".+" method=".+">/', $page, $next_page);
            $_SESSION['next_page'] = $next_page[1];
            for ($i = 0; $i < 30; ++$i) {
                $id = $id + 1;
                $maches[1][$i] = str_replace(' ', '', $maches[1][$i]);
                $maches[2][$i] = str_replace(' ', '', $maches[2][$i]);
                $maches[1][$i] = add_special_chars($maches[1][$i]);
                $maches[2][$i] = add_special_chars($maches[2][$i]);
                $_SESSION['inner_url'] = $home_page.$maches[1][$i].$maches[2][$i];
                //   var_dump($_SESSION['inner_url']);
                //  echo$_SESSION['maches'][1][$i].'<br/>';
                //  echo$_SESSION['maches'][2][$i].'<br/>';

                try {
                    $sql = 'INSERT INTO scraping SET
                                source_url = "'.$_SESSION['inner_url'].'";';
                    $pdo->exec($sql);
                } catch (PDOExeption $e) {
                    echo $e->getMessage();
                    exit();
                }
                $_SESSION['inner_page'] = geturl($_SESSION['inner_url']);

                try {
                    $sql = 'UPDATE scraping set
                                  project_name = "'.$maches[2][$i].'"
                                  WHERE id = '.$id.'';
                    $pdo->exec($sql);
                } catch (PDOExeption $e) {
                    echo $e->getMessage();
                    exit();
                }
                //preg_match_all('<a href=\"\/.+\/.+\" data-pjax=\"#js-repo-pjax-container">(.+)<\/a>', $inner_page, $project_name);
                preg_match_all("/\/<span class=\"col-11 text-gray-dark mr-2\" itemprop=\"about\">|[^>]+(.+)<\/span>/ ", $_SESSION['inner_page'], $shrt_descrp);
                $check_result = check_for_project($shrt_descrp);
                echo $short = substr($shrt_descrp[0][$check_result->returnIndex() + 1], 0, -7).'<br/>';
                $short = add_special_chars($short);
                try {
                    $sql = 'UPDATE scraping SET
                                   shrt_descrtion ="'.$short.'"
                                   WHERE id = '.$id.'';
                    $pdo->exec($sql);
                } catch (PDOExeption $e) {
                    echo$e->getMessage();
                    die();
                }
                if (proj_url($shrt_descrp, $check_result)) {
                    echo $pro_url = $shrt_descrp[0][$check_result->returnIndex() + 2].'<br/>';
                    $pro_url = add_special_chars($pro_url);
                    try {
                        $sql = 'UPDATE scraping SET
                                 project_url ="'.$pro_url.'"
                                 WHERE id = '.$id.'';
                        // var_dump($sql);
                        //exit();
                        $pdo->exec($sql);
                    } catch (PDOExeption $e) {
                        echo$e->getMessage();
                        die();
                    }
                } elseif (!(proj_url($shrt_descrp, $check_result))) {
                    try {
                        $sql = 'UPDATE scraping SET
                               project_url ="'.$_SESSION['inner_url'].'"
                               WHERE id = '.$id.'';
                        $pdo->exec($sql);
                    } catch (PDOExeption $e) {
                        echo$e->getMessage();
                        die();
                    }
                }
                preg_match_all('/<article class=\"markdown-body entry-content\" itemprop=\"text\">|<[^>]+>(.*)<\/[^>]+>|U<\/article>/', $_SESSION['inner_page'], $matches1);

                $num_element = count($matches1[0]);

                for ($r = $num_element; $r >= 0; --$r) {
                    if (preg_match("/[A-Z][a-z]+ \d+, \d+/", $matches1[0][$r], $test)) {
                        break;
                    }
                }
                $addr = $r;

                $r = 0;
                for ($r = 0; $r <= $addr; ++$r) {
                    $matches1[0][$r] = '';
                    if ($matches1[0][$r] == $test[0]) {
                        break;
                    }
                }

                define('MY_CONSTANT', serialize(array('sponsors',  'learning',
                'contribut', 'contribution', 'overview', 'official documentation', 'install', 'documentation', 'table of content', 'content', 'usage', 'getting started',
          'requirements', 'how does it work', 'demo', 'set up', 'how to install', 'todo list', 'installation', 'installation of ',
          'set up', 'version compatibility', 'to do list', 'how to use it',
          'install ', 'installing ',   'support', 'composer install', 'credit notice', 'what it looks like', 'what it look like',
          'guide', 'database migration',  'license', '可用的验证规则',  )));

                $test_values = unserialize(MY_CONSTANT);

                $matches1[0] = implode('', $matches1[0]);
                $matches1[0] = explode('<h', $matches1[0]);

                for ($u = 0; $u < count($test_values); ++$u) {
                    if ($u === 0) {
                        if (tester(0, $test_values[$u], $matches1[0])->getStatus() == true) {
                            $addr1 = tester(0, $test_values[$u], $matches1[0])->getAddr();
                            remove($addr1, $matches1[0]);
                        }
                    } else {
                        if (tester(0, $test_values[$u - 1], $matches1[0])->getStatus() == true) {
                            if ((tester(0, $test_values[$u], $matches1[0])->getStatus() == true)) {
                                $addr1 = tester(0, $test_values[$u], $matches1[0])->getAddr();
                                remove($addr1, $matches1[0]);
                            }
                        } else {
                            if ((tester(0, $test_values[$u], $matches1[0])->getStatus() == true)) {
                                $addr1 = tester(0, $test_values[$u], $matches1[0])->getAddr();
                                remove($addr1, $matches1[0]);
                            }
                        }
                    }
                }
                // var_dump($matches1[0]);

                //  print_r($_SESSION['matches1'][0]);
                //  exit();
                for ($s = 0; $s < $num_element; ++$s) {
                    $matches1[0][$s] = add_special_chars($matches1[0][$s]);
                }
                //  print_r($result = $_SESSION['matches1'][0]);

                //   print_r(image_remover($num_element, $_SESSION['matches1'][0]));
                $result = implode('<h', $matches1[0]);
                // exit();
                /* var_dump($matches1[0]);
                 exit();*/
                try {
                    $sql = 'UPDATE scraping SET
                               long_descrtion = "'.$result.'"
                               WHERE id = '.$id.'';

                    $pdo->exec($sql);
                } catch (PDOExeption $e) {
                    echo$e->getMessage();
                    die();
                }
            }
        } elseif ($count == 1) {
            $page = geturl('https://github.com/topics/laravel?utf8=%E2%9C%93&after=Y3Vyc29yOjMw');
            preg_match_all('/<span class="text-normal">(.+)<\/span>(.+)/', $page, $maches);
            preg_match_all('/<form accept-charset="UTF-8" action="(.*)" class=".+" method=".+">/', $page, $next_page);
            // var_dump($next_page[1][1]);
            // exit();
            for ($i = 0; $i < 30; ++$i) {
                $id = $id + 1;
                $maches[1][$i] = str_replace(' ', '', $maches[1][$i]);
                $maches[2][$i] = str_replace(' ', '', $maches[2][$i]);
                $maches[1][$i] = add_special_chars($maches[1][$i]);
                $maches[2][$i] = add_special_chars($maches[2][$i]);
                $_SESSION['inner_url'] = $home_page.$maches[1][$i].$maches[2][$i];
                //   var_dump($_SESSION['inner_url']);
                //  echo$_SESSION['maches'][1][$i].'<br/>';
                //  echo$_SESSION['maches'][2][$i].'<br/>';

                try {
                    $sql = 'INSERT INTO scraping SET
                             source_url = "'.$_SESSION['inner_url'].'";';
                    $pdo->exec($sql);
                } catch (PDOExeption $e) {
                    echo $e->getMessage();
                    exit();
                }
                $_SESSION['inner_page'] = geturl($_SESSION['inner_url']);

                try {
                    $sql = 'UPDATE scraping set
                                project_name = "'.$maches[2][$i].'"
                                WHERE id = '.$id.'';
                    $pdo->exec($sql);
                } catch (PDOExeption $e) {
                    echo $e->getMessage();
                    exit();
                }
                //preg_match_all('<a href=\"\/.+\/.+\" data-pjax=\"#js-repo-pjax-container">(.+)<\/a>', $inner_page, $project_name);
                preg_match_all("/\/<span class=\"col-11 text-gray-dark mr-2\" itemprop=\"about\">|[^>]+(.+)<\/span>/ ", $_SESSION['inner_page'], $shrt_descrp);
                $check_result = check_for_project($shrt_descrp);
                echo $short = substr($shrt_descrp[0][$check_result->returnIndex() + 1], 0, -7).'<br/>';
                $short = add_special_chars($short);
                try {
                    $sql = 'UPDATE scraping SET
                              shrt_descrtion ="'.$short.'"
                              WHERE id = '.$id.'';
                    $pdo->exec($sql);
                } catch (PDOExeption $e) {
                    echo$e->getMessage();
                    die();
                }
                if (proj_url($shrt_descrp, $check_result)) {
                    echo $pro_url = $shrt_descrp[0][$check_result->returnIndex() + 2].'<br/>';
                    $pro_url = add_special_chars($pro_url);
                    try {
                        $sql = 'UPDATE scraping SET
                               project_url ="'.$pro_url.'"
                               WHERE id = '.$id.'';
                        // var_dump($sql);
                        //exit();
                        $pdo->exec($sql);
                    } catch (PDOExeption $e) {
                        echo$e->getMessage();
                        die();
                    }
                } elseif (!(proj_url($shrt_descrp, $check_result))) {
                    try {
                        $sql = 'UPDATE scraping SET
                            project_url ="'.$_SESSION['inner_url'].'"
                            WHERE id = '.$id.'';
                        $pdo->exec($sql);
                    } catch (PDOExeption $e) {
                        echo$e->getMessage();
                        die();
                    }
                }
                preg_match_all('/<article class=\"markdown-body entry-content\" itemprop=\"text\">|<[^>]+>(.*)<\/[^>]+>|U<\/article>/', $_SESSION['inner_page'], $matches1);

                $num_element = count($matches1[0]);

                for ($r = $num_element; $r >= 0; --$r) {
                    if (preg_match("/[A-Z][a-z]+ \d+, \d+/", $matches1[0][$r], $test)) {
                        break;
                    }
                }
                $addr = $r;
                $r = 0;
                for ($r = 0; $r <= $addr; ++$r) {
                    $matches1[0][$r] = '';
                    if ($matches1[0][$r] == $test[0]) {
                        break;
                    }
                }

                define('MY_CONSTANT', serialize(array('sponsors',  'learning',
                'contribut', 'contribution', 'overview', 'official documentation', 'install', 'documentation', 'table of content', 'content', 'usage', 'getting started',
          'requirements', 'how does it work', 'demo', 'set up', 'how to install', 'todo list', 'installation', 'installation of ',
          'set up', 'version compatibility', 'to do list', 'how to use it',
          'install ', 'installing ',   'support', 'composer install', 'credit notice', 'what it looks like', 'what it look like',
          'guide', 'database migration',  'license',  )));

                $test_values = unserialize(MY_CONSTANT);

                $matches1[0] = implode('', $matches1[0]);
                $matches1[0] = explode('<h', $matches1[0]);

                for ($u = 0; $u < count($test_values); ++$u) {
                    if ($u === 0) {
                        if (tester(0, $test_values[$u], $matches1[0])->getStatus() == true) {
                            $addr1 = tester(0, $test_values[$u], $matches1[0])->getAddr();
                            remove($addr1, $matches1[0]);
                        }
                    } else {
                        if (tester(0, $test_values[$u - 1], $matches1[0])->getStatus() == true) {
                            if ((tester(0, $test_values[$u], $matches1[0])->getStatus() == true)) {
                                $addr1 = tester(0, $test_values[$u], $matches1[0])->getAddr();
                                remove($addr1, $matches1[0]);
                            }
                        } else {
                            if ((tester(0, $test_values[$u], $matches1[0])->getStatus() == true)) {
                                $addr1 = tester(0, $test_values[$u], $matches1[0])->getAddr();
                                remove($addr1, $matches1[0]);
                            }
                        }
                    }
                }
                //  print_r($_SESSION['matches1'][0]);
                //  exit();
                for ($s = 0; $s < $num_element; ++$s) {
                    $matches1[0][$s] = add_special_chars($matches1[0][$s]);
                }
                //  print_r($result = $_SESSION['matches1'][0]);

                //   print_r(image_remover($num_element, $_SESSION['matches1'][0]));
                $result = implode('<h', $matches1[0]);
                /* var_dump($matches1[0]);
                 exit();*/
                try {
                    $sql = 'UPDATE scraping SET
                               long_descrtion = "'.$result.'"
                               WHERE id = '.$id.'';

                    $pdo->exec($sql);
                } catch (PDOExeption $e) {
                    echo$e->getMessage();
                    die();
                }
            }
        } else {
            preg_match_all('/<input name="after" type="hidden" value="(.+)"/', $page, $supli_adder);
            $nex_page = $next_page[1][1].$url_adder.$supli_adder[1][0];
            // var_dump($_SESSION['next_page']);
            //exit();

            $page = geturl($nex_page);
            preg_match_all('/<span class="text-normal">(.+)<\/span>(.+)/', $page, $maches);
            preg_match_all('/<form accept-charset="UTF-8" action="(.*)" class=".+" method=".+">/', $page, $next_page);
            $_SESSION['next_page'] = $next_page[1];
            for ($i = 0; $i < 30; ++$i) {
                $id = $id + 1;
                $maches[1][$i] = str_replace(' ', '', $maches[1][$i]);
                $maches[2][$i] = str_replace(' ', '', $maches[2][$i]);
                $maches[1][$i] = add_special_chars($maches[1][$i]);
                $maches[2][$i] = add_special_chars($maches[2][$i]);
                $_SESSION['inner_url'] = $home_page.$maches[1][$i].$maches[2][$i];
                //   var_dump($_SESSION['inner_url']);
                //  echo$_SESSION['maches'][1][$i].'<br/>';
                //  echo$_SESSION['maches'][2][$i].'<br/>';

                try {
                    $sql = 'INSERT INTO scraping SET
                             source_url = "'.$_SESSION['inner_url'].'";';
                    $pdo->exec($sql);
                } catch (PDOExeption $e) {
                    echo $e->getMessage();
                    exit();
                }
                $_SESSION['inner_page'] = geturl($_SESSION['inner_url']);

                try {
                    $sql = 'UPDATE scraping set
                                project_name = "'.$maches[2][$i].'"
                                WHERE id = '.$id.'';
                    $pdo->exec($sql);
                } catch (PDOExeption $e) {
                    echo $e->getMessage();
                    exit();
                }
                //preg_match_all('<a href=\"\/.+\/.+\" data-pjax=\"#js-repo-pjax-container">(.+)<\/a>', $inner_page, $project_name);
                preg_match_all("/\/<span class=\"col-11 text-gray-dark mr-2\" itemprop=\"about\">|[^>]+(.+)<\/span>/ ", $_SESSION['inner_page'], $shrt_descrp);
                $check_result = check_for_project($shrt_descrp);
                echo $short = substr($shrt_descrp[0][$check_result->returnIndex() + 1], 0, -7).'<br/>';
                $short = add_special_chars($short);
                try {
                    $sql = 'UPDATE scraping SET
                              shrt_descrtion ="'.$short.'"
                              WHERE id = '.$id.'';
                    $pdo->exec($sql);
                } catch (PDOExeption $e) {
                    echo$e->getMessage();
                    die();
                }
                if (proj_url($shrt_descrp, $check_result)) {
                    echo $pro_url = $shrt_descrp[0][$check_result->returnIndex() + 2].'<br/>';
                    $pro_url = add_special_chars($pro_url);
                    try {
                        $sql = 'UPDATE scraping SET
                               project_url ="'.$pro_url.'"
                               WHERE id = '.$id.'';
                        // var_dump($sql);
                        //exit();
                        $pdo->exec($sql);
                    } catch (PDOExeption $e) {
                        echo$e->getMessage();
                        die();
                    }
                } elseif (!(proj_url($shrt_descrp, $check_result))) {
                    try {
                        $sql = 'UPDATE scraping SET
                            project_url ="'.$_SESSION['inner_url'].'"
                            WHERE id = '.$id.'';
                        $pdo->exec($sql);
                    } catch (PDOExeption $e) {
                        echo$e->getMessage();
                        die();
                    }
                }
                preg_match_all('/<article class=\"markdown-body entry-content\" itemprop=\"text\">|<[^>]+>(.*)<\/[^>]+>|U<\/article>/', $_SESSION['inner_page'], $matches1);

                $num_element = count($matches1[0]);

                for ($r = $num_element; $r >= 0; --$r) {
                    if (preg_match("/[A-Z][a-z]+ \d+, \d+/", $matches1[0][$r], $test)) {
                        break;
                    }
                }
                $addr = $r;
                $r = 0;
                for ($r = 0; $r <= $addr; ++$r) {
                    $matches1[0][$r] = '';
                    if ($matches1[0][$r] == $test[0]) {
                        break;
                    }
                }

                define('MY_CONSTANT', serialize(array('sponsors',  'learning',
                'contribut', 'contribution', 'overview', 'official documentation', 'install', 'documentation', 'table of content', 'content', 'usage', 'getting started',
          'requirements', 'how does it work', 'demo', 'set up', 'how to install', 'todo list', 'installation', 'installation of ',
          'set up', 'version compatibility', 'to do list', 'how to use it',
          'install ', 'installing ',   'support', 'composer install', 'credit notice', 'what it looks like', 'what it look like',
          'guide', 'database migration',  'license',  )));

                $test_values = unserialize(MY_CONSTANT);

                $matches1[0] = implode('', $matches1[0]);
                $matches1[0] = explode('<h', $matches1[0]);

                for ($u = 0; $u < count($test_values); ++$u) {
                    if ($u === 0) {
                        if (tester(0, $test_values[$u], $matches1[0])->getStatus() == true) {
                            $addr1 = tester(0, $test_values[$u], $matches1[0])->getAddr();
                            remove($addr1, $matches1[0]);
                        }
                    } else {
                        if (tester(0, $test_values[$u - 1], $matches1[0])->getStatus() == true) {
                            if ((tester(0, $test_values[$u], $matches1[0])->getStatus() == true)) {
                                $addr1 = tester(0, $test_values[$u], $matches1[0])->getAddr();
                                remove($addr1, $matches1[0]);
                            }
                        } else {
                            if ((tester(0, $test_values[$u], $matches1[0])->getStatus() == true)) {
                                $addr1 = tester(0, $test_values[$u], $matches1[0])->getAddr();
                                remove($addr1, $matches1[0]);
                            }
                        }
                    }
                }
                //  print_r($_SESSION['matches1'][0]);
                //  exit();
                for ($s = 0; $s < $num_element; ++$s) {
                    $matches1[0][$s] = add_special_chars($matches1[0][$s]);
                }
                //  print_r($result = $_SESSION['matches1'][0]);

                //   print_r(image_remover($num_element, $_SESSION['matches1'][0]));
                $result = implode('<h', $matches1[0]);
                /* var_dump($matches1[0]);
                 exit();*/
                try {
                    $sql = 'UPDATE scraping SET
                               long_descrtion = "'.$result.'"
                               WHERE id = '.$id.'';

                    $pdo->exec($sql);
                } catch (PDOExeption $e) {
                    echo$e->getMessage();
                    die();
                }
            }
        }
    }
 //   print_r($_SESSION);
 ?>
</body>
</html>