<html>
<head></head>
<body style="text-align:center;">
<img src ="http://i.imgur.com/4mDwCrL.png" alt ="Meisshi.png" height="60px" />
<p style="font-size: 20px">
	Welcome to Meisshi!
	<br />
	Click on this link to confirm your account and start on creating your card:
	<br />
	<br />
	<a href="{{ url('auth/confirm-user') . '?user_id=' . $user->id . '&confirmation_token=' . $user->confirmation_token }}">
		<img src ="http://i.imgur.com/WPvviSd.png" alt ="Meisshi.png" height="40px" />
	</a>
</p>
</body>
</html>