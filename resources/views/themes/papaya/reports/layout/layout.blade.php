<!DOCTYPE html>
<html lang="es">

<head>
	@include('reports.layout.head')
</head>

<body>
	<header>
		@include('reports.layout.header')
	</header>
	<main>
		@yield('content')
	</main>
	<footer>
		@include('reports.layout.footer')
	</footer>
</body>

</html>
