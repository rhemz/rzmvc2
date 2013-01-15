<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
<head>

	<!-- Basic Page Needs
  ================================================== -->
	<meta charset="utf-8">
	<title><?=$title?></title>
	<meta name="description" content="">
	<meta name="author" content="">

	<!-- Mobile Specific Metas
  ================================================== -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<!-- CSS
  ================================================== -->
	<link rel="stylesheet" href="/public/css/base.css">
	<link rel="stylesheet" href="/public/css/skeleton.css">
	<link rel="stylesheet" href="/public/css/layout.css">
	<link rel="stylesheet" href="/public/css/sampleapp.css">

	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<!-- Favicons
	================================================== -->
	<link rel="shortcut icon" href="/public/images/favicon.ico">
	<link rel="apple-touch-icon" href="/public/images/apple-touch-icon.png">
	<link rel="apple-touch-icon" sizes="72x72" href="/public/images/apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="114x114" href="/public/images/apple-touch-icon-114x114.png">

	<script src="/public/scripts/jquery-1.8.3.min.js"></script>

</head>
<body>


<div class="container">

	<div class="header">
		<p><?=isset($alt) ? $alt : $title?></p>
	</div>

	<div class="content">