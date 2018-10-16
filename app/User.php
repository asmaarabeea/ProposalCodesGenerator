<?php namespace App;

use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, LogsActivity, SoftDeletes;

    public $timestamps = TRUE;
    protected $dates = ['created_at','updated_at','deleted_at'];
    protected $fillable  = [
        'first_name', 'last_name', 'email', 'password',
    ];

    protected static $logAttributes = [
        'first_name',
        'last_name',
        'email',
    ];
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function code()
    {
        return $this->hasOne('App\Code');
    }

    /**
     * @param $value
     */
    public function setPasswordAttribute($value) {
        $this->attributes['password'] = bcrypt($value);
    }
}
