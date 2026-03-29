<?php

namespace Pratik\Firewall\Models;

use Illuminate\Database\Eloquent\Model;

class FirewallLog extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'firewall_logs';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
}
