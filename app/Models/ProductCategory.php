<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory, \App\Traits\Translatable;

    protected $translatable = ['name', 'slug', 'description', 'home_description', 'seo_title', 'seo_description'];

    protected $fillable = [
        'parent_id', 'name', 'slug', 'title', 'description', 'image', 'icon', 'icon_home', 'order', 'status', 'home_status', 'seo_title', 'seo_description'
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id')->orderBy('order');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('order');
    }

    public function descendantIds(): array
    {
        $ids = [$this->id];
        foreach ($this->children as $child) {
            $ids[] = $child->id;
        }

        return $ids;
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->mediaUrl($this->image);
    }

    public function getIconUrlAttribute(): ?string
    {
        return $this->mediaUrl($this->icon) ?: $this->image_url;
    }

    public function getIconHomeUrlAttribute(): ?string
    {
        return $this->mediaUrl($this->icon_home) ?: $this->icon_url;
    }

    private function mediaUrl(?string $path): ?string
    {
        $path = trim((string) $path);
        if ($path === '') {
            return null;
        }

        if (str_starts_with($path, 'default:')) {
            $url = front_asset('img/' . substr($path, 8));

            return $url !== '' ? $url : null;
        }

        $url = homepage_media_url($path);

        return $url !== '' ? $url : null;
    }
}
