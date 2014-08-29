<div id="lostPasswordForm" style="display:none">
    <?php echo Form::open(array('url' => 'lost_password', 'class' => 'form-signin', 'role' => 'form')); ?>
    <h2 class="form-signin-heading"><?php echo Lang::get('LaravelUsers::auth.LostPassword')?></h2>
    <input name="email" type="email" class="form-control form-control-one" placeholder="Email" required autofocus>
    <button class="btn btn-lg btn-primary btn-block" type="submit"><?php echo Lang::get('LaravelUsers::auth.Remind')?></button>
    <?php echo Form::close(); ?>
    <ul>
        <li><a href="#" onclick="showForm('loginForm', 'lostPasswordForm')"><?php echo Lang::get('LaravelUsers::auth.Auth')?></a></li>
        <?php if(Config::get('LaravelUsers::auth.registration-allow')):?>
            <li><a href="#registration" onclick="showForm('registrationForm', 'lostPasswordForm')"><?php echo Lang::get('LaravelUsers::auth.Registration')?></a></li>
        <?php endif; ?>
    </ul>
</div>