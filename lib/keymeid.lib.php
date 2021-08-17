<?php
//==============================================================================
// keymedi newral 연동 라이브러리 모음
//==============================================================================

/*
 $ku = new keymedi_curl();
 $ku->set_url("http://api.keymedidev.com:82/api/auth/login");
 $ku->set_post_data(array('uid'=>'test', 'password'=>'test1234'));
 $ku->set_header_data(array('accept: application/json','Content-Type: multipart/form-data','X-CSRF-TOKEN:'));
 $ku->set_curlopt_proxy("172.10.0.0:80");
 $t = $ku->exec();
*/

class keymedi_curl
{

//    private $url = "http://ec2-3-35-50-93.ap-northeast-2.compute.amazonaws.com";
    private $url = "http://api.keymedidev.com:82";
    private $method = "";
    private $post_data = array();
    private $header_data = array();
    private $access_token = "";
    private $curlopt_proxy = "172.17.0.1:82";

    public $result;

    function __construct()
    {

    }

    function set_url($url)
    {
        $this->url = $url;
    }

    function set_method($method)
    {
        $this->method = $method;
    }

    function set_post_data($post_data)
    {
        $this->post_data = $post_data;
    }

    function set_header_data($header_data)
    {
        $this->header_data = $header_data;
    }

    function set_access_token($access_token)
    {
        $this->access_token = $access_token;
    }

    function set_curlopt_proxy($curlopt_proxy)
    {
        $this->curlopt_proxy = $curlopt_proxy;
    }

    function exec()
    {
        if ($this->access_token) {
            array_push($this->header_data, "Authorization: Bearer " . $this->access_token);
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->url.$this->method);     //URL 지정하기
        curl_setopt($ch, CURLOPT_POST, 1);                           //0이 default 값이며 POST 통신을 위해 1로 설정해야 함
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->post_data);            //POST로 보낼 데이터 지정하기
//        curl_setopt($ch, CURLOPT_POSTFIELDSIZE, 0);                  //이 값을 0으로 해야 알아서 &post_data 크기를 측정하는듯
        curl_setopt($ch, CURLOPT_HEADER, false);                     //헤더 정보를 보내도록 함(*필수)
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->header_data);          //header 지정하기
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);                 // 이 옵션이 0으로 지정되면 curl_exec의 결과값을 브라우저에 바로 보여줌. 이 값을 1로 하면 결과값을 return하게 되어 변수에 저장 가능(테스트 시 기본값은 1인듯?)

        if ($this->curlopt_proxy) {
            curl_setopt($ch, CURLOPT_PROXY, $this->curlopt_proxy);
        }
//        unset($this->result);
        $this->result = curl_exec($ch);
    }

    function keymedi_login($uid, $password)
    {
        $this->set_method("/api/auth/login");
        $this->set_post_data(array('uid'=>$uid, 'password'=>$password));
        $this->set_header_data(array('accept: application/json','Content-Type: multipart/form-data','X-CSRF-TOKEN:'));

        $this->exec();
    }

    function get_access_token()
    {
        $json_data = json_decode($this->result, true);
        $this->set_access_token($json_data['data']['token']['access_token']);

        return $this->access_token;
    }

    function get_member_info()
    {
        $this->set_method("/api/member/myInfo");
        $this->set_header_data(array('accept: application/json','Content-Type: multipart/form-data','X-CSRF-TOKEN:'));

        $this->exec();

        $json_data = json_decode($this->result, true);

        if ($json_data['code'] != 0) {
            return $json_data['data'] = null;
        }

        return $json_data['data'];
    }

    function get_member_point()
    {
        $this->set_method("/api/channel/getPointBalance");
        $this->set_header_data(array('accept: application/json','Content-Type: multipart/form-data','X-CSRF-TOKEN:'));

        $this->exec();

        $json_data = json_decode($this->result, true);

        return $json_data['data'];
    }

}
