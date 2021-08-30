<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpecialService extends Model
{
    protected $fillable = ['project_title', 'project_details', 'service_type', 'expected_budget', 'expected_delivery_time', 'user_id', 'other_help_attachments'];
    public const type = [
        'Account management services' => 1,
        'Financial analysis services' => 2,
        'Tax services' => 3,
        'Feasibility study services and business plans' => 4,
        'training services' => 5,
    ];
    public const manager_route = 'manager.special_services.';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
//    other_help_attachments
    public function getOtherHelpAttachmentsAttribute($value)
    {
        return is_null($value) ? null : asset($value);
    }
    public function getActionButtonsAttribute()
    {
        $button = '';
        $button .= '<a href="' . route(self::manager_route . 'show', $this->id) . '" class="btn btn-icon btn-danger "><i class="la la-eye"></i></a> ';
        $button .= '<button type="button" data-id="' . $this->id . '" data-toggle="modal" data-target="#deleteModel" class="deleteRecord btn btn-icon btn-danger"><i class="la la-trash"></i></button>';
        return $button;
    }
}
