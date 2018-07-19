<?php
/**
 * Author: Jason
 */
namespace Phalavel\Wechat;

use App\Foundation\ServiceProvider;
use Carbon\Carbon;
use EasyWeChat\Foundation\Application as EasyWeChatApplication;

class WechatServiceProvider extends ServiceProvider
{
    /**
     * Register easy wechat application services.
     *
     * @return void
     */
    protected function register()
    {
        $this->setConfig();
        $this->registerEasyWechat();
    }

    protected function setConfig()
    {
        $config_file = CONFIG_PATH . DIRECTORY_SEPARATOR . 'wechat.php';
        if (!file_exists($config_file)) {
            throw new \Exception("Do not have wechat config");
        }
        $config = new \Phalcon\Config($config_file);
        $this->config->merge($config);
    }

    protected function registerEasyWechat()
    {
        $config = $this->config;

        $this->di->setShared('wechat', function () use ($config) {
            $period = '';
            if ($config->log->period == 'daily') {
                $period = Carbon::today()->format('-Y-m-d');
            }

            $options = [
                'debug'   => $config->wechat->debug,
                'app_id'  => $config->wechat->appid,
                'secret'  => $config->wechat->app_secret,
                'token'   => $config->wechat->token,
                'log'     => [
                    'level' => 'debug',
                    'file'  => $config->log->path . "wechat$period.log",
                ],
                'payment' => [
                    'merchant_id' => $config->wechat->merchant_id,
                    'key'         => $config->wechat->mer_key,
                    'cert_path'   => $config->wechat->cert_path, // XXX: 绝对路径！！！！
                    'key_path'    => $config->wechat->key_path, // XXX: 绝对路径！！！！
                ],
            ];
            return new EasyWeChatApplication($options);
        });
    }
}
