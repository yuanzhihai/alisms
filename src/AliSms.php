<?php
/**
 * Created by PHP@大海 [三十年河东三十年河西,莫欺少年穷.!]
 * User: yuanzhihai
 * Date: 2020/7/15
 * Time: 12:58 下午
 * Author: PHP@大海 <396751927@qq.com>
 *       江城子 . 程序员之歌
 *
 *  十年生死两茫茫，写程序，到天亮。
 *      千行代码，Bug何处藏。
 *  纵使上线又怎样，朝令改，夕断肠。
 *
 *  领导每天新想法，天天改，日日忙。
 *     相顾无言，惟有泪千行。
 *  每晚灯火阑珊处，夜难寐，加班狂。
 */

namespace yzh52521\sms;

use yzh52521\sms\helper\Helper;

class AliSms
{
    /**
     * 配置参数
     * @var array
     */
    protected $config;

    public function __construct($config = null)
    {
        if (!$config) {
            throw new \Exception('传入的配置不能为空');
        }
        //默认参数
        $_config      = [
            'accessKeyId'     => '',
            'accessKeySecret' => '',
        ];
        $this->config = array_merge($_config, $config);
    }

    /**
     * 发送短信
     * @param $array
     * @return bool|\stdClass
     */
    public function sendSms($array)
    {
        $params = [];
        $accessKeyId     = $this->config['accessKeyId'];
        $accessKeySecret = $this->config['accessKeySecret'];
        $params["PhoneNumbers"] = $array['mobile'];
        $params["SignName"] = $this->config['SignName'];
        $params["TemplateCode"] = $this->config['templateCode'];
        $params['TemplateParam']   = [
            "code" => $array['code']
        ];
        $params['OutId']           = "";
        $params['SmsUpExtendCode'] = "";
        // *** 需用户填写部分结束, 以下代码若无必要无需更改 ***
        if (!empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
            $params["TemplateParam"] = json_encode($params["TemplateParam"], JSON_UNESCAPED_UNICODE);
        }
        // 初始化SignatureHelper实例用于设置参数，签名以及发送请求
        $helper = new Helper();

        $content = $helper->request(
            $accessKeyId,
            $accessKeySecret,
            "dysmsapi.aliyuncs.com",
            array_merge($params, [
                "RegionId" => "cn-hangzhou",
                "Action"   => "SendSms",
                "Version"  => "2017-05-25",
            ])
        // fixme 选填: 启用https
        // ,true
        );

        return $content;
    }

    /**
     * 批量发送短信
     *
     * @param $mobile
     * @param $templateCode
     * @param $signNameJson
     * @param $templateParamJson
     * @return bool|\stdClass
     */
    public function sendBatchSms($mobile, $templateCode, $signNameJson, $templateParamJson)
    {
        $params = [];

        // fixme 必填: 请参阅 https://ak-console.aliyun.com/ 取得您的AK信息
        $accessKeyId     = $this->config['accessKeyId'];
        $accessKeySecret = $this->config['accessKeySecret'];

        // fixme 必填: 待发送手机号。支持JSON格式的批量调用，批量上限为100个手机号码,批量调用相对于单条调用及时性稍有延迟,验证码类型的短信推荐使用单条调用的方式
        $params["PhoneNumberJson"] = $mobile;

        // fixme 必填: 短信签名，支持不同的号码发送不同的短信签名，每个签名都应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
        $params["SignNameJson"] = $signNameJson;

        // fixme 必填: 短信模板Code，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
        $params["TemplateCode"] = $templateCode;

        // fixme 必填: 模板中的变量替换JSON串,如模板内容为"亲爱的${name},您的验证码为${code}"时,此处的值为
        // 友情提示:如果JSON中需要带换行符,请参照标准的JSON协议对换行符的要求,比如短信内容中包含\r\n的情况在JSON中需要表示成\\r\\n,否则会导致JSON在服务端解析失败
        $params["TemplateParamJson"] = $templateParamJson;

        // todo 可选: 上行短信扩展码, 扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段
        $params["SmsUpExtendCodeJson"] = '';

        // *** 需用户填写部分结束, 以下代码若无必要无需更改 ***
        $params["TemplateParamJson"] = json_encode($params["TemplateParamJson"], JSON_UNESCAPED_UNICODE);
        $params["SignNameJson"]      = json_encode($params["SignNameJson"], JSON_UNESCAPED_UNICODE);
        $params["PhoneNumberJson"]   = json_encode($params["PhoneNumberJson"], JSON_UNESCAPED_UNICODE);

        if (!empty($params["SmsUpExtendCodeJson"] && is_array($params["SmsUpExtendCodeJson"]))) {
            $params["SmsUpExtendCodeJson"] = json_encode($params["SmsUpExtendCodeJson"], JSON_UNESCAPED_UNICODE);
        }

        // 初始化SignatureHelper实例用于设置参数，签名以及发送请求
        $helper = new Helper();

        // 此处可能会抛出异常，注意catch
        return $helper->request(
            $accessKeyId,
            $accessKeySecret,
            "dysmsapi.aliyuncs.com",
            array_merge($params, [
                "RegionId" => "cn-hangzhou",
                "Action"   => "SendBatchSms",
                "Version"  => "2017-05-25",
            ])
        // fixme 选填: 启用https
        // ,true
        );
    }

