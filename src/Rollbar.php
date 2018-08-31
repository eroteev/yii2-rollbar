<?php

namespace eroteev\rollbar;

use Rollbar\Rollbar as BaseRollbar;
use Yii;
use yii\base\BaseObject;
use yii\base\BootstrapInterface;

/**
 * Rollbar class is used as a Yii2 wrapper for the Rollbar library.
 *
 * Basic usage:
 *
 * ```php
 * 'components' => [
 *     'rollbar' => [
 *          'class' => 'eroteev\rollbar\Rollbar',
 *          'config' => [
 *              'access_token' => 'POST_SERVER_ITEM_ACCESS_TOKEN',
 *              // ...
 *          ]
 *     ],
 * ],
 * ```
 */
class Rollbar extends BaseObject implements BootstrapInterface
{
    /**
     * @var array Associative array of supported configuration options. For more information
     * check the documentation here: https://docs.rollbar.com/docs/php
     */
    public $config = [];

    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        $this->setDefaults();

        if (empty($this->config['enabled'])) {
            return;
        }

        BaseRollbar::init($this->config, false, false, false);
    }

    protected function setDefaults()
    {
        if (!isset($this->config['enabled'])) {
            $this->config['enabled'] = true;
        }

        if (!isset($this->config['root'])) {
            $this->config['root'] = Yii::getAlias('@app');
        }

        if (!isset($this->config['scrub_fields'])) {
            $this->config['scrub_fields'] = ['passwd', 'password', 'secret', 'auth_token', '_csrf'];
        }
    }

    /**
     * Send log to Rollbar
     *
     * @param string $level Severity level as defined in Rollbar
     * @param mixed $toLog The thing to be logged (message, exception, error)
     * @param array $extra Extra params to be sent along with the payload
     * @param bool $isUncaught It will be set to true if the error was caught by the global error handler
     */
    public function log($level, $toLog, $extra = [], $isUncaught = false)
    {
        BaseRollbar::log($level, $toLog, $extra, $isUncaught);
    }
}
