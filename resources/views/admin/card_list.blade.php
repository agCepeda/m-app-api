<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" href="{{ url('public/css/vendor/bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ url('public/css/app.css') }}">
</head>
<body>

<section id="main-section">
	<h1>Administrador</h1>
	<section id="index-module" class="row">
		<div class="col-md-4" id="card-list">
			<ul>
				<li v-for="card in listCards" v-on:click="loadCard(card)">@{{ card.name }}</li>
			</ul>
			<a href="">Agregar Tarjeta</a>
		</div>
		<div class="col-md-8" >
			<div class="row" id="canvas">
			</div>
			<table class="table">
				<thead>
					<tr>
						<th colspan="2">Elemento</th>
						<th>X</th>
						<th>Y</th>
						<th>Width</th>
						<th>Height</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="field in currentCard.fields">
						<td colspan="2">
							<select class="form-control" name="" id="">
								<option v-for="f in listFields" v-bind:value="f.id">@{{ f.name }}</option>		
							</select>
						</td>
						<td>
							<input class="form-control" type="text" v-bind:value="field.x">
						</td>
						<td>
							<input class="form-control" type="text" v-bind:value="field.y">
						</td>
						<td>
							<input class="form-control" type="text" v-bind:value="field.width">
						</td>
						<td>
							<input class="form-control" type="text" v-bind:value="field.height">
						</td>
						<td>
							<input class="form-control" type="text" v-bind:value="field.color">
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</section>
</section>

<script src="{{ url('public/js/vendor/jquery.min.js') }}"></script>
<script src="{{ url('public/js/vendor/vue.js') }}"></script>
<script src="{{ url('public/js/index.js') }}"></script>
</body>
</html>