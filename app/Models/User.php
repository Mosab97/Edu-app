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

    public $translatable = ['name'];

    public const manager_route = 'users';
    public const user_type = [
        'STUDENT' => 1,
        'TEACHER' => 2,
    ];
    public const user_status = [
        'Pending' => 'Pending',
        'Accepted' => 'Accepted',
        'Rejected' => 'Rejected',
        'Blocked' => 'Blocked',
    ];

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot(); //  Change the autogenerated stub
        static::addGlobalScope('orderedBy', function (Builder $builder) {
            $builder->latest('updated_at');
        });
    }


    //    User Register type
    public const ANDROID = 1;
    public const WEB = 2;
    public const DASHBOARD = 3;


    protected $hidden = ['password', 'remember_token',];

    //    relations

//    Scopes

    public function scopePhone($query, $param)
    {
        return $query->where('phone', $param);
    }

    public function scopeTeacherType($query)
    {
        return $query->where('user_type', self::user_type['TEACHER']);
    }

    public function scopeStudentType($query)
    {
        return $query->where('user_type', self::user_type['STUDENT']);
    }


    public function scopeNotDraft($query)
    {
        return $query->where('users.draft', false);
    }

    public function student_groups()
    {
        return $this->hasMany(StudentGroups::class, 'student_id');
    }

    public function student_details()
    {
        return $this->hasOne(StudentDetails::class, 'student_id');
    }


    public function teacher_details()
    {
        return $this->hasOne(Teacher::class, 'teacher_id');
    }

    public function teacher_groups()
    {
        return $this->hasMany(Group::class, 'teacher_id');
    }


    public function getImagePathAttribute()
    {
        $image = File::where(['target_id' => $this->id, 'target_type' => User::class])->first();
        return !isset($image) ? defaultUserImage() : $image->path;
    }


//    attributes
    public function getStatusNameAttribute()
    {
        return ($this->verified) ? api('Active') : api('Not Active');
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
        'user_type' => 'integer',
    ];

}
