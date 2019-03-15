<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Password extends Model
{
    protected $fillable = ['title','password','category_id','user_id'];
    protected $table = 'passwords';
    public function users()
    {
        return $this->belongsTo('App\User');
    }
    public function categories()
    {
        return $this->belongsTo('App\Category');
    }
}
