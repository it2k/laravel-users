@extends('LaravelUsers::authTemplate')

@section('title'){{Lang::get('LaravelUsers::auth.Auth')}}@stop

@section('main')
    {{Form::open(array('class' => 'form-signin', 'role' => 'form'));}}
        <h2 class="form-signin-heading">{{Lang::get('LaravelUsers::auth.Auth')}}</h2>
        <input name="username" type="username" class="form-control form-control-top" placeholder="{{Lang::get('LaravelUsers::auth.UserNameOrEmail')}}" value="{{Input::old('username')}}" required autofocus>
        <input name="password" type="password" class="form-control form-control-bottom" placeholder="{{Lang::get('LaravelUsers::auth.Password')}}" value="" required>
        <div class="checkbox">
            <label>
                <input name="remember" type="checkbox" value="1"> {{Lang::get('LaravelUsers::auth.RememberMe')}}
            </label>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">{{Lang::get('LaravelUsers::auth.SignIn')}}</button>
    {{Form::close();}}
    <ul>
        <?php if(Config::get('LaravelUsers::auth.registration-allow')):?>
        <li><a href="{{ URL::to('registration') }}">{{Lang::get('LaravelUsers::auth.Registration')}}</a></li>
        <?php endif;?>
        <li><a href="{{ URL::to('lost_password') }}">{{Lang::get('LaravelUsers::auth.LostPassword')}}</a></li>
    </ul>
@stop