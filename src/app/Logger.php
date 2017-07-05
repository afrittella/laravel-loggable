<?php

namespace Afrittella\LaravelLoggable;

use Afrittella\LaravelLoggable\Contracts\Logger as LoggerInterface;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use Illuminate\Container\Container as App;

class Logger implements LoggerInterface
{
    const DEBUG = 100;
    const INFO = 200;
    const NOTICE = 250;
    const WARNING = 300;
    const ERROR = 400;
    const CRITICAL = 500;
    const ALERT = 550;
    const EMERGENCY = 600;

    public $levels = [
        self::DEBUG => 'DEBUG',
        self::INFO => 'INFO',
        self::NOTICE => 'NOTICE',
        self::WARNING => 'WARNING',
        self::ERROR => 'ERROR',
        self::CRITICAL => 'CRITICAL',
        self::ALERT => 'ALERT',
        self::EMERGENCY => 'EMERGENCY'
    ];

    protected $log_model;
    protected $model;
    protected $app;

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->log_model = $this->app->make(config('loggable.log_model'));
    }

    public function addLog($level, string $message, array $context = [])
    {
        if (!empty($message)) {
            $message = $this->interpolate($message, $context);
        }

        $level_name = $this->getLevelName($level);

        $log_record = new $this->log_model([
           'level' => $level,
           'level_name' => $level_name,
           'message' => $message,
           'context' => $context
        ]);

        //@TODO manage exceptions
        $log_record->loggable()->associate($this->model);

        $log_record->save();
    }

    public function attach(Model $model)
    {
        $this->model = $model;
        return $this;
    }


    public function log($level, string $message, array $context)
    {
        $this->addLog($this->toLoggerLevel($level), $message, $context);
    }

    public function debug($message, array $context = [])
    {
        $this->addLog(self::DEBUG, $message, $context);
    }

    public function info($message, array $context = [])
    {
        $this->addLog(self::INFO, $message, $context);
    }

    public function notice($message, array $context = [])
    {
        $this->addLog(self::NOTICE, $message, $context);
    }

    public function warning($message, array $context = [])
    {
        $this->addLog(self::WARNING, $message, $context);
    }

    public function error($message, array $context = [])
    {
        $this->addLog(self::ERROR, $message, $context);
    }

    public function critical($message, array $context = [])
    {
        $this->addLog(self::CRITICAL, $message, $context);
    }

    public function alert($message, array $context = [])
    {
        $this->addLog(self::ALERT, $message, $context);
    }

    public function emergency($message, array $context = [])
    {
        $this->addLog(self::EMERGENCY, $message, $context);
    }

    /**
     * Return array with level-names => level-codes
     *
     * @return array
     */
    public function getLevels(): array
    {
        return array_flip($this->levels);
    }


    public function getLevelName(int $level): string
    {
        if (!isset($this->levels[$level])) {
            throw new InvalidArgumentException('Level "' . $level . '" is not defined.');
        }

        return $this->levels[$level];
    }


    public function toLoggerLevel($level): int
    {
        if (is_string($level)) {
            if (defined(__CLASS__.'::'.strtoupper($level))) {
                return constant(__CLASS__.'::'.strtoupper($level));
            }
            throw new InvalidArgumentException('Level "'.$level.'" is not defined, use one of: '.implode(', ', array_keys($this->levels)));
        }
        return $level;
    }

    /**
     * Interpolates context values into the message placeholders.
     */
    public function interpolate($message, array $context = array())
    {
        // build a replacement array with braces around the context keys
        $replace = array();
        foreach ($context as $key => $val) {
            // check that the value can be casted to string
            if (!is_array($val) && (!is_object($val) || method_exists($val, '__toString'))) {
                $replace['{' . $key . '}'] = $val;
            }
        }

        // interpolate replacement values into the message and return
        return strtr($message, $replace);
    }
}