<?php
    //--------------------------------------------------------------
    //                    STARTING PHP FUNCTION
    //--------------------------------------------------------------

    //-- REQUIRE CONFIG

    require_once('../config.php');
    require_once('menu.php');
    header('Content-Type: text/html; charset=UTF-8');
    session_start();

    //--------------------------------------------------------------
    //                    CONSULTAS A LA BASE DE DATOS
    //--------------------------------------------------------------
    //connection instance
    $con = mysqli_connect($server, $user, $pass, $database.$_SESSION['anio']) or die("Problemas con la conexi&oacute;n a la base de datos");
    $con->query("SET NAMES 'utf8'");

    //get first S.F. register
    $primero = mysqli_query($con, "SELECT noSolFon FROM ingresos ORDER BY noSolFon LIMIT 1") or die(mysqli_error($con));
    while($reg = mysqli_fetch_array($primero)) {   $first = $reg['noSolFon']; }

    //get las S.F. register
    $ultimo = mysqli_query($con, "SELECT noSolFon FROM ingresos ORDER BY noSolFon DESC LIMIT 1") or die(mysqli_error($con));
    while($ult = mysqli_fetch_array($ultimo)) {  $last = $ult['noSolFon'];  }

    //get proyect list
    $proyectos = mysqli_query($con, "SELECT nombreProyecto, numeroProyecto FROM proyectos ORDER BY numeroProyecto") or die(mysqli_error($con));

    //get lista de partidasSF
    $partidas = mysqli_query($con, "SELECT noPartida, nomPartida FROM zlistapartidas ORDER BY noPartida")
    or die(mysqli_error($con));

    //get SF lista
    $ingresos  = mysqli_query($con, "SELECT ing.noSolFon, ing.numProy, ing.concepto,
                      ing.fechaElab, ing.SFtotal, pro.nombreProyecto
                      FROM ingresos AS ing
                      INNER JOIN proyectos AS pro
                      ON	ing.numProy = pro.numeroProyecto
                      ORDER BY ing.noSolFon") or die(mysqli_error($con));


    mysqli_close($con);
 ?>


<!DOCTYPE html>
<html lang="en" dir="ltr">
	<head>
		<meta charset="utf-8">
		<title> Alta Solicitudes de Fondo </title>
		<script type="text/javascript" src="../js/jquery-3.1.1.js"></script>
		<link rel="stylesheet" href="../css3/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="../css2/menu.css"></link>
		<link rel="stylesheet" href="../css3/sfStyle.css">
    <link rel="stylesheet" href="../css3/datatables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <script type="text/javascript" src="../js/jquery.dataTables.min.js"></script>
		<script src="../js/bootstrap.min.js"></script>
	</head>
	<body>
		<?php
				//--------------------------------------------------------------
				//                     INVOKE THE MENU
				//--------------------------------------------------------------
				//revisa si el usuario esta logueado
				//sino esta logueado lo redirecciona al index
				if($_SESSION == NULL)
				{
					// echo '<script languaje="javascript">
					//     alert("Area restringida, redireccionando...");
					//     location.href="../index.php";
					//     </script>';
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

				//                        MENU END
				//--------------------------------------------------------------
		?>

		<div class="container-fluid">
      <div class="row">
        <div class="col-md-10 col-md-offset-1 block_header noPointer">
          Imprimir rango de Solicitudes de Fondos
        </div>
        <div class="col-md-10 col-md-offset-1 block_square block_search">
          <div class="row">
            <div class="col-md-10 col-md-offset-1">
              <div class="row">
                <div class="col-md-3 text-right">
                  Imprimir S.F.
                </div>
                <div class="col-md-3 text-center">
                  de:
                  <input type="text" name="" class="form-control searchInput" id="de" onkeypress="return justNumbers(event);" value="<?php echo $first ?>">
                </div>
                <div class="col-md-3 text-center">
                  a:
                  <input type="text" name="" class="form-control searchInput" id="hasta" onkeypress="return justNumbers(event);" value="<?php echo $last ?>">
                </div>
                <div class="col-md-3">
                  <button type="button" name="button" class="actionBtn" id="buscar"> <span class="glyphicon glyphicon-print"></span> &nbsp;&nbsp; Imprimir </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

			<div class="row">
        <div class="col-md-10 col-md-offset-1 block_header noPointer">
          Lista de Solicitudes de Fondos
        </div>
        <div class="col-md-10 col-md-offset-1 block_square">
          <div class="table-responsive">
            <table id="myTable" class="table table-striped">
              <thead>
                <th>#S.F.</th>
                <th>#Proyecto</th>
                <th>Nombre del Proyecto</th>
                <th>Concepto</th>
                <th>Fecha de Elaboración</th>
                <th>ImporteTotal</th>
                <th>Imprimir</th>
              </thead>
              <tbody>
                <?php
                  while ($reg = mysqli_fetch_array ($ingresos))
                  {
                    echo '<tr>
                        <td class="text-right">'.$reg['noSolFon'].'</td>
                        <td class="text-right">'.$reg['numProy'].'</td>
                        <td>'.$reg['nombreProyecto'].'</td>
                        <td>'.$reg['concepto'].'</td>
                        <td class="text-right">'.$reg['fechaElab'].'</td>
                        <td class="text-right">$'.number_format($reg['SFtotal'], 2,'.',',').'</td>
                        <td class="text-center"><a href="http://172.26.26.126/sicofi/formularios/impresionSF2.php?start='.$reg['noSolFon'].'" target="_blank"><button class="demi_btn"><span class="glyphicon glyphicon-print"></span></button></a></td>
                      </tr>';
                  }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
		</div>
	</body>

  <script type="text/javascript">
    $(document).ready(function() {
      //start datatables
      $('#myTable').DataTable({
        "lengthMenu": [[50, 100, -1], [50, 100, "All"]],
        "language": {
            "lengthMenu": "Mostrar _MENU_ registros por página",
            "zeroRecords": "Lista vacia",
            "info": "Mostrando página _PAGE_ de _PAGES_",
            "infoEmpty": "Lista vacia",
            "infoFiltered": "(filtered from _MAX_ total records)",
            "search": "Buscar:"
        },
        "order": [[ 0, "desc" ]]
      });

      //click on search
      $("#buscar").click(function(){
        if ( $("#de").val() == "" ) {
          return alert("Seleccione la S.F. inicial");
        }
        if ( $("#hasta").val() == "" ) {
          return alert("Seleccione la S.F. final");
        }
        if ( parseInt($("#de").val()) > parseInt( $("#hasta").val() ) )
        {
          return alert("El valor de inicio debe ser menor que el final");
        }

        window.location.href = "http://172.26.26.126/sicofi/formularios/impresionSF2.php?start="+ $("#de").val()+"&end="+$("#hasta").val();

      });
    });


    //allow just numbers
    function justNumbers(e)
    {
        var keynum = window.event ? window.event.keyCode : e.which;
        if ((keynum == 8) || (keynum == 46))
        return true;

        return /\d/.test(String.fromCharCode(keynum));
    }
  </script>
</html>
