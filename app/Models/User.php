<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Mailer\Contracts\MailableContract;
use App\Filters\UserFilter;
use App\Filters\Traits\Filterable;
use App\Facades\Mail;
use App\Facades\Cache;

class User extends Model
{
    use Filterable;
    
    protected $table = 'user';
    
    protected $fillable = [
        'email',
        'name',
        'is_active',
        'is_admin',
    ];
    
    protected $defaultFilter = UserFilter::class;

    // Relations

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_role', 'user_id', 'role_id');
    }

    // Attributes

    public function getAvatarPathAttribute()
    {
        if ($this->avatar) {
            return public_path(self::AVATAR_FOLDER . DIRECTORY_SEPARATOR . $this->avatar);
        }

        return null;
    }

    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return self::AVATAR_FOLDER . '/' . $this->avatar;
        }

        return 'images/noface.png';
    }

    // Scopes

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByRememberToken($query, $id, $token)
    {
        return $query->where('id', $id)->where('remember_token', $token);
    }

    // Functions

    public function setPassword(string $password)
    {
        $this->password = bcrypt($password);
        $this->remember_token = null;
    }

    public function generateRememberToken()
    {
        $this->remember_token = str_random(60);
        $this->save();
    }

    public function removeRememberToken()
    {
        $this->remember_token = null;
        $this->save();
    }

    public function can(string $ability, $model = null): bool
    {
        if (is_null($model)) {
            return $this->hasPermission($ability);
        }
        
        return \App\Policies\PolicyResolver::for($model)->can($this, $ability);
    }

    public function cannot(string $ability, $model = null): bool
    {
        return ! $this->can($ability, $model);
    }

    public function hasPermission(string $permission): bool
    {
        if ($this->is_admin) {
            return true;
        }

        return in_array($permission, $this->getPermissions());
    }

    // Twig
    public function has_permission(string $permission): bool
    {
        return $this->hasPermission($permission);
    }

    public function getPermissions(): array
    {
        $permissions = function () {
            $roles = $this->roles->pluck('id')->toArray();

            $permissions = Permission::whereExists(
                function ($query) use ($roles) {
                    return $query->select($query->raw(1))
                        ->from('role_permission')
                        ->whereRaw('`role_permission`.`permission_id` = `permission`.`id`')
                        ->whereIn('role_permission.role_id', $roles)
                    ;
                }
            )->pluck('id')->toArray();

            return json_encode($permissions);
        };

        $key = sprintf('user.%s.permissions', $this->id);
        $json = Cache::remember($key, 60, $permissions);
        
        return json_decode($json);
    }

    public function send(MailableContract $mail)
    {
        if (filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            Mail::to($this)->send($mail);
        }
    }
}
