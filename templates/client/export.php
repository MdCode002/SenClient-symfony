<?php 
require  __DIR__ . '/vendor/autoload.php';
$html2pdf = new \Spipu\Html2Pdf\Html2Pdf('P', 'A4', 'en');
$html2pdf->writeHTML('<h1>HelloWorld</h1>This is my first page
<table class="tg">
<thead>
	<tr>
		<td class="tg-0lax">Prenom</td>
		<td class="tg-0lax">Nom</td>
		<td class="tg-0lax">Modifier</td>
		<td class="tg-0lax">Suprimer</td>
	</tr>
</thead>
{% for client in clients %}
	<tr>
		<td class="tg-0lax">{{client.nom}}</td>
		<td class="tg-0lax">{{client.email}}</td>
		<td class="tg-0lax">
			<a href=") }}" class="btn btn-info">
				Modifier</a>
		</td>
		<td class="tg-0lax">
			<a href="" class="btn btn-info">
				Suprimer</a>
		</td>
	</tr>
{% endfor %}</table>

');
$html2pdf->output(); ?>