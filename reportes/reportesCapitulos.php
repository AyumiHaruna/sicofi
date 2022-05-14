<!DOCTYPE html>
<?php
	require_once('menu.php');
	session_start();
	header('Content-Type: text/html; charset=UTF-8');
?>
<html>
  <head>
    <meta charset="utf-8">
    <title>SICOFI | Reportes</title>

    <script type="text/javascript" src="../js/jquery-3.1.1.js"></script>
		<script type="text/javascript" src="../js/jquery-ui.js"></script>
		<script type="text/javascript" src="../js/readOnly.js"></script>
		<script type="text/javascript" src="../js/jquery.mask.min.js"></script>
		<script type="text/javascript" src="../js/jquery.dataTables.min.js"></script>
		<link rel="stylesheet" href="../css3/jquery-ui.css">
		<link rel="stylesheet" href="../css3/bootstrap.min.css">
		<link rel="stylesheet" href="../css3/font-awesome.min.css">
		<link rel="stylesheet" href="../css3/dataTables.bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="../css2/menu.css"></link>
		<link rel="stylesheet" href="../css3/reportes.css">
  </head>
  <body>
    <?php //-------------------------	INICIA MENÚ	--------------------------
			//revisa si el usuario esta logueado
			//sino esta logueado lo redirecciona al index
			if($_SESSION == NULL)
			{
				echo '<script languaje="javascript">
						alert("Area restringida, redireccionando...");
						location.href="../index.php";
						</script>';
			}
			//si esta logueado muestra el menu correspondiente al nivel
			else
			{
				$nivel = $_SESSION['nivel'];

				switch($nivel)
				{
					case 1:		echo $menu[1];
						break;

					case 2:		echo $menu[2];
						break;

					case 3:		echo $menu[3];
						break;

					case 4:		echo $menu[4];
						break;

					case 5:		echo $menu[5];
						break;
				}
			}
		?>

<!--  /////////////////////////// DIV TRANSPARENCIA ////////////////////////// -->
		<div class="fondoTransparencia"></div>

<!--  /////////////////////////// DIV AVISO ////////////////////////////////// -->
		<div class="col-md-4 col-md-offset-4 aviso text-center"></div>

