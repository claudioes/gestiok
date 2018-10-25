<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use Traits\HasAudit;

    protected $table = 'comment';
    protected $fillable = ['message'];

    // Relations

    public function commentable()
    {
        return $this->morphTo('model', 'model_type', 'model_id');
    }

    // Scopes

    public function scopeByModel($query, $model)
    {
        return $query->where('model_type', get_class($model))->where('model_id', $model->id);
    }
}
