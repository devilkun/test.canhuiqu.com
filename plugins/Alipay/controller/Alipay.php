<?php
namespace plugins\Alipay\controller;
use app\common\controller\Common;
require_once(dirname(dirname(__FILE__))."/sdk/alipay/AlipaySubmit.class.php");
require_once(dirname(dirname(__FILE__))."/sdk/alipay/AlipayNotify.class.php");

/**
 * 支付宝控制器
 * @package plugins\Barcode\controller
 */
class Alipay extends Common
{
    /**
     * 支付宝
     * 示例：
     * $data=array(
     *   'out_trade_no'=>time(),
     *   'price'=>1,
     *   'subject'=>'测试',
     *   'show_url'=>'http://baidu.com',
     *   'notify_url'=>'http://baidu.com',
     *   'return_url'=>'http://baidu.com',
     * );
     * plugin_action('Alipay/Alipay/alipay', $data);
     */
    public function alipay($order){
        $config = plugin_config('alipay');
        $data = array(
            "_input_charset" => $config['input_charset'], // 编码格式
            "logistics_fee" => "0.00", // 物流费用
            "logistics_payment" => "SELLER_PAY", // 物流支付方式SELLER_PAY（卖家承担运费）、BUYER_PAY（买家承担运费）
            "logistics_type" => "EXPRESS", // 物流类型EXPRESS（快递）、POST（平邮）、EMS（EMS）
            "notify_url" => $order['notify_url'], // 异步接收支付状态通知的链接
            "out_trade_no" => $order['out_trade_no'], // 订单号
            "partner" => $config['partner'], // partner 从支付宝商户版个人中心获取
            "payment_type" => "1", // 支付类型对应请求时的 payment_type 参数原样返回。固定设置为1即可
            "price" => $order['price'], // 订单价格单位为元
            "quantity" => "1", // price、quantity 能代替 total_fee。 即存在 total_fee就不能存在 price 和 quantity;存在 price、quantity 就不能存在 total_fee。 （没绕明白；好吧；那无视这个参数即可）
            "receive_address" => '1', // 收货人地址 即时到账方式无视此参数即可
            "receive_mobile" => '1', // 收货人手机号码 即时到账方式无视即可
            "receive_name" => '1', // 收货人姓名 即时到账方式无视即可
            "receive_zip" => '1', // 收货人邮编 即时到账方式无视即可
            "return_url" => $order['return_url'], // 页面跳转 同步通知 页面路径 支付宝处理完请求后当前页面自 动跳转到商户网站里指定页面的 http 路径。
            "seller_email" => $config['seller_email'], // email 从支付宝商户版个人中心获取
            "service" => "create_direct_pay_by_user", // 接口名称 固定设置为create_direct_pay_by_user
            "show_url" => $order['show_url'], // 商品展示网址收银台页面上商品展示的超链接。
            "subject" => $order['subject'], // 商品名称商品的标题/交易标题/订单标 题/订单关键字等
        );
        $alipay = new \AlipaySubmit($config);
        $new = $alipay->buildRequestPara($data);
        echo $alipay->buildRequestForm($new, 'get');
    }

    public function alipay_return()
    {
        // 引入支付宝
        $config = plugin_config('alipay');
        $notify = new \AlipayNotify($config);
        // 验证支付数据
        $status = $notify->verifyReturn();
        if($status){
            // 下面写验证通过的逻辑 比如说更改订单状态等等 $_GET['out_trade_no'] 为订单号；
            $this->success('支付成功');
        }else{
            $this->success('支付失败');
        }
    }
    public function alipay_notify()
    {
        // 引入支付宝
        $config = plugin_config('alipay');
        $alipayNotify = new \AlipayNotify($config);
        // 验证支付数据
        $verify_result = $alipayNotify->verifyNotify();
        if($verify_result) {
            echo "success";
            // 下面写验证通过的逻辑 比如说更改订单状态等等 $_POST['out_trade_no'] 为订单号；
        }else {
            echo "success";
        }
    }

}