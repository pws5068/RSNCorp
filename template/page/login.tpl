<h2>Customer Login</h2>

<br />
{if !Security::isMember()}
<form name="loginForm" method="post">
	Email:&nbsp; &nbsp; &nbsp; &nbsp; <input type="text" name="userField" /><br />
	Password: <input type="password" name="passField" /><br />
	
	<br />
	<input type="submit" name="submit" value="Login" />
    <a href="/page/register.php">New User?</a>
</form>
{elseif is_object($me)}

	<h3>Welcome Back, {$me->getName()}</h3>
	
	<br />
	Today is {$smarty.now|date_format}
{/if}

<br />
<br />
<br />
<br />