<?php
namespace Afrittella\LaravelLoggable\Contracts;

use Illuminate\Database\Eloquent\Model;

interface Logger
{
    /**
     * Attach a log message to $model
     * @param int|string $level
     * @param string $message
     * @param array $context
     * @return bool
     * @internal param Model $model
     */
    public function addLog($level, string $message, array $context = []);

    /**
     * Sets the model to which logs are attached
     *
     * @param Model $model
     * @return mixed
     */
    public function attach(Model $model);

    public function log($level, string $message, array $context);
    public function debug($message, array $context = []);
    public function info($message, array $context = []);
    public function notice($message, array $context = []);
    public function warning($message, array $context = []);
    public function error($message, array $context = []);
    public function critical($message, array $context = []);
    public function alert($message, array $context = []);
    public function emergency($message, array $context = []);

    public function getLevels() : array ;
    public function getLevelName(int $level) : string ;

    /**
     * Return always level number
     * @param mixed $level
     * @return int
     */
    public function toLoggerLevel($level) : int ;

}