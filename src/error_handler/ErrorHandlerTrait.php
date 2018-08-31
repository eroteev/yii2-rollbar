<?php

namespace eroteev\rollbar\error_handler;

use Rollbar\Payload\Level;
use yii\base\ErrorException;

trait ErrorHandlerTrait
{
    /**
     * @inheritdoc
     */
    public function logException($exception)
    {
        \Yii::$app->rollbar->log($this->getSeverityLevel($exception), $exception, [], true);

        parent::logException($exception);
    }

    /**
     * Determine the severity level of the error
     * @param mixed $exception
     * @return string
     */
    private function getSeverityLevel($exception)
    {
        if ($exception instanceof \Error
            || ($exception instanceof ErrorException && ErrorException::isFatalError(['type' => $exception->getSeverity()]))
        ) {
            return Level::CRITICAL;
        }

        return Level::ERROR;
    }
}
