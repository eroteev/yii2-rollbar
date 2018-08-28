<?php

namespace eroteev\rollbar\log;

use Rollbar\Payload\Level;
use Rollbar\Rollbar;
use yii\log\Logger;

/**
 * RollbarTarget sends selected log messages to Rollbar.
 *
 * ```php
 * 'components' => [
 *     'log' => [
 *          'targets' => [
 *              [
 *                  'class' => 'eroteev\rollbar\log\RollbarTarget',
 *                  'levels' => ['error', 'warning'],
 *                  'categories' => ['application'],
 *              ],
 *          ],
 *     ],
 * ],
 * ```
 */
class RollbarTarget extends \yii\log\Target
{
    /**
     * @var string
     */
    protected $requestId;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->requestId = uniqid(rand(), true);
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function export()
    {
        foreach ($this->messages as $message) {
            list($message, $level, $category, $timestamp, $traces, $memoryUsage) = $message;

            Rollbar::log($this->getSeverityLevel($level), $message, [
                'category' => $category,
                'request_id' => $this->requestId,
                'timestamp' => $timestamp,
                'memory_usage' => $memoryUsage,
            ]);
        }
    }

    /**
     * @param integer $level Severity level as defined in \yii\log\Logger
     * @return string Returns severity level that could be recognized by Rollbar
     */
    protected static function getSeverityLevel($level)
    {
        if (in_array($level,
            [Logger::LEVEL_PROFILE, Logger::LEVEL_PROFILE_BEGIN, Logger::LEVEL_PROFILE_END, Logger::LEVEL_TRACE])) {
            return Level::DEBUG;
        }
        return Logger::getLevelName($level);
    }
}
