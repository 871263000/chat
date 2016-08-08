<?php 
/**
* 实时猫 API 
*/
class ssmApi 
{
    private $_curl = null;

    private $key;

    private $Secret;

    private $header;
    /**
     * $key是项目的API Key，
     * $Secret是这个API Key的Secret。
     */
    function __construct( $key, $Secret )
    {
         $this->key = $key;
         $this->Secret = $Secret;
    }
    // get  token
    public  function getToken( $url, $cData ) 
    {
        $res = $this->_curl->post( $url, $cData );
        return $res;
        
    }

    // 创建一个 会话
    public function handleSession ( $meThod='', $url, $cData =array() ) 
    {
        if ( $meThod == 'post' ) {
            $res = $this->_curl->post( $url, $cData );
        } elseif ( $meThod == 'get' ) {
            $res = $this->_curl->get( $url, $cData );
        }
        return $res;
    }
    // 设置 curl  实例
    public function setCurl( $curl ) 
    {
        $this->_curl = $curl;
        // 设置  curl  header
        $this->_curl->headers = $this->header = array( 'X-RTCAT-APIKEY'=> $this->key, 'X-RTCAT-SECRET'=>$this->Secret );
    }
}

 ?>