<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">

	{{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

	<title>Document</title>
	<link rel="stylesheet" href="css/app.css">
	<script src="/js/app.js"></script>
	
</head>
<body>
	@yield('content')
	@yield('scripts')
</body>
</html>