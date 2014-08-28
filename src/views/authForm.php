<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title></title>
	<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
	<style>
		body {
		  padding-top: 40px;
		  padding-bottom: 40px;
		  background-color: #eee;
		}
		
		.form-signin {
		  max-width: 330px;
		  padding: 15px;
		  margin: 0 auto;
		}
		.form-signin .form-signin-heading,
		.form-signin .checkbox {
		  margin-bottom: 10px;
		}
		.form-signin .checkbox {
		  font-weight: normal;
		}
		.form-signin .form-control {
		  position: relative;
		  height: auto;
		  -webkit-box-sizing: border-box;
		     -moz-box-sizing: border-box;
		          box-sizing: border-box;
		  padding: 10px;
		  font-size: 16px;
		}
		.form-signin .form-control:focus {
		  z-index: 2;
		}
		.form-signin .form-control-top {
		  margin-bottom: -1px;
		  border-bottom-right-radius: 0;
		  border-bottom-left-radius: 0;
		}
		.form-signin .form-control-center {
		  margin-bottom: -1px;
		  border-top-left-radius: 0;
		  border-top-right-radius: 0;
		  border-bottom-right-radius: 0;
		  border-bottom-left-radius: 0;
		}
		.form-signin .form-control-bottom {
		  margin-bottom: 10px;
		  border-top-left-radius: 0;
		  border-top-right-radius: 0;
		}

		.form-signin .form-control-one {
		  margin-bottom: 10px;
		}

		.my-page-header {
			margin-top: 50px;
		}
		.my-text-center {
			text-align: center;
		}

	</style>
</head>

<body>
	<!-- Begin page content -->
	<div class="container">
		<div class="row">

			<?php if ($errors->count() > 0): ?>
			<div class="col-md-6 col-md-offset-3">
				<div class="alert alert-danger alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<?php
					foreach ($errors->all('<li>:message</li>') as $message)
					{
					    echo $message;
					}
					?>
				</div>
			</div>
			<?php endif; ?>

			<div class="my-page-header">
				<div class="col-md-4 col-md-offset-4">

					<div id="loginForm" style="display:none">
						<?php echo Form::open(array('url' => 'login', 'class' => 'form-signin', 'role' => 'form')); ?>
							<h2 class="form-signin-heading"><?php echo Lang::get('LaravelUsers::auth.Auth')?></h2>
							<input name="username" type="username" class="form-control form-control-top" placeholder="<?php echo Lang::get('LaravelUsers::auth.UserNameOrEmail')?>" value="<?php echo Input::old('username')?>" required autofocus>
							<input name="password" type="password" class="form-control form-control-bottom" placeholder="<?php echo Lang::get('LaravelUsers::auth.Password')?>" value="" required>
							<div class="checkbox">
								<label>
									<input name="remember" type="checkbox" value="1"> <?php echo Lang::get('LaravelUsers::auth.RememberMe')?>
								</label>
							</div>
							<button class="btn btn-lg btn-primary btn-block" type="submit"><?php echo Lang::get('LaravelUsers::auth.SignIn')?></button>
						<?php echo Form::close(); ?>
						<ul>
							<?php if(Config::get('LaravelUsers::auth.registration-allow')):?>
							<li><a href="#registration" onclick="showForm('registrationForm', 'loginForm')"><?php echo Lang::get('LaravelUsers::auth.Registration')?></a></li>
							<?php endif;?>
							<li><a href="#lost_password" onclick="showForm('lostPasswordForm', 'loginForm')"><?php echo Lang::get('LaravelUsers::auth.LostPassword')?></a></li>
						</ul>
					</div>

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

				</div>

			</div>
		</div>
	</div>
	<!-- /container -->
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="//code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
<script type="text/javascript">
	function showForm(openedForm, closedForm)
	{
		var titleArray = [];
		titleArray['loginForm']        = '<?php echo Lang::get('LaravelUsers::auth.Auth')?>';
		titleArray['registrationForm'] = '<?php echo Lang::get('LaravelUsers::auth.Registration')?>';
		titleArray['lostPasswordForm'] = '<?php echo Lang::get('LaravelUsers::auth.LostPassword')?>';
		titleArray['confirmEmailForm'] = '<?php echo Lang::get('LaravelUsers::auth.ConfirmEmail')?>';

		if(closedForm)
		{
			$('#'+closedForm).hide();
		}
		$('#'+openedForm).show();
		document.title = titleArray[openedForm];
	}

	$(document).ready(function()
	{
		if (location.hash)
		{
			switch (location.hash) 
			{
				case '#registration': {
					showForm('registrationForm');
					break;
				}

				case '#lost_password': {
					showForm('lostPasswordForm');
					break;
				}

				case '#confirm_email': {
					showForm('confirmEmailForm');
					break;
				}

				default: {
					showForm('loginForm');
					break;
				}				
			}
		}
		else
		{
			showForm('loginForm');
		}
	});
</script>
</body>
</html>

