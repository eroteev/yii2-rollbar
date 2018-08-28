Rollbar for Yii2
================
Rollbar monitoring integration for Yii2 applications.

Installation
------------
The preferred way to install this extension is through [composer](http://getcomposer.org/download/). 

 To install add
 ```
 "eroteev/yii2-rollbar": "0.1.*"
 ```
 to the `require` section of your `composer.json` file.

Setup instructions
-----
1. Add the component configuration in your *global* config file:
 ```php
 'bootstrap' => ['rollbar'],
 'components' => [
     'rollbar' => [
         'class' => 'eroteev\rollbar\Rollbar',
         'config' => [
             'access_token' => 'POST_SERVER_ITEM_ACCESS_TOKEN',
         ]
     ],
 ],
 ```

2. Add the *web* error handler configuration in your *web* config file:
 ```php
 'components' => [
     'errorHandler' => [
         'class' => 'eroteev\rollbar\error_handler\WebErrorHandler'
     ],
 ],
 ```

3. Add the *console* error handler configuration in your *console* config file:
 ```php
 'components' => [
     'errorHandler' => [
         'class' => 'eroteev\rollbar\error_handler\ConsoleErrorHandler'
     ],
 ],
 ```

4. Add log target in your *main* config file:
 ```php
 'log' => [
     'targets' => [
         [
             'class' => 'eroteev\rollbar\log\RollbarTarget',
             'levels' => ['error'], // Log levels you want to appear in Rollbar
             'categories' => ['application'],
         ],
     ],
 ],
 ```

Ignore specific exceptions
-----
To ignore specific exceptions you can update the component configuration in your *global* config file:
 ```php
 'components' => [
     'rollbar' => [
         'class' => 'eroteev\rollbar\Rollbar',
         'config' => [
             // ...
             'check_ignore' => function ($isUncaught, $toLog, $payload) {
                 return eroteev\rollbar\helpers\IgnoreExceptionHelper::checkIgnore($toLog, [
                        ['yii\web\HttpException', 'statusCode' => [400, 404]],
                        ['yii\db\Exception', 'getCode' => [2002]],
                    ]
                 );
             }
         ]
     ],
 ],
 ```