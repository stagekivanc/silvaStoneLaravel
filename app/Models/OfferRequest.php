<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OfferRequest extends Model
{
    protected $fillable = [
        'application_number',
        'product_id',
        'user_id',
        'source',
        'status',
        'name',
        'company',
        'email',
        'phone',
        'areas',
        'budget',
        'employees',
        'configuration',
        'items',
        'message',
        'admin_note',
        'is_read',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'areas' => 'array',
        'configuration' => 'array',
        'items' => 'array',
        'is_read' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'pending' => 'Beklemede',
            'processing' => 'İşleniyor',
            'quoted' => 'Teklif Verildi',
            'closed' => 'Kapatıldı',
            default => $this->status ?: 'Beklemede',
        };
    }

    public function isCartQuote(): bool
    {
        return ($this->source ?? 'form') === 'cart';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->application_number)) {
                $model->application_number = 'OFF-' . strtoupper(bin2hex(random_bytes(4)));
            }
            if (empty($model->source)) {
                $model->source = 'form';
            }
            if (empty($model->status)) {
                $model->status = 'pending';
            }
        });
    }
}
