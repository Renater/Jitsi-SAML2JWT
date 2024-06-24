<?php


class RESTCaller {
    private string $baseURL;

    public function __construct($url) {
        $this->baseURL = $url;
    }

    /**
     * @param string $method
     * @param string $path
     * @param array $args
     * @param mixed $content
     * @param array $options
     *
     * @return bool|string|StdClass
     * @throws ClientError
     * @throws MissingParameter
     * @throws NotAllowed
     */
    private function call(string $method, string $path, array $args = array(), $content = '', array $options = []) {

  
        $contentType = 'application/json';
        if (array_key_exists('Content-Type', $options))
            $contentType = $options['Content-Type'];

        if ($content) {
            $input = ($contentType == 'application/json') ? json_encode($content) : $content;
        }

        $url = $this->baseURL . $path;
        if (!empty($args)) {
            $url .= '?' . implode('&', $this->flatten($args));
        }
        $h = curl_init();
        curl_reset($h);
        if ($content){
            curl_setopt($h, CURLOPT_POSTFIELDS, $input);
        }

        $headers = [
            'Accept: application/json',
            'Content-Type: ' . $contentType,
        ];
        if (array_key_exists('headers', $options)) {
            $headers = array_merge($headers, $options['headers']);
        }

        $curlOptions = [
            CURLOPT_CONNECTTIMEOUT => 1,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_VERBOSE => false,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_URL => $url,
        ];

        switch ($method) {
            case 'get' :
                break;
            case 'post' :
                curl_setopt($h, CURLOPT_POST, true);
                break;

            case 'put' :
                curl_setopt($h, CURLOPT_CUSTOMREQUEST, 'PUT');
                break;

            case 'delete':
                curl_setopt($h, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;

            default:
                return "";
        }

        curl_setopt_array($h, $curlOptions);
        $response = curl_exec($h);
        $error = curl_error($h);
        $code = (int)curl_getinfo($h, CURLINFO_HTTP_CODE);
        curl_close($h);


        if ($method != 'post') {
            return $response;
        }

        $r = new StdClass();
        $r->location = $headers['Location'];
        $r->created = $response;
        return $r;
    }


    /**
     * Flatten arguments (recursive)
     *
     * @param $a array multi-dimensional array
     * @param string|null $p string parent key stack
     *
     * @return array single dimension array
     */
    private function flatten(array $a, string $p = null) {
        $o = [];
        ksort($a);
        foreach ($a as $k => $v) {
            if (is_array($v)) {
                foreach ($this->flatten($v, $p ? $p . '[' . $k . ']' : $k) as $s) $o[] = $s;
            } else $o[] = ($p ? $p . '[' . $k . ']' : $k) . '=' . $v;
        }
        return $o;
    }


    /**
     * Get caller
     *
     * @throws ClientError
     * @throws MissingParameter
     * @throws NotAllowed
     */
    public function get($path, array $args = [], array $options = []) {
        return $this->call('get', $path, $args, '', $options);
    }
}