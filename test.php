<?php
    $logfile = './logs/access.log';
    /* 30分前から今まで */
    $start = strtotime(date("Y-m-d H:i:00", strtotime("-30 minute")));
    $end = strtotime(date("Y-m-d H:i:00"));
    // $arrayRemoteHost = array();
    // $arrayTimeZone = array("0-6" => 0, "6-12" => 0, "12-18" => 0, "18-24" => 0);
    $array = array(
        'remoteHost' => array(),
        'timeZone' => array("0-6" => 0, "6-12" => 0, "12-18" => 0, "18-24" => 0)
    );

    if ($handle = opendir('./logs/')) {
        while (false !== ($file = readdir($handle))) {
            if ($file == '.' || $file == '..') {
                // 「.」「..」は無視
                continue;
            }
            if (preg_match('/.(log)$/', $file)) {
                $files[] = $file;
                echo $file."<br>";
            }
        }
        closedir($handle);
    } else {
        echo("ディレクトリのオープンに失敗");
    }

    foreach ($files as $value) {
        if (($fp = fopen('logs/'.$value, "r"))) {
            //while (!feof($fp)) {
            //while (($c = fgetc($fp)) != 'EOF'){
            while (strlen($str = fgets($fp)) != 0) {
                $info = explode(",", preg_replace('/^(.+?)\s(.+?)\s(.+?)\s\[(.+)\]\s\"(.+?)\"\s(.+?)\s(.+?)\s\"(.+?)\"(.+)/', "$1,$2,$3,$4,$5,$6,$7,$8,$9", $str));
                $access = strtotime($info[3]);
                // var_dump($info);
                // echo "<br>";
                // echo $access;
                $time = intval(date("H", strtotime($info[3])));
                // echo "<br>".$time;
                // if ($access > $start && $access < $end) {
                //     //echo $info;
                // }

                if (empty($array['remoteHost'][$info[0]])) {
                    $array['remoteHost'][$info[0]] = 1;
                } else {
                    $array['remoteHost'][$info[0]]++;
                }

                if ($time >= 0 && $time < 6) {
                    $array['timeZone']["0-6"]++;
                }
                if ($time >= 6 && $time < 12) {
                    $array['timeZone']["6-12"]++;
                }
                if ($time >= 12 && $time < 18) {
                    $array['timeZone']["12-18"]++;
                }
                if ($time >= 18 && $time < 24) {
                    $array['timeZone']["18-24"]++;
                }

                //　print_r($arrayRemoteHost);
                // echo "<hr>";
            }
            fclose($fp);
        }
    }
    arsort($array['remoteHost']);
    print_r($array['remoteHost']);
    print_r($array['timeZone']);
    foreach ($array['remoteHost'] as $key => $value) {
        echo $key;
        echo $value;
    }
?>
