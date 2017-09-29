<?php
    $logfile = 'access.log';
    /* 30分前から今まで */
    $start = strtotime(date("Y-m-d H:i:00", strtotime("-30 minute")));
    $end = strtotime(date("Y-m-d H:i:00"));

    if (($fp = fopen($logfile, "r"))) {
        while (!feof($fp)) {
            //$info = explode("\t", preg_replace('/^(.*) (.*) (.*) \[(.*)\] "(.*)" (.*) (.*) "(.*)" "(.*)"$/', "\1\t\2\t\3\t\4\t\5\t\6\t\7\t\8\t\9", fgets($fp)));
			$info = explode(",", preg_replace('/^(.+?)\s(.+?)\s(.+?)\s\[(.+)\]\s\"(.+?)\"\s(.+?)\s(.+?)\s\"(.+?)\"(.+)/', "$1,$2,$3,$4,$5,$6,$7,$8,$9", fgets($fp)));
            $access = strtotime($info[3]);
			var_dump($info);
			echo "<br>";
			echo $access."<br>";
			echo $start."<br>";
			echo $end."<br>";
            if ($access > $start && $access < $end) {
                //echo $info;
            }
			//break;
			?><hr><?php
        }
        fclose($fp);
    }
?>
