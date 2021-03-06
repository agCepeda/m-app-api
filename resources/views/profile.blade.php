<html>
<head>
	<meta property="user_id" content="{{ $user->id }}">
	<meta property="og:url" content="{{ url('profile/' . $user->id) }}" />
	<meta property="og:type" content="article" />
	<meta property="og:title" content="{{ $user->name . ' ' . $user->last_name }}" />
	<meta property="og:description" content="{{ $user->profession }}" />
	@if($user->logo)
		<meta property="og:image" content="{{ $user->logo }}" />
	@else
		<meta property="og:image" content="{{ url('/') }}" />
	@endif
</head>
<body style="text-align:center;">
<img src ="http://i.imgur.com/4mDwCrL.png" alt = "Meisshi.png" height= "150px" />
                <p style="font-size: 20px"> 
                    Welcome to Meisshi!
                    <br />
                     Welcome to Meisshi now create your digital card with the best designs suitable for you and let your essence in every new person or clients. <br />Saves all data of people in an orderly and functional manner in your card holder. Thank you.
                     <br />

                    <br /><a href="javascript:onLaunchApp()"><img src ="http://i.imgur.com/YRCKHtx.png" alt = "Meisshi.png" height= "60px" /></a>
     <br />
            </p>
<script type="text/javascript">/*
	setTimeout(function () { window.location = "https://itunes.apple.com/appdir"; }, 25);
	window.location = "meisshi://"; */

	var userId = (function () { 
		var metas = document.getElementsByTagName('meta');
		for (var i=0; i<metas.length; i++) { 
			if (metas[i].getAttribute("property") == "user_id") { 
				return metas[i].getAttribute("content");
			} 
		}
		return "";
	})();

	function getMobileOperatingSystem() {
	  var userAgent = navigator.userAgent || navigator.vendor || window.opera;

	      // Windows Phone must come first because its UA also contains "Android"
	    if (/windows phone/i.test(userAgent)) {
	        return "Windows Phone";
	    }

	    if (/android/i.test(userAgent)) {
	        return "Android";
	    }

	    // iOS detection from: http://stackoverflow.com/a/9039885/177710
	    if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
	        return "iOS";
	    }

	    return "unknown";
	}

	function onLaunchApp() {
		var urlStore = null;
		if (getMobileOperatingSystem() == 'Android') {
			urlStore = 'https://play.google.com/store/apps/details?id=com.meisshi.ms';
		}
		if (getMobileOperatingSystem() == 'iOS') {
			urlStore = 'https://itunes.apple.com/py/app/meisshi/id1179194186?mt=8';
		}

		setTimeout(function () { window.location = urlStore; }, 5000);
		window.location = "meisshi://?profile=" + userId;
	}

</script>
</body>
</html>