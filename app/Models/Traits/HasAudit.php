<?php

namespace App\Models\Traits;

use App\Models\User;

trait HasAudit
{
    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function setCreatedBy(User $user)
    {
        $this->created_by = $user->id;
    }

    public function scopeCreatedBy($query, User $user)
    {
        return $query->where('created_by', $user->id);
    }

    public function wasCreatedBy(User $user)
    {
        return $user->id == $this->created_by;
    }

    // Twig
    public function was_created_by(User $user)
    {
        return $this->wasCreatedBy($user);
    }

    public function updatedByUser()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function setUpdatedBy(User $user)
    {
        $this->updated_by = $user->id;
    }

    public function scopeUpdatedBy($query, User $user)
    {
        return $query->where('updated_by', $user->id);
    }

    public function wasUpdatedBy(User $user)
    {
        return $user->id == $this->updated_by;
    }

    // Twig
    public function was_updated_by(User $user)
    {
        return $this->wasUpdatedBy($user);
    }
}
