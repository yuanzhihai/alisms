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
    'code' => '1234'//短信内容
];
$sms = new AliSms($config);
$info = $sms->sendSms($params);
```

