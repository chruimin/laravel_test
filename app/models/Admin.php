<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Cartalyst\Sentry\Groups\GroupInterface;
use Cartalyst\Sentry\Hashing\HasherInterface;
use Cartalyst\Sentry\Users\LoginRequiredException;
use Cartalyst\Sentry\Users\PasswordRequiredException;
use Cartalyst\Sentry\Users\UserAlreadyActivatedException;
use Cartalyst\Sentry\Users\UserExistsException;
use Cartalyst\Sentry\Users\UserInterface;
class Admin extends \Cartalyst\Sentry\Users\Eloquent\User implements UserInterface{
    /**
    * The table associated with the model.
    *
    * @var string
    */
    protected $table = 'admins';
    public function groups(){
         
        return $this->belongsToMany('Cartalyst\Sentry\Groups\Eloquent\Group',
            'admins_groups');
    }
         
    protected static $groupModel = 'Cartalyst\Sentry\Groups\Eloquent\Group';
}