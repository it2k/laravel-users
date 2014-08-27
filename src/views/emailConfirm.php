<<!DOCTYPE html>
<html>
<head>
	<title><?php echo Lang::get('LaravelUsers::auth.ConfirmEmail');?></title>
</head>
<body>
<?php echo Lang::get('LaravelUsers::auth.ConfirmEmailText', ['token' => $token, 'email' => $email]);?>
</body>
</html>>