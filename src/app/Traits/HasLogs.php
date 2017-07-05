<?php
namespace Afrittella\LaravelLoggable\Traits;

trait HasLogs
{
    public function logs()
    {
        return $this->morphMany(config('loggable.log_model'), 'loggable');
    }

    public function hasLogs()
    {
        return (count($this->logs->toArray()) > 0);
    }

    public function delete()
    {
        if ($this->hasLogs()) {
            $this->logs()->each(function($item) {
                $item->delete();
            });
        }

        return parent::delete();
    }
}