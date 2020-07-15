# AliSms

阿里新版短信

```
$ composer require yzh52521/alisms
```

```php
//引入
use yzh52521\AliSms;
$config = [
    'accessKeyId'     => '',
    'accessKeySecret' => '',
    'TemplateCode'    => '',
    'SignName'        => '',
];
$params = [
    'mobile' => '手机号',
    'code' => '1234'//验证码
];
$sms = new AliSms($config);
//发送短信
$info = $sms->sendSms($params);

$mobile = [
   '132xxxxxxxx',
   '135xxxxxxxx'
];
$sign   = ['签名', '签名'];

$params=[
//模板变量名 => 模板变量值
    ['name' => '张三', 'product' => '产品'],
    ['name' => '李四', 'product' => '产品'],
];
//批量发送
$request = $sms->sendBatchSms($mobile,'SMS_111111',$sign,$params);
```

