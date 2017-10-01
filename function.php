<?php
    /**
     * [LogAnalyzer description]
     *
     * Apacheのログ分析を行うクラス
     *
     */
    class LogAnalyzer
    {

        private $array;

        /**
         * [__construct description]
         */
        public function __construct()
        {
            $this->array = array(
                'remoteHost' => array(),
                'timeZone' => array("0-6" => 0, "6-12" => 0, "12-18" => 0, "18-24" => 0)
            );
        }

        /**
         * [analyze description]
         *
         * 期間指定時は絞り込み、指定しなければすべてのログから分析
         *
         * @param  string $begin [絞り込み期間のスタート]
         * @param  string $end   [絞り込み期間の終わり]
         * @return array  $array [分析結果（'remoteHost','timeZone'）]
         * @see    LogAnalyzer::countRemoteHostAndTimeZone
         */
        public function analyze($begin = '', $end = '')
        {
            // $logfile = './logs/access.log';
            $refine = ($begin != '' && $end != '');
            $begin = strtotime($begin);
            $end = strtotime($end);

            if ($handle = opendir('./logs/')) {
                while (false !== ($file = readdir($handle))) {
                    if ($file == '.' || $file == '..') {
                        continue;
                    }
                    if (preg_match('/.(log)$/', $file)) {
                        $files[] = $file;
                    }
                }
                closedir($handle);
            } else {
                echo "ディレクトリのオープンに失敗<br>";
            }

            foreach ($files as $value) {
                if (($fp = fopen('logs/'.$value, "r"))) {
                    //while (!feof($fp)) {
                    //while (($c = fgetc($fp)) != 'EOF'){
                    while (strlen($str = fgets($fp)) != 0) {
                        $info = explode(",", preg_replace('/^(.+?)\s(.+?)\s(.+?)\s\[(.+)\]\s\"(.+?)\"\s(.+?)\s(.+?)\s\"(.+?)\"(.+)/', "$1,$2,$3,$4,$5,$6,$7,$8,$9", $str));
                        $timeStamp = strtotime($info[3]);
                        $time = intval(date("H", strtotime($info[3])));

                        if ($refine) {
                            if ($timeStamp > $begin && $timeStamp < $end) {
                                $this->countRemoteHostAndTimeZone($info[0], $time);
                            }
                        } else {
                            $this->countRemoteHostAndTimeZone($info[0], $time);
                        }
                    }
                    fclose($fp);
                }
            }
            arsort($this->array['remoteHost']);
            return $this->array;
        }

        /**
         * [countRemoteHostAndTimeZone description]
         *
         * リモートホスト別、時間帯別にアクセス件数をカウント
         *
         * @param  string $remoteHost [リモートホスト]
         * @param  int    $time       [時間（hour）]
         * @return none
         */
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