<!--  /////////////////////////// MAIN DIV ////////////////////////////////// -->
    <div class="container mainDiv">
      <div class="col-md-12 bloque">
        <div class="col-md-12 subBloque">
          <legend>FILTROS</legend>
          <div class="col-md-12">
            <div class="col-sm-4 text-center  ">
              Capítulo 1000 <br>
              <input type="checkbox" id="check1" class="checkCap" value="1">
            </div>
            <div class="col-sm-4 text-center  ">
              Capítulo 2000 <br>
              <input type="checkbox" id="check2" class="checkCap" value="2">
            </div>
            <div class="col-sm-4 text-center  ">
              Capítulo 3000 <br>
              <input type="checkbox" id="check3" class="checkCap" value="3">
            </div>
						<div class="col-sm-6 text-center">
							Capítulo 4000 <br>
              <input type="checkbox" id="check4" class="checkCap" value="4">
						</div>
						<div class="col-sm-6 text-center">
							Capítulo 5000 <br>
              <input type="checkbox" id="check5" class="checkCap" value="5">
						</div>
          </div>
        </div>
        <div class="col-md-12 subBloque">
          <legend>REPORTE</legend>
					<table class="table table-bordered table-striped tablaLista">
						<thead>
							<tr>
								<th>#</th>
								<th>Nombre</th>
								<th>Total Autorizado</th>
								<th>Ministrado <br> (CNRF)</th>
								<th>Reintegros</th>
								<th>Ejercido</th>
								<th>Diferencia</th>
							</tr>
						</thead>
						<tbody class="tablaBody">

						</tbody>
					</table>
        </div>

				<div class="col-md-12 subBloque">
					<legend>TOTALES</legend>
					<br>
					<div class="col-sm-2 text-center totales">&nbsp;</div>
					<div class="col-sm-2 text-center totales" id="totAut"></div>
					<div class="col-sm-2 text-center totales" id="totMin"></div>
					<div class="col-sm-2 text-center totales" id="totRei"></div>
					<div class="col-sm-2 text-center totales" id="totEje"></div>
					<div class="col-sm-2 text-center totales" id="totDif"></div>
				</div>
      </div>
    </div>

  </body>
  <script type="text/javascript">
    $( document ).ready(function() {
      //------------------------------------------------------------
      //				              VARIABLES GLOBALES
      var stat = [1,1,1,1,1];

      //------------------------------------------------------------
      //				            	CONDICIONES INICIALES
      $(".aviso").hide();
      $(".fondoTransparencia").hide();
      $(".checkCap").attr('checked', true);
      console.log("var stat: "+stat);
      getReporte();

      //------------------------------------------------------------
      //				            	ACCIONES DEL DOM
      $(".checkCap").click( function(){
        testCheckbox();
      } );

      //------------------------------------------------------------
      //				            	FUNCIONES GENERALES
			//--  Agrega comas a las cantidades
      function testCheckbox(){
        for(var x=1; x<=5; x++){
          if( $('#check'+x).prop('checked') == true){
            stat[(x-1)] = 1;
          } else {
            stat[(x-1)] = 0;
          }
        }
        console.log('var stat: '+stat);
        getReporte();
      }

	    //--  Muestra aviso en la pantalla
      function muestraAviso(tipo, mensaje)
      {
        if(tipo == 'success'){ $(".aviso").addClass('success') }
        $(".aviso").html(mensaje);
        $(".aviso").show();
        setTimeout( function(){
          $(".aviso").hide();
          $(".aviso").removeClass('success');
          //$(".fondoTransparencia").hide();
        } ,3500);
      }

	    //--  Agrega comas a las cantidades
	    function addCommas(nStr)
	    {
	        nStr += '';
	        var x = nStr.split('.');
	        var x1 = x[0];
	        var x2 = x.length > 1 ? '.' + x[1] : '';
	        var rgx = /(\d+)(\d{3})/;
	        while (rgx.test(x1)) {
	            x1 = x1.replace(rgx, '$1' + ',' + '$2');
	        }
	        return x1 + x2;
	    }

      //------------------------------------------------------------
      //				            	FUNCIONES AJAX
      function getReporte(){
				$.ajax({
            data: { type:'getReporteMil', data:stat},
            type: "POST",
            url: "reportesCapitulos.api.php",
        })
				.done(function(data){
					data = $.parseJSON(data);		
					console.log('getReporte(Ok)')
					var lista = "";
					var TAut=0; var TMin = 0; var TRei = 0; var TEje = 0; var TDif = 0;
					for(var x=0; x<Object.keys(data).length; x++){

						TAut += parseFloat(data[ Object.keys(data)[x] ]['totAut']);
						TMin += parseFloat(data[ Object.keys(data)[x] ]['min']);
						TRei += parseFloat(data[ Object.keys(data)[x] ]['rei']);
						TEje += parseFloat(data[ Object.keys(data)[x] ]['eje']);
						TDif += parseFloat(data[ Object.keys(data)[x] ]['dif']);

						if(data[ Object.keys(data)[x] ]['dif'] >= 0){
							lista += '<tr class="default">';
						} else {
							lista += '<tr class="danger">';
						}
						lista += "<td>"+ Object.keys(data)[x] +"</td>"+
								"<td>"+ data[ Object.keys(data)[x] ]['nomProy'] +"</td>"+
								"<td>$"+addCommas(parseFloat(data[ Object.keys(data)[x] ]['totAut']).toFixed(2))+"</td>"+
								"<td>$"+ addCommas((data[ Object.keys(data)[x] ]['min']).toFixed(2)) +"</td>"+
								"<td>$"+ addCommas((data[ Object.keys(data)[x] ]['rei']).toFixed(2)) +"</td>"+
								"<td>$"+ addCommas((data[ Object.keys(data)[x] ]['eje']).toFixed(2)) +"</td>";

						if(data[ Object.keys(data)[x] ]['dif'] < 0){
							lista += '<td class="textRed">$'+ addCommas((data[ Object.keys(data)[x] ]['dif']).toFixed(2)) +'</td>';
						} else {
							lista += "<td>$"+ addCommas((data[ Object.keys(data)[x] ]['dif']).toFixed(2)) +"</td>";
						}

						lista += "</tr>";
					}

					$(".tablaBody").html(lista);
					$("#totAut").html( 'Total Autorizado:<br>$' + addCommas(TAut.toFixed(2)) );
					$("#totMin").html( 'Total Ministrado:<br>$' + addCommas(TMin.toFixed(2)) );
					$("#totRei").html( 'Total Reintegros:<br>$' + addCommas(TRei.toFixed(2)) );
					$("#totEje").html( 'Total Ejercido:<br>$' + addCommas(TEje.toFixed(2)) );
					$("#totDif").html( 'Total Diferencia:<br>$' + addCommas(TDif.toFixed(2)) );

					$('.tablaLista').DataTable({
						"retrieve": true,
						"language": { "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json" },
						"paging": false,
						"info": false
					});
				})
				.fail(function(){
          muestraAviso('', '<i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i> &nbsp;&nbsp; No se encuentra la Base de Datos, favor de reintentarlo');
        });
			}
    });
  </script>
</html>
