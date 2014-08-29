<div id="confirmEmailForm" style="display:none">
    <?php echo Form::open(array('method' => 'GET','url' => 'confirm_email', 'class' => 'form-signin', 'role' => 'form')); ?>
    <h2 class="form-signin-heading"><?php echo Lang::get('LaravelUsers::auth.ConfirmEmail')?></h2>
    <input name="email" type="username" class="form-control form-control-top" placeholder="<?php echo Lang::get('LaravelUsers::auth.Email')?>" value="<?php echo Input::get('email')?>" required autofocus>
    <input name="token" type="text" class="form-control form-control-bottom" placeholder="<?php echo Lang::get('LaravelUsers::auth.Token')?>" required>
    <button class="btn btn-lg btn-primary btn-block" type="submit"><?php echo Lang::get('LaravelUsers::auth.Confirm')?></button>
    <?php echo Form::close(); ?>
    <div class="alert alert-info alert-dismissible" role="alert">
        <?php echo Lang::get('LaravelUsers::auth.ConfirmEmailFormText')?>
    </div>
</div>