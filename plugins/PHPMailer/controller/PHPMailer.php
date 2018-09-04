<?php
/**
 * Created by PhpStorm.
 * User: Mrs-Y
 * Date: 2017/06/27 0027
 * Time: 11:53
 */

namespace plugins\PHPMailer\controller;


use app\common\controller\Common;
use think\Exception;
use think\Validate;

class PHPMailer extends Common
{
    protected static $config; //配置

    protected function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
    }

    /**
     * 检测配置
     * @param array $data
     * @throws Exception
     */
    protected static function check_config($data=[]){
        $validate = new Validate([
            ['host','require','邮箱服务地址参数缺省'],
            ['port','require','邮箱服务端口参数缺省'],
            ['password','require','邮箱密码参数缺省'],
            ['form_user','require','发件人参数缺省'],
        ]);
        if (!$validate->check($data)) {
            throw new Exception($validate->getError(),400);
        }
    }

    /**
     * 发送邮件
     * @param string $title     邮件主题
     * @param string $content   邮件内容
     * @param string $to_user   接收人
     * @param string $alias     接收人别名
     * @return int
     */
    public static function send_email($title = '',$content = '',$to_user = '' ,$alias = ''){
        self::$config = plugin_config('PHPMailer');
        try{
            self::check_config(self::$config);//检测配置
        }catch (Exception $e){
            return $e->getCode(); //配置缺省返回400  发送成功返回1  失败返回0
        }

        $mail = new \PHPMailer();

        //$mail->SMTPDebug = 3;                               // Enable verbose debug output

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = self::$config['host'];                 // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = self::$config['form_user'];         // SMTP username
        $mail->Password = self::$config['password'];          // SMTP password
        $mail->SMTPSecure = self::$config['smtp_secure'];//'tls';// Enable TLS encryption, `ssl` also accepted
        $mail->Port = self::$config['port'];                  // TCP port to connect to

        $mail->setFrom(self::$config['form_user'], self::$config['form_user_alias']);
        $mail->addAddress($to_user, $alias);     // Add a recipient
        //$mail->addAddress('ellen@example.com');               // Name is optional
        $mail->addReplyTo(self::$config['form_user'], self::$config['form_user_alias']);
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');

        //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
        $mail->isHTML(true);                           // Set email format to HTML

        $mail->Subject = $title;
        $mail->Body    = $content;
        //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        if(!$mail->send()) {  //为统一swiftmailer的返回值，因此成功返回1  错误返回0
            //echo 'Message could not be sent.';
            //echo 'Mailer Error: ' . $mail->ErrorInfo; //调试请打开此项查看错误信息
            return 0;
        } else {
            //echo 'Message has been sent';
            return 1;
        }
    }

}