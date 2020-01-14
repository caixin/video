<?php

if (!function_exists("encode_search_params")) {
    function encode_search_params($params, $zero=[])
    {
        foreach ($params as $key => $value) {
            if ($key == '_token' || $value == '' || $value == 'all' || (in_array($key, $zero) && $value == '0')) {
                unset($params[$key]);
            } else {
                if (is_array($value)) {
                    $value = implode('|,|', $value).'array';
                }
                $params[$key] = urlencode($value);
            }
        }

        return $params;
    }
}

if (!function_exists("decode_search_params")) {
    function decode_search_params($params)
    {
        foreach ($params as $key => $value) {
            $value = urldecode($value);
            if (strpos($value, 'array') !== false) {
                $value = explode('|,|', substr($value, 0, strpos($value, 'array')));
            }
            $params[$key] = $value;
        }

        return $params;
    }
}

if (!function_exists("get_search_uri")) {
    function get_search_uri($params, $uri, $zero=[])
    {
        $params = encode_search_params($params, $zero);
        $arr = [];
        foreach ($params as $k => $v) {
            $arr[] = "$k=$v";
        }
        return $uri.'?'.implode('&', $arr);
    }
}

if (!function_exists("get_page")) {
    function get_page($params)
    {
        return isset($params['page']) ? (int) $params['page'] : 1;
    }
}

if (!function_exists("get_order")) {
    function get_order($params, $default_order = [])
    {
        $order = $default_order;

        isset($params['asc']) && $order = [$params['asc'], 'asc'];
        isset($params['desc']) && $order = [$params['desc'], 'desc'];

        return $order;
    }
}

if (!function_exists("param_process")) {
    function param_process($params, $default_order = [])
    {
        $result['page']  = $params['page'] ?? 1;
        $result['order'] = get_order($params, $default_order);

        unset($params['page']);
        $uri = [];
        foreach ($params as $k => $v) {
            $uri[] = "$k=$v";
        }
        $result['params_uri'] = implode('&', $uri);

        unset($params['asc']);
        unset($params['desc']);

        $result['search'] = decode_search_params($params);

        return $result;
    }
}

if (!function_exists("sort_title")) {
    function sort_title($key, $name, $base_uri, $order, $where = [])
    {
        $where = encode_search_params($where);
        $uri = [];
        foreach ($where as $k => $v) {
            $uri[] = "$k=$v";
        }
        $uri[] = $order[1] === 'asc' ? 'desc='.$key : 'asc='.$key;
        $uri_str = '?'.implode('&', $uri);

        $class = ($order[0] === $key) ? 'sort '.$order[1] : 'sort';

        return '<a class="'.$class.'" href="'.route($base_uri).$uri_str.'">'.$name.'</a>';
    }
}

if (!function_exists("lists_message")) {
    function lists_message($type='success')
    {
        if (session('message')) {
            return '<div id="message" class="alert alert-'.$type.' alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-check"></i> 讯息!</h4>
                '.session('message').'
            </div>
            <script>
                setTimeout(function() { $(\'#message\').slideUp(); }, 3000);
                $(\'#message .close\').click(function() { $(\'#message\').slideUp(); });
            </script>';
        }
        return '';
    }
}

if (! function_exists('randPwd')) {
    /**
     * 隨機產生密碼
     * @param integer $pwd_len 密碼長度
     * @param integer $type
     * @return string
     */
    function randPwd($pwd_len, $type=0)
    {
        $password = '';
        if (!in_array($type, [0,1,2,3])) {
            return '';
        }

        // remove o,0,1,l
        if ($type == 0) {
            $word = 'abcdefghijkmnpqrstuvwxyz-ABCDEFGHIJKLMNPQRSTUVWXYZ_23456789';
        }
        if ($type == 1) {
            $word = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        if ($type == 2) {
            $word = '123456789';
        }
        if ($type == 3) {
            $word = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789';
        }

        $len = strlen($word);

        for ($i = 0; $i < $pwd_len; $i++) {
            $password .= $word[rand(1, 99999) % $len];
        }

        return $password;
    }
}

if (! function_exists('bytes_to_kbmbgb')) {
    function bytes_to_kbmbgb($filesize)
    {
        if ($filesize<1048576) {
            return number_format($filesize/1024, 1) . " KB";
        }
        if ($filesize>=1048576 && $filesize<1073741824) {
            return number_format($filesize/1048576, 1) . " MB";
        }
        if ($filesize>=1073741824) {
            return number_format($filesize/1073741824, 1) . " GB";
        }
    }
}

if (! function_exists('getVideoApi')) {
    /**
     * 取得IQQTV API資料
     *
     * @param string $method API接口
     * @return array
     */
    function getVideoApi($method)
    {
        $domain = config('video.domain');
        $au     = config('video.au');
        $apiKey = config('video.key');
        $e      = time() + 2000;
        $token  = md5("$apiKey/$method?au=$au&e=$e");

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, "$domain/$method?au=$au&e=$e&token=$token");
        $output = curl_exec($ch);
        curl_close($ch);

        return json_decode($output, true);
    }
}

if (! function_exists('sendSMS')) {
    /**
     * 發送認證簡訊
     *
     * @param string $mobile 手機號碼
     * @param string $code   驗證碼
     * @param string $tpl_id 模板ID
     * @return bool
     */
    function sendSMS($mobile, $code, $tpl_id='161064')
    {
        $url = config('sms.url');
        $key = config('sms.key');

        $content = juheCurl($url, [
            'key'       => $key,          //您申请的APPKEY
            'mobile'    => $mobile,       //接受短信的用户手机号码
            'tpl_id'    => $tpl_id,       //您申请的短信模板ID，根据实际情况修改
            'tpl_value' => "#code#=$code" //您设置的模板变量，根据实际情况修改
        ]);

        $result = json_decode($content, true);
        if ($result) {
            if ($result['error_code'] == 0) {
                return true;
            } else {
                \Log::error($content);
                return false;
            }
        }
    }
}

if (! function_exists('juheCurl')) {
    /**
     * 請求接口返回內容
     * @param string $url [請求的URL地址]
     * @param string $params [請求的參數]
     * @param int $ispost [是否採用POST形式]
     * @return string
     */
    function juheCurl($url, $params=[], $ispost=0)
    {
        $httpInfo = array();
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'JuheData');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        if ($ispost) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
            curl_setopt($ch, CURLOPT_URL, $url);
        } else {
            if ($params) {
                curl_setopt($ch, CURLOPT_URL, $url.'?'.http_build_query($params));
            } else {
                curl_setopt($ch, CURLOPT_URL, $url);
            }
        }
        $response = curl_exec($ch);
        if ($response === false) {
            //echo "cURL Error: " . curl_error($ch);
            return false;
        }
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $httpInfo = array_merge($httpInfo, curl_getinfo($ch));
        curl_close($ch);
        return $response;
    }
}
