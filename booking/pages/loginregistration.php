<form action="login.php" method="post" id="loginform">
	<div class="ui-widget ui-widget-content ui-corner-all loginscreen">
		<h2>Login</h2>
		<input type="hidden" name="form" value="loginform" />
		<div>
			<label>Email:</label><input type="text" name="email" class="ui-widget-content"/>
		</div>
		<div>
			<label>Password:</label><input type="password"
				name="password" class="ui-widget-content"/>
		</div>
		<input type="submit" id="login" name="login" value="Login"/> 
		<br /><br />
		<a
			style="margin-left: 2em;" id="registerlink" href="#">Register</a>
			<a
			style="margin-left: 4em;" id="forgotlink" href="#">Forgot Password</a>

	</div>
</form>
<form action="register.php" method="post" id="registerform">
	<div id="registerdialog" title="Register" class="registerscreen">
		<input type="hidden" name="form" value="registerform" />
		<div>
			<label>Name: *</label><input type="text" name="fname"
				class="ui-widget-content"/>
		</div>
		<div>
			<label>Lastname: *</label><input type="text" name="lname"
				class="ui-widget-content"/>
		</div>
		<div>
			<label>Cellphone: *</label><input type="text"
				name="cellphone" class="ui-widget-content"/>
		</div>
		<div>
			<label>Birthdate:</label><input type="text" name="bdate"
				class="ui-widget-content date-widget"/>
		</div>

		<br />
		<div>
			<label>Email: *</label><input type="text"
				name="email"  class="ui-widget-content"/>
		</div>
		<div>
			<label>Password: *</label><input
				type="password"  name="password" id="registerpassword"
				class="ui-widget-content"/>
		</div>
		<div>
			<label>Confirm Password: *</label><input
				type="password" name="confpassword" 
				class="ui-widget-content"/>
		</div>
		<em>* Required fields</em>
	</div>
</form>
<form action="forgotpassword.php" method="post" id="forgotpasswordform">
	<div id="forgotpassworddialog" title="Register" class="registerscreen">
		<input type="hidden" name="form" value="forgotpasswordform" />
		<div>
		Type in your registered password. An email with a new temporary password will be sent to the email address.
		</div>
		<br />
		<div>
			<label>Registered Email:</label><input type="text"
				name="email" class="ui-widget-content"/>
		</div>
	</div>
</form>
