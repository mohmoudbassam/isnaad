<?php

namespace App;

use App\Models\DeviceToken;
use App\Models\role;
use App\Models\Ticket;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class user extends Authenticatable
{

    use Notifiable;
    use HasRoles;

    protected $webhook;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'type', 'pc_device_token', 'mobile_device_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    protected $appends = [
        'Order_Number'
    ];

    public function store()
    {
        return $this->belongsTo(store::class, 'id', 'user_id');
    }

    public function routeNotificationForSlack()
    {
        if ($this->webhook) {
            return config('slack.channels.' . $this->webhook);
        }
    }

    public function slackChannel($channel)
    {
        $this->webhook = $channel;
        return $this;
    }

    public function getOrderNumberAttribute()
    {
        if ($this->store) {
            return $this->store->orders()->count();
        } else {
            return null;
        }


    }

    public function device_token()
    {
        return $this->hasMany(DeviceToken::class);
    }

    public function routeNotificationForFcm()
    {
        return $this->pc_device_token;
    }

    public function admin_recent_ticket()
    {
        return $this->belongsToMany(Ticket::class, 'ticket_assigned_to')->whereHas('status', function ($q) {
            $q->where('name', 'opened');
        });
    }


    public function client_recent_ticket()
    {
        return Ticket::query()->where('store_id', $this->store->account_id)->whereHas('status', function ($q) {
            $q->where('name', 'opened');
        })->latest()->get();
    }


}