    /**
     * 短信发送记录查询
     *
     * @param $mobile
     * @param $sendTime
     * @return mixed
     */
    public function querySendDetails($mobile,$sendTime)
    {
        $params = [];

        // *** 需用户填写部分 ***

        // fixme 必填: 请参阅 https://ak-console.aliyun.com/ 取得您的AK信息
        $accessKeyId     = $this->config['accessKeyId'];
        $accessKeySecret = $this->config['accessKeySecret'];

        // fixme 必填: 短信接收号码
        $params["PhoneNumber"] = $mobile;

        // fixme 必填: 短信发送日期，格式Ymd，支持近30天记录查询
        $params["SendDate"] = $sendTime;

        // fixme 必填: 分页大小
        $params["PageSize"] = 10;

        // fixme 必填: 当前页码
        $params["CurrentPage"] = 1;

        // fixme 可选: 设置发送短信流水号
        $params["BizId"] = "yourBizId";

        // *** 需用户填写部分结束, 以下代码若无必要无需更改 ***

        // 初始化SignatureHelper实例用于设置参数，签名以及发送请求
        $helper = new Helper();

        // 此处可能会抛出异常，注意catch
        return $helper->request(
            $accessKeyId,
            $accessKeySecret,
            "dysmsapi.aliyuncs.com",
            array_merge($params, [
                "RegionId" => "cn-hangzhou",
                "Action"   => "QuerySendDetails",
                "Version"  => "2017-05-25",
            ])
        // fixme 选填: 启用https
        // ,true
        );
    }

    /**
     * 获取详细错误信息
     * @param string $status
     * @return mixed
     */
    public static function getErrorMessage($status)
    {
        $message = [
            'isv.SMS_SIGNATURE_SCENE_ILLEGAL'   => '短信所使用签名场景非法',
            'isv.EXTEND_CODE_ERROR'             => '扩展码使用错误，相同的扩展码不可用于多个签名',
            'isv.DOMESTIC_NUMBER_NOT_SUPPORTED' => '国际/港澳台消息模板不支持发送境内号码',
            'isv.DENY_IP_RANGE'                 => '源IP地址所在的地区被禁用',
            'isv.DAY_LIMIT_CONTROL'             => '触发日发送限额',
            'isv.SMS_CONTENT_ILLEGAL'           => '签名禁止使用',
            'isp.RAM_PERMISSION_DENY'           => 'RAM权限DENY',
            'isv.OUT_OF_SERVICE'                => '业务停机',
            'isv.SMS_SIGNATURE_ILLEGAL'         => '短信签名不合法',
            'isv.MOBILE_NUMBER_ILLEGAL'         => '非法手机号',
            'isv.MOBILE_COUNT_OVER_LIMIT'       => '手机号码数量超过限',
            'isv.TEMPLATE_MISSING_PARAMETERS'   => '模版缺少变量',
            'isv.BUSINESS_LIMIT_CONTROL'        => '业务限流',
            'isv.INVALID_JSON_PARAM'            => 'JSON参数不合法，只接受字符串值',
            'isv.BLACK_KEY_CONTROL_LIMIT'       => '黑名单管控',
            'isv.PARAM_LENGTH_LIMIT'            => '参数超出长度限制',//每个变量的长度限制为1~20字
            'isv.PARAM_NOT_SUPPORT_URL'         => '不支持URL',
            'isv.AMOUNT_NOT_ENOUGH'             => '账户余额不足',
            'isv.TEMPLATE_PARAMS_ILLEGAL'       => '模版变量里包含非法关键字',
        ];
        if (isset ($message [$status])) {
            return $message [$status];
        }
        return $status;
    }
}
