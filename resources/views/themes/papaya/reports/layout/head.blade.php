<!-- Required meta tags -->
<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="author" content="{{ trans(\Config::get('app.theme').'-app.head.meta_author') }}">
<meta name="description" content="{{ trans(\Config::get('app.theme').'-app.head.meta_description') }}">
<title>
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
</title>
<meta name="title" content="{{ trans(\Config::get('app.theme').'-app.head.title_app') }}">

<!-- Bootstrap CSS -->


<style>
	@page {
		margin: 130px 50px;
	}

	header {
		position: fixed;
		top: -100px;
		left: 0px;
		right: 0px;
		height: 80px;
	}

	footer {
		position: fixed;
		bottom: -95px;
		left: 0px;
		right: 0px;
		height: 60px;
	}

	h1, h2{
		text-align: center;
	}

	table,
	th,
	td {
		border: 1px solid black;
		border-collapse: collapse;
	}

	th, .td-title {
		background-color: #018ccc;
		color: white;
	}

	th,
	td {
		padding: 5px 0px 5px 4px;
		text-align: left;
	}

	td {
		font-size: 12px;
	}

	td.Sociedad, td.colummn-name {
		font-size: 12px;
	}


</style>
