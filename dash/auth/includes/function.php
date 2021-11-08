<?php

$SECRET_KEY = 'i7B`M(0Bohb+*{$'; // for hash cookies
date_default_timezone_set('Asia/Phnom_Penh');

function signin($id, $redirect, $remember){

    require "dbh.php";
    global $SECRET_KEY;

    $sql = "SELECT * FROM users WHERE id=:id";
    $handler = $db_conn -> prepare($sql);
    $handler -> bindParam(':id', $id);
    $handler -> execute();
    $result = $handler -> fetch();

    if (!empty($result)) {
        
        if ($remember) {
            $expire = time() + (86400 * 30);
            $token = bin2hex(random_bytes(16));
    
            try {
                $sql = "UPDATE users SET remember_token = :token WHERE id = :id;";
                $handler = $db_conn -> prepare($sql);
                $handler -> bindParam(':token', $token);
                $handler -> bindParam(':id', $id, PDO::PARAM_INT);
                $handler -> execute(); 
            } catch (PDOException $e) {
                echo $e -> getMessage();
                exit;
            }
    
            $cookie = $id . ':' . $token;
            $mac = hash_hmac('sha256', $cookie, $SECRET_KEY);
            $cookie .= ':' . $mac;
    
            setcookie('clogin', $cookie, $expire, "/vision/");
        }

        $_SESSION["id"] = $result["id"];
        header("Location: ".$redirect);
        exit;
    }
}

function checklogin($redirect){
    if (isset($_SESSION["id"])) {
        header("Location: ".$redirect) and exit;
    } else if (clogin()){
        signin(clogin(), $redirect, false);
    }
}

function clogin(){
    global $SECRET_KEY;
    $cookie = isset($_COOKIE['clogin']) ? $_COOKIE['clogin'] : '';

    if ($cookie) {
        $cookie_explode = explode(':', $cookie);

        if (count($cookie_explode) == 3) {

            list ($user, $token, $mac) = $cookie_explode;

            if (!hash_equals(hash_hmac('sha256', $user . ':' . $token, $SECRET_KEY), $mac)) {
                return false;
            }

            require "dbh.php";

            $sql = "SELECT * FROM users WHERE id = :id;";
            $handler = $db_conn -> prepare($sql);
            $handler -> bindParam(':id', $user, PDO::PARAM_INT);
            $handler -> execute();
            $result = $handler -> fetch();

            $usertoken = empty($result) ? '' : $result["remember_token"];
    
            if (hash_equals($usertoken, $token)) {
                return $result["id"];
            }

        }

    }
}

function getProfileInfo($id, $except_current=false){
    require "dbh.php";

    if ($except_current) {
        $sql = "SELECT * FROM users WHERE id = :id AND id <> :current_id";   
        $handler = $db_conn -> prepare($sql);
        $handler -> bindParam(':id', $id, PDO::PARAM_INT);
        $handler -> bindParam(':current_id', $_SESSION['id'], PDO::PARAM_INT);
    } else {
        $sql = "SELECT * FROM users WHERE id = :id";   
        $handler = $db_conn -> prepare($sql);
        $handler -> bindParam(':id', $id, PDO::PARAM_INT);
    }

    $handler -> execute();
    $result = $handler -> fetch();

    if (!empty($result)) {
        $profile = strcasecmp($result['profile_img'], 'nothum.jpg') == 0 ? 
        "https://avatars.dicebear.com/api/initials/{$result['name']}.svg?chars=1" : "img/".$result['profile_img'];
        
        $result += ["profile" => $profile];
        return $result;
    } else {
        echo "<b class='text-danger'>".strtoupper("empty")."</b>";
        exit;
    }
}

