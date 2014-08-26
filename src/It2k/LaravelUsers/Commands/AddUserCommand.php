<?php namespace It2k\LaravelUsers\Commands;

use Lang;
use Validator;
use User;
use Hash;
use Illuminate\Console\Command;

class AddUserCommand extends Command {

    protected $name = 'auth:add-user';
    protected $description = 'Add new user to database.';

    public function fire()
    {
        $username = $this->option('username');
        $email    = $this->option('email');
        $password = $this->option('password');

        if (!$username or !$email)
        {
            $username = $this->ask(Lang::get('LaravelUsers::auth.UserName').': ');
            $email    = $this->ask(Lang::get('LaravelUsers::auth.Email').': ');
        }

        if (!$password)
        {
            $password = $this->secret(Lang::get('LaravelUsers::auth.Password').': ');
        }

        $enable = $this->confirm(Lang::get('LaravelUsers::auth.Enable').'(Y/n)?') ? true : false;

        //$validation = Validator::make(array('username' => $username, 'email' => $email, 'password' => $password), User::$rules);
        //if ($validation->passes())
        //{
            User::create(array('username' => $username, 'email' => strtolower($email), 'password' => Hash::make($password), 'enable' => (($enable)?1:0)));
            $this->info('ok');
        //}
        //else
        //{
        //    $this->error('false');
        //}
    }

    public function getOptions()
    {
        return array(
            array('username', 'username', 4, Lang::get('LaravelUsers::auth.UserName'), ''),
            array('email', 'email', 4, Lang::get('LaravelUsers::auth.Email'), ''),
            array('password', 'password', 4, Lang::get('LaravelUsers::auth.Password'), ''),
        );
    }
}
