<?php

namespace Afrittella\LaravelLoggable\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class Log extends Model
{
    protected $guarded = [
        'created_at', 
        'updated_at',
    ];
    
    protected $casts = [
        'context' => 'array',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        
        $this->table = config('loggable.log_table');

        if (empty($attributes['remote_ip'])) {
            $this->setRemoteIpAttribute();
        }

        if (empty($attributes['user_agent'])) {
            $this->setUserAgentAttribute();
        }

        if (empty($attributes['user_id'])) {
            $this->setUserIdAttribute();
        }
    }

    public function loggable()
    {
        return $this->morphTo();
    }

    public function setRemoteIpAttribute()
    {
        $this->attributes['remote_ip'] = Request::server('REMOTE_ADDR') ? ip2long(Request::server('REMOTE_ADDR')) : null;
    }

    public function setUserAgentAttribute()
    {
        $this->attributes['user_agent'] = Request::server('HTTP_USER_AGENT') ??  null;
    }

    public function setUserIdAttribute()
    {
        $this->attributes['user_id'] = Auth::check() ? Auth::user()->id : null;
    }
}
