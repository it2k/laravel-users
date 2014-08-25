<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Вход</title>
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
			<div class="my-page-header">

				<div class="col-md-4 col-md-offset-4">
					<div id="loginForm">
						<form class="form-signin" role="form" method="post">
							<h2 class="form-signin-heading">Авторизация</h2>
							<input name="username" type="username" class="form-control form-control-top" placeholder="Email или имя пользователя" required autofocus>
							<input name="password" type="password" class="form-control form-control-bottom" placeholder="Пароль" required>
							<div class="checkbox">
								<label>
									<input name="remember" type="checkbox" value="1"> Запомнить меня
								</label>
							</div>
							<button class="btn btn-lg btn-primary btn-block" type="submit">Войти</button>
						</form>
							<ul>
								<li>Регистрация</li>
								<li>Восстановление пароля</li>
							</ul>
					</div>

					<div id="registrationForm" style="display:none">
						<form class="form-signin" role="form" method="post">
							<h2 class="form-signin-heading">Нет аккаунта</h2>
							<input name="username" type="username" class="form-control form-control-top" placeholder="Имя пользователя" required>
							<input name="email" type="email" class="form-control form-control-center" placeholder="Email" required>
							<input name="password" type="password" class="form-control form-control-center" placeholder="Пароль" required>
							<input name="password_confirm" type="password" class="form-control form-control-bottom" placeholder="Подтверждение пароля" required>
							<button class="btn btn-lg btn-primary btn-block" type="submit">Зарегистрироваться</button>
						</form>
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
	function showForm(name)
	{
		//
	}

	$( document ).ready(function() {
    	//alert(location.hash);
	});
</script>
</body>
</html>

