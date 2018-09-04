<?php
// +----------------------------------------------------------------------
// | 海豚PHP框架 [ DolphinPHP ]
// +----------------------------------------------------------------------
// | 版权所有 2016~2017 河源市卓锐科技有限公司 [ http://www.zrthink.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://dolphinphp.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
/**
 * 插件配置信息
 */
return [
    ['text', 'appKey', '应用Key', '请到高德后台查询应用的AppKey并填入此处', ''],
    ['radio', 'isEncrypt', '是否启用数字签名', '如果启用了数字签名，请启用本项并填写加密Kye', [0 => '关闭', 1 => '开启'], 0],
    ['text', 'encryptKey', '签名私钥', '在应用的右侧点击设置，即可看到数字签名私钥', '']
];