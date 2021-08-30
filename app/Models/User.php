<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Laravel\Passport\HasApiTokens;
use Spatie\Translatable\HasTranslations;


class User extends Authenticatable
{
    use Notifiable, SoftDeletes, HasApiTokens;
    use HasTranslations;

    public $translatable = ['name'];

    public const DIR_IMAGE_UPLOADS = 'users';
    public const client_type = [
        'CLIENT' => 1,
        'COMPANY' => 2,
    ];

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot(); //  Change the autogenerated stub
        static::addGlobalScope('orderedBy', function (Builder $builder) {
            $builder->orderBy('ordered')->latest('updated_at');
        });

        if (request()->is('api/*')) {
            static::addGlobalScope('notDraft', function (Builder $builder) {
                $builder->notDraft();
            });
//        static::addGlobalScope('active', function (Builder $builder) {
//            $builder->active();
//        });
        }

    }


    //    User Register type
    public const ANDROID = 1;
    public const WEB = 2;
    public const DASHBOARD = 3;


    protected $hidden = ['password', 'remember_token',];

    //    relations
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function packages()
    {
        return $this->hasMany(UserPackage::class);
    }

//    Scopes

    public function scopePhone($query, $param)
    {
        return $query->where('phone', $param);
    }


    public function scopeNotDraft($query)
    {
        return $query->where('users.draft', false);
    }

//    attributes
    public function getStatusNameAttribute()
    {
        return ($this->verified)? api('Active'):api('Not Active');
    }


    public function getImageAttribute($value)
    {
        return is_null($value) ? defaultUserImage() : asset($value);
    }

    public function getActiveNameAttribute()
    {
        return $this->status ? t('Active') : t('Inactive');
    }

    public function getSourceNameAttribute()
    {
        switch ($this->source) {
            case self::ANDROID:
                return api('Android');
            case self::WEB:
                return api('Web');
            case self::DASHBOARD:
                return api('Dashboard');
            default:
                return api('unknown status');
                break;
        }

    }


    public function getUnreadNotificationsAttribute()
    {
        return $this->unreadNotifications()->count();
    }

    public function getActionButtonsAttribute()
    {
        $route = Request::is('manager/user') ? 'user' : 'driver';


        if (Auth::guard('manager')->check()) {
            $button = '';
            $button .= '<a href="' . route('manager.' . $route . '.edit', $this->id) . '" class="btn btn-icon btn-danger "><i class="la la-pencil"></i></a> ';
//            $button .= '<a href="' . route('manager.' . $route . '.show', $this->id) . '" class="btn btn-icon btn-danger "><i class="la la-eye"></i></a> ';
            $button .= '<button type="button" data-id="' . $this->id . '" data-toggle="modal" data-target="#deleteModel" class="deleteRecord btn btn-icon btn-danger"><i class="la la-trash"></i></button>';
            return $button;
        }
    }


//methods
    public function setLanguage()
    {
        $locale = $this->local ?? config("app.fallback_locale");
        app()->setLocale($locale);
    }


    protected $casts = [
        'email_verified_at' => 'datetime',
        'updated_at' => 'datetime',
        'lat' => 'double',
        'lng' => 'double',
        'type' => 'integer',
        'is_registered' => 'boolean',
        'show_phone_number' => 'boolean',
    ];

}
