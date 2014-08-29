<div id="registrationForm" style="display:none">
    <?php echo Form::open(array('url' => 'registration', 'class' => 'form-signin', 'role' => 'form')); ?>
    <h2 class="form-signin-heading"><?php echo Lang::get('LaravelUsers::auth.Registration')?></h2>
    <input name="username" type="username" class="form-control form-control-top" placeholder="<?php echo Lang::get('LaravelUsers::auth.UserName')?>" value="<?php echo Input::old('username')?>" required autofocus>
    <input name="email" type="email" class="form-control form-control-center" placeholder="<?php echo Lang::get('LaravelUsers::auth.Email')?>" value="<?php echo Input::old('email')?>" required>
    <input name="password" type="password" class="form-control form-control-center" placeholder="<?php echo Lang::get('LaravelUsers::auth.Password')?>" required>
    <input name="password_confirm" type="password" class="form-control form-control-bottom" placeholder="<?php echo Lang::get('LaravelUsers::auth.PasswordConfirm')?>" required>
    <button class="btn btn-lg btn-primary btn-block" type="submit"><?php echo Lang::get('LaravelUsers::auth.SignUp')?></button>
    <?php echo Form::close(); ?>
    <ul>
        <li><a href="#" onclick="showForm('loginForm', 'registrationForm')"><?php echo Lang::get('LaravelUsers::auth.Auth')?></a></li>
        <li><a href="#lost_password" onclick="showForm('lostPasswordForm', 'registrationForm')"><?php echo Lang::get('LaravelUsers::auth.LostPassword')?></a></li>
    </ul>
</div>