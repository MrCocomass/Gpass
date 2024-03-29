<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Category extends Model
{
    protected $fillable = ['name','user_id'];
    protected $table = 'categories';
    public function passwords()
    {
        return $this->hasMany('App\Password');
    }
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
