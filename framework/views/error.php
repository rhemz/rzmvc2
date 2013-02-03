<html>
<head>
	<title>Error</title>
</head>

<body>

	<p>
		Whoops, something broke on line <?=$error['line']?> in <?=$error['file']?>.  
		<?php Logger::print_r($error); ?>
	</p>

</body>
</html>