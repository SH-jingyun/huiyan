<?php
namespace Core;

Class Wxpay
{
    protected $retryCount = 0;
    protected $appId = 0;
    protected $id = 0;
    protected $key = 0;
    protected $callback = 'http://jytest.darkness.ltd:8006/user/wxpay';

    public function __construct()
    {
        if (ENV_PRODUCTION) {
            $this->callback = 'https://hy.stepcounter.cn:4420/user/wxpay';
        }
        $this->appId = 'wx0d37ff9a3e588e06';//申请商户号的appid或商户号绑定的appid
        $this->id = '1578227681';//微信支付分配的商户号
        $this->key = '23a365d18f89691ad645049f67d8064e';
    }

    public function unifiedorder ($amount, $orderNumber = '') {
        $createList = '123456789ABCDEFGHIJKLMNPQRSTUVWXYZ';
//        $createList = '0123456789';
        $nonceStr = '';
        for ($i = 0; $i < 32; $i++) {
            $nonceStr .= $createList{rand(0, 33)};
        }

        $partnerTradeNo = $orderNumber ?: ('HUIYANTANTAN' . time() . substr($nonceStr, 1, 5));

        //todo 回调地址
        $data = array( 'appid' => $this->appId, 'attach' => '慧眼探探VIP', 'body' => '慧眼探探-VIP充值', 'mch_id' => $this->id, 'nonce_str' => $nonceStr, 'notify_url' => $this->callback, 'out_trade_no' => $partnerTradeNo, 'spbill_create_ip' => '101.10.10.10', 'total_fee' => $amount, 'trade_type' => 'APP');

        $strArr = array();
        foreach ($data as $key => $value) {
            $strArr[] = $key . '=' . $value;
        }
        $strArr[] = 'key=' . $this->key;
        $data['sign'] = strtoupper(md5(implode('&', $strArr)));
        $xml = '<xml>';
        foreach ($data as $key => $value) {
            $xml .= '<' . $key . '>' . $value . '</' . $key . '>';
        }
        $xml .= '</xml>';
//var_dump($xml);

        $curl = curl_init();//初始一个curl会话
        curl_setopt($curl, CURLOPT_URL, "https://api.mch.weixin.qq.com/pay/unifiedorder");//设置url
//        curl_setopt($curl, CURLOPT_POST, true);//设置发送方式：post
        curl_setopt($curl, CURLOPT_POSTFIELDS, $xml); //设置发送数据
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);//TRUE 将curl_exec()获取的信息以字符串返回，而不是直接输出
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);

        $return_xml = curl_exec($curl);//执行cURL会话 ( 返回的数据为xml )
        curl_close($curl);//关闭cURL资源，并且释放系统资源
//        var_dump($return_xml);

        if ($return_xml) {
            libxml_disable_entity_loader(true);//禁止引用外部xml实体
            $value_array = json_decode(json_encode(simplexml_load_string($return_xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);//先把xml转换为simplexml对象，再把simplexml对象转换成 json，再将 json 转换成数组。
//            var_dump($value_array);
            if (isset($value_array['err_code'])) {
                return $value_array['err_code'] . ':' . ($value_array['err_code_des'] ?? '');
            } else {
                return array('orderString' => $value_array['prepay_id'], 'orderNo' => $value_array['prepay_id']);
            }
        } else {
            return '请求微信下单接口失败';
        }
    }
}