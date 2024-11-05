<?php

namespace App\Models;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class RecruitmentForm extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, HasFactory;

    protected $appends = [
        'cv',
    ];

    public $table = 'recruitment_forms';

    protected $dates = [
        'appointment',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const CHANEL_RADIO = [
        'Frotas'        => 'Frotas',
        'Redes sociais' => 'Redes sociais',
        'Promotor'      => 'Promotor'
    ];

    public const STATUS_RADIO = [
        'Fechado'     => 'Fechado',
        'Tratamento'  => 'Tratamento',
        'Sem sucesso' => 'Sem sucesso',
        'Em aberto'   => 'Em aberto'
    ];

    public const TYPE_RADIO = [
        'TVDE Frota'        => 'TVDE Frota',
        'Motorista Próprio' => 'Motorista Próprio',
        'Formação'          => 'Formação',
    ];

    protected $fillable = [
        'company_id',
        'name',
        'email',
        'contact_successfully',
        'phone',
        'scheduled_interview',
        'appointment',
        'done',
        'comments',
        'status',
        'type',
        'chanel',
        'responsible_for_the_lead',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function getCvAttribute()
    {
        return $this->getMedia('cv')->last();
    }

    public function getAppointmentAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setAppointmentAttribute($value)
    {
        $this->attributes['appointment'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }
}