<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';
    
    protected $fillable = [
        'user_id',
        'role',
    ];

	public function user()
    {
        return $this->belongsTo('App\User');
    }
    public static function isRole($check_role)
    {
        $user_roles = self::where(['user_id'=> \Auth::user()->id,'role'=>$check_role])->first();
        return $user_roles ? true : false;
    }
}
