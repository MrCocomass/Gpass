<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class User extends Model
{
    protected $fillable = ['name', 'password', 'email', 'user_id'];
    protected $table = 'users';
    public function passwords()
    {
        return $this->hasMany('App\Password');
    }
    public function categories()
    {
        return $this->hasMany('App\Category');
    }
    public function roles()
    {
        return $this->belongsTo('App\Role');
    }
}
