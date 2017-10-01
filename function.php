<?php
    /**
     *
     */
    class LogAnalyser
    {

        private $array;

        public function __construct()
        {
            $this->array = array(
                'remoteHost' => array(),
                'timeZone' => array("0-6" => 0, "6-12" => 0, "12-18" => 0, "18-24" => 0)
            );
        }

        public function analysis($begin = '', $end = '')
        {
            $logfile = './logs/access.log';
            $refine = ($begin != '' && $end != '');
            // echo $refine ? "true" : "false";
            // $this->array = array(
            //     'remoteHost' => array(),
            //     'timeZone' => array("0-6" => 0, "6-12" => 0, "12-18" => 0, "18-24" => 0)
            // );
            // echo "begin = ".$begin."<br>";
            // echo "end = ".$end."<br>";
            // echo "refine = ";
            // echo $refine ? "true" : "false";
            // echo "<br>";
            $begin = strtotime($begin);
            $end = strtotime($end);
            // echo "begin = ".$begin."<br>";
            // echo "end = ".$end."<br>";

            if ($handle = opendir('./logs/')) {
                while (false !== ($file = readdir($handle))) {
                    if ($file == '.' || $file == '..') {
                        // 「.」「..」は無視
                        continue;
                    }
                    if (preg_match('/.(log)$/', $file)) {
                        $files[] = $file;
                        // echo $file."<br>";
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
                        $timeStamp = strtotime($info[3]);
                        // var_dump($info);
                        // echo "<br>";
                        // echo $access;
                        $time = intval(date("H", strtotime($info[3])));
                        // echo "<br>".$time;

                        if ($refine) {
                            if ($timeStamp > $begin && $timeStamp < $end) {
                                $this->countRemoteHostAndTimeZone($info[0], $time);
                            }
                        } else {
                            $this->countRemoteHostAndTimeZone($info[0], $time);
                        }


                        //　print_r($arrayRemoteHost);
                        // echo "<hr>";
                    }
                    fclose($fp);
                }
            }
            arsort($this->array['remoteHost']);
            // print_r($array['remoteHost']);
            // print_r($array['timeZone']);
            // foreach ($array['remoteHost'] as $key => $value) {
            //     echo $key;
            //     echo $value;
            // }
            return $this->array;
        }

        private function countRemoteHostAndTimeZone($remoteHost, $time)
        {
            if (empty($this->array['remoteHost'][$remoteHost])) {
                $this->array['remoteHost'][$remoteHost] = 1;
            } else {
                $this->array['remoteHost'][$remoteHost]++;
            }

            if ($time >= 0 && $time < 6) {
                $this->array['timeZone']["0-6"]++;
            }
            if ($time >= 6 && $time < 12) {
                $this->array['timeZone']["6-12"]++;
            }
            if ($time >= 12 && $time < 18) {
                $this->array['timeZone']["12-18"]++;
            }
            if ($time >= 18 && $time < 24) {
                $this->array['timeZone']["18-24"]++;
            }
        }
    }


?>