function upload_img($file_img, $ftype, $fsize, $dir, $thumb = false){
    if (file_exists($file_img['tmp_name']) || is_uploaded_file($file_img['tmp_name'])){
        try {

            if (!isset($file_img['error']) || is_array($file_img['error'])) {
                throw new RuntimeException('Invalid parameters.');
            }
        
            switch ($file_img['error']) {
                case UPLOAD_ERR_OK:
                    break;
                case UPLOAD_ERR_NO_FILE:
                    throw new RuntimeException('No file sent.');
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    throw new RuntimeException('Exceeded filesize limit.');
                default:
                    throw new RuntimeException('Unknown errors.');
            }
        
            define('MB', 1048576);
        
            if ($file_img['size'] > $fsize*MB) {
                throw new RuntimeException("Exceeded {$fsize} MB filesize limit.");
            }
        
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            if (false === $ext = array_search($finfo->file($file_img['tmp_name']), $ftype, true)) {
                $msg = "";
                foreach ($ftype as $key => $value) {
                    $msg .= "*.".$key.", ";
                }
                throw new RuntimeException('Invalid file format. ('.substr($msg, 0, strlen($msg)-2).')');
            }

            if ($thumb) {
                $imgname = $thumb;
            } else {
                $imgname = sha1_file($file_img['tmp_name']);
            }
        
            $imgpath = sprintf("$dir%s.%s", $imgname, $ext);
        
            if (!move_uploaded_file($file_img['tmp_name'], $imgpath)) {
                throw new RuntimeException('Failed to move uploaded file.');
            }

            return array(
                "error" => false,
                "msg" => "OK!",
                "file_name" => sprintf("%s.%s", $imgname, $ext),
                "file_path" => $imgpath
            );

        } catch (RuntimeException $e) {
            return array(
                "error" => true,
                "msg" => $e -> getMessage()
            );
        }
    } else {
        return false;
    }
}

function time_elapsed_str($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = date_diff($now, $ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

function pagination($currentPage, $itemCount, $itemsPerPage, $adjacentCount, $pageLinkTemplate, $showPrevNext = true) {
    $firstPage = 1;
    $lastPage = ceil($itemCount / $itemsPerPage);
    if ($lastPage < 2) { // hide pagination with 1 page
        return;
    }
    if ($currentPage <= $adjacentCount*2) {
        $firstAdjacentPage = $firstPage;
        $lastAdjacentPage  = min($firstPage + $adjacentCount*2, $lastPage);
    } elseif ($currentPage > $lastPage - $adjacentCount - $adjacentCount) {
        $lastAdjacentPage  = $lastPage;
        $firstAdjacentPage = $lastPage - $adjacentCount - $adjacentCount;
    } else {
        $firstAdjacentPage = $currentPage - $adjacentCount;
        $lastAdjacentPage  = $currentPage + $adjacentCount;
    }
    echo '<nav><ul class="pagination justify-content-center mt-4">';
    if ($showPrevNext) {
        if ($currentPage == $firstPage) {
            echo '<li class="page-item disabled"><span class="page-link"><i class="fas fa-arrow-left"></i></span></li>';
        } else {
            echo '<li class="page-item"><a class="page-link" href="' . (is_callable($pageLinkTemplate) ? $pageLinkTemplate($currentPage - 1) : sprintf($pageLinkTemplate, $currentPage - 1)) . '"><i class="fas fa-arrow-left"></i></a></li>';
        }
    }
    if ($firstAdjacentPage > $firstPage) {
        echo '<li class="page-item"><a class="page-link" href="' . (is_callable($pageLinkTemplate) ? $pageLinkTemplate($firstPage) : sprintf($pageLinkTemplate, $firstPage)) . '">' . $firstPage . '</a></li>';
        if ($firstAdjacentPage > $firstPage + 1) {
            echo '<span class="mx-2">...</span>';
        }
    }
    for ($i = $firstAdjacentPage; $i <= $lastAdjacentPage; $i++) {
        if ($currentPage == $i) {
            echo '<li class="page-item disabled"><b class="page-link active">' . $i . '</b></li>';
        } else {
            echo '<li class="page-item"><a class="page-link" href="' . (is_callable($pageLinkTemplate) ? $pageLinkTemplate($i) : sprintf($pageLinkTemplate, $i)) . '">' . $i . '</a></li>';
        }
    }
    if ($lastAdjacentPage < $lastPage) {
        if ($lastAdjacentPage < $lastPage - 1) {
            echo '<span class="mx-2">...</span>';
        }
        echo '<li class="page-item"><a class="page-link" href="' . (is_callable($pageLinkTemplate) ? $pageLinkTemplate($lastPage) : sprintf($pageLinkTemplate, $lastPage)) . '">' . $lastPage . '</a></li>';
    }
    if ($showPrevNext) {
        if ($currentPage == $lastPage) {
            echo '<li class="page-item disabled"><span class="page-link"><i class="fas fa-arrow-right"></i></span></li>';
        } else {
            echo '<li class="page-item"><a class="page-link" href="' . (is_callable($pageLinkTemplate) ? $pageLinkTemplate($currentPage + 1) : sprintf($pageLinkTemplate, $currentPage + 1)) . '"><i class="fas fa-arrow-right"></i></a></li>';
        }
    }
    echo '</ul></nav>';
}

function getProfileImg($name, $path, $profile_name){
    $default = "https://avatars.dicebear.com/api/initials/$name.svg?chars=1";
    return ($profile_name == "nothum.jpg") ? $default : $path.$profile_name;
}


?>