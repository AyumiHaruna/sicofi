		<?php

		/*
	LISTA DE NIVELES PROGRAMADOS HASTA EL MOMENTO
	NIVEL[0] = El nivel 0 pertenece a los usuarios sin registrar, no tienen acceso a reportes ni formularios/altaEgresos
	NIVEL[4] = El nivel 4 pertenece a los usuarios mas básicos quienes solo tienen acceso a reportes
	NIVEL[3] = El nivel 3 pertenece a las validaciones de Egresos, tambien tiene acceso a los reportes
	NIVEL[2] = El nivel 2 pertenece a los autorizados para dar de alta registros en la Base de Datos pero no pueden modificar ningun registro previamente capturado
	NIVEL[1] = El nivel 1 es el nivel mas alto quien tiene acceso a todos los formularios y reportes del sistema, pueden modificar registros de la base de datos

	NIVEL[5] = El nivel 5 tiene acceso al formulario de validaciones de Ingresos, parecido al nivel [2]
*/

			//----------------		Sin registrar usuarios 		--------------------------
			$menu[0] = '	<div class="cuadro">
						<img src="../imagen/logo.jpg" width="200px" height="80px"></img>
						<img src="../imagen/cncpc_logo.jpg" width="300"></img>
						<div id="cssmenu"">
							<ul>
								<li><a href="../index.php">Inicio</a></li>
								<li class="active"><a href="#"><font color="color="424242">Reportes</font></a>	</li>
								<li><a href="#"><font color="color="424242">Formularios</font></a>	</li>
								<li><a href="../contacto.php">Contacto</a></li>
							</ul>
						</div>
						</div>
					<br>';

				//----------------		Usuario nivel 5 Comprobaciones en el sistema 		-----------------
			$menu[5] = '	<div class="cuadro">
						<img src="../imagen/logo.jpg" width="200px" height="80px"></img>
						<img src="../imagen/cncpc_logo.jpg" width="300"></img>
						<div id="cssmenu"">
							<ul>
								<li><a href="../index.php">Inicio</a></li>
								<li class="active"><a href="#">Reportes</a>
									<ul>
										<li><a href="#">Gasto en Proyectos</a>
											<ul>
												<li><a href="#">Proyectos</a>
													<ul>
														<li><a href="../reportes/listaProyectos.php">Lista de Proyectos</a></li>
													</ul>
												</li>
												<li><a href="#">Ingresos</font></a>
													<ul>
														<li><a href="../reportes/listaIngresos.php">Ingresos Desglozados</a></li>
														<li><a href="../reportes/listaReintegros.php">Reintegros Desglozados</a></li>
														<li><a href="../reportes/listaIngresosXProyecto.php">Ingresos Por Proyecto</a></li>
														<li><a href="../reportes/comppart.php">Comparativo de Partidas</a></li>
													</ul>
												</li>
												<li><a href="#">Egresos</a>
													<ul>
														<li><a href="../reportes/listaEgresos.php">Egresos Desglozados</a></li>
														<li><a href="../reportes/listaEgresosXProyecto.php">Egresos Por Proyecto</a></li>
													</ul>
												<li><a href="../reportes/reportescapitulos.php">Reportes por Cap&iacute;tulos</a></li>
											</ul>
										</li>
									</ul>
								</li>
								<li><a href="#">Formularios</a>
									<ul>
										<li><a href="#">Gasto en Proyectos</a>
											<ul>
												<li><a href="#">Ingresos</a>
													<ul>
														<li><a href="comp1.php">Comprobaciones en el Sistema</a></li>
													</ul>
												</li>

												<li><a href="#">Egresos</a>
													<ul>
														<li><a href="validaEgresos.php">Validacion de Egresos</a></li>
													</ul>
												</li>

											</ul>
										</li>
									</ul>
								</li>
								<li><a href="../contacto.php">Contacto</a></li>
							</ul>
						</div>
						</div>
					<br>';

		//----------------		Usuario nivel 4 solo acceso a reportes 		-----------------
		$menu[4] = '	<div class="cuadro">
						<img src="../imagen/logo.jpg" width="200px" height="80px"></img>
						<img src="../imagen/cncpc_logo.jpg" width="300"></img>
						<div id="cssmenu"">
							<ul>
								<li><a href="../index.php">Inicio</a></li>
								<li class="active"><a href="#">Reportes</a>
									<ul>
										<li><a href="#">Gasto en Proyectos</a>
											<ul>
												<li><a href="#">Proyectos</a>
													<ul>
														<li><a href="../reportes/listaProyectos.php">Lista de Proyectos</a></li>
													</ul>
												</li>
												<li><a href="#">Ingresos</font></a>
													<ul>
														<li><a href="../reportes/listaIngresos.php">Ingresos Desglozados</a></li>
														<li><a href="../reportes/listaReintegros.php">Reintegros Desglozados</a></li>
														<li><a href="../reportes/listaIngresosXProyecto.php">Ingresos Por Proyecto</a></li>
														<li><a href="../reportes/comppart.php">Comparativo de Partidas</a></li>
													</ul>
												</li>
												<li><a href="#">Egresos</a>
													<ul>
														<li><a href="../reportes/listaEgresos.php">Egresos Desglozados</a></li>
														<li><a href="../reportes/listaEgresosXProyecto.php">Egresos Por Proyecto</a></li>
													</ul>
												<li><a href="../reportes/reportescapitulos.php">Reportes por Cap&iacute;tulos</a></li>
											</ul>
										</li>
									</ul>
								</li>
								<li><a href="#">Formularios</a>
								</li>
								<li><a href="../contacto.php">Contacto</a></li>
							</ul>
						</div>
						</div>
					<br>';

		//----------------		Usuario nivel 3 Acceso a reportes y Validaciones de Egresos	-----------------
		$menu[3] = '	<div class="cuadro">
						<img src="../imagen/logo.jpg" width="200px" height="80px"></img>
						<img src="../imagen/cncpc_logo.jpg" width="300"></img>
						<div id="cssmenu"">
							<ul>
								<li><a href="../index.php">Inicio</a></li>
								<li class="active"><a href="#">Reportes</a>
									<ul>
										<li><a href="#">Gasto en Proyectos</a>
											<ul>
												<li><a href="#">Proyectos</a>
													<ul>
														<li><a href="../reportes/listaProyectos.php">Lista de Proyectos</a></li>
													</ul>
												</li>
												<li><a href="#">Ingresos</font></a>
													<ul>
														<li><a href="../reportes/listaIngresos.php">Ingresos Desglozados</a></li>
														<li><a href="../reportes/listaReintegros.php">Reintegros Desglozados</a></li>
														<li><a href="../reportes/listaIngresosXProyecto.php">Ingresos Por Proyecto</a></li>
														<li><a href="../reportes/comppart.php">Comparativo de Partidas</a></li>
														<li><a href="../reportes/comppart.php">Comparativo de Partidas</a></li>
													</ul>
												</li>
												<li><a href="#">Egresos</a>
													<ul>
														<li><a href="../reportes/listaEgresos.php">Egresos Desglozados</a></li>
														<li><a href="../reportes/listaEgresosXProyecto.php">Egresos Por Proyecto</a></li>
													</ul>
												<li><a href="../reportes/reportescapitulos.php">Reportes por Cap&iacute;tulos</a></li>
											</ul>
										</li>
										<li><a href="#">Gasto B&aacute;sico</a>
											<ul>
												<li><a href="../reportes/gblistaIngresos.php">GB Ingresos Desglozados</a></li>
												<li><a href="../reportes/gblistaEgresos.php">GB Egresos Desglozados</a></li>
												<li><a href="../reportes/gbcompleto.php">GB Disponible</a></li>
											</ul>
										</li>
										<li><a href="#">Gasto N&oacute;mina</a>
											<ul>
												<li><a href="../reportes/nmlistaIngresos.php">NM Ingresos Desglozados</a></li>
												<li><a href="../reportes/nmlistaEgresos.php">NM Egresos Desglozados</a></li>
												<li><a href="../reportes/nmcompleto.php">NM Disponible</a></li>
											</ul>
										</li>
									</ul>
								</li>
								<li><a href="#">Formularios</a>
									<ul>
										<li><a href="#">Gasto en Proyectos</a>
											<ul>
												<li><a href="#">Ingresos</a>
													<ul>
														<li><a href="validaSF.php"> Validaciones de SF  </a></li>
														<li><a href="compIngresos1.php"> Comprobaci&oacute;n de Ingresos </a></li>
													</ul>
												</li>
												<li><a href="#">Egresos</a>
													<ul>
														<li><a href="validaEgresos.php">Validacion de Egresos</a></li>
													</ul>
												</li>
											</ul>
										</li>
									</ul>
								</li>
								<li><a href="../contacto.php">Contacto</a></li>
							</ul>
						</div>
						</div>
					<br>';


		//----------------		Usuario nivel 2 Acceso a Reportes, Validaciones y Alta  	-----------------
		$menu[2] = '	<div class="cuadro">
						<img src="../imagen/logo.jpg" width="200px" height="80px"></img>
						<img src="../imagen/cncpc_logo.jpg" width="300"></img>
						<div id="cssmenu"">
							<ul>
								<li><a href="../index.php">Inicio</a></li>
								<li class="active"><a href="#">Reportes</a>
									<ul>
										<li><a href="#">Gasto en Proyectos</a>
											<ul>
												<li><a href="#">Proyectos</a>
													<ul>
														<li><a href="../reportes/listaProyectos.php">Lista de Proyectos</a></li>
													</ul>
												</li>
												<li><a href="#">Ingresos</font></a>
													<ul>
														<li><a href="../reportes/listaIngresos.php">Ingresos Desglozados</a></li>
														<li><a href="../reportes/listaReintegros.php">Reintegros Desglozados</a></li>
														<li><a href="../reportes/listaIngresosXProyecto.php">Ingresos Por Proyecto</a></li>
														<li><a href="../reportes/comppart.php">Comparativo de Partidas</a></li>
													</ul>
												</li>
												<li><a href="#">Egresos</a>
													<ul>
														<li><a href="../reportes/listaEgresos.php">Egresos Desglozados</a></li>
														<li><a href="../reportes/listaEgresosXProyecto.php">Egresos Por Proyecto</a></li>
													</ul>
												<li><a href="../reportes/reportescapitulos.php">Reportes por Cap&iacute;tulos</a></li>
											</ul>
										</li>
									</ul>
								</li>
								<li><a href="#">Formularios</a>
								</li>
								<li><a href="../contacto.php">Contacto</a></li>
							</ul>
						</div>
						</div>
					<br>';

		//----------------		Usuario nivel 1 SU  	-----------------
		$menu[1] = '	<div class="cuadro">
						<img src="../imagen/logo.jpg" width="200px" height="80px"></img>
						<img src="../imagen/cncpc_logo.jpg" width="300"></img>
						<div id="cssmenu"">
							<ul>
								<li><a href="../index.php">Inicio</a></li>
								<li class="active"><a href="#">Reportes</a>
									<ul>
										<li><a href="#">Gasto en Proyectos</a>
											<ul>
												<li><a href="#">Proyectos</a>
													<ul>
														<li><a href="../reportes/listaProyectos.php">Lista de Proyectos</a></li>
													</ul>
												</li>
												<li><a href="#">Ingresos</font></a>
													<ul>
														<li><a href="../reportes/listaIngresos.php">Ingresos Desglozados</a></li>
														<li><a href="../reportes/listaReintegros.php">Reintegros Desglozados</a></li>
														<li><a href="../reportes/listaIngresosXProyecto.php">Ingresos Por Proyecto</a></li>
														<li><a href="../reportes/sf.php">Solicitudes de Fondos</a></li>
														<li><a href="../reportes/comppart.php">Comparativo de Partidas</a></li>
													</ul>
												</li>
												<li><a href="#">Egresos</a>
													<ul>
														<li><a href="../reportes/listaEgresos.php">Egresos Desglozados</a></li>
														<li><a href="../reportes/listaEgresosXProyecto.php">Egresos Por Proyecto</a></li>
													</ul>
												<li><a href="../reportes/reportescapitulos.php">Reportes por Cap&iacute;tulos</a></li>
											</ul>
										</li>
										<li><a href="#">Gasto B&aacute;sico</a>
											<ul>
												<li><a href="../reportes/gblistaIngresos.php">GB Ingresos Desglozados</a></li>
												<li><a href="../reportes/gblistaEgresos.php">GB Egresos Desglozados</a></li>
												<li><a href="../reportes/gbcompleto.php">GB Disponible</a></li>
											</ul>
										</li>
										<li><a href="#">Gasto N&oacute;mina</a>
											<ul>
												<li><a href="../reportes/nmlistaIngresos.php">NM Ingresos Desglozados</a></li>
												<li><a href="../reportes/nmlistaEgresos.php">NM Egresos Desglozados</a></li>
												<li><a href="../reportes/nmcompleto.php">NM Disponible</a></li>
											</ul>
										</li>
									</ul>
								</li>
								<li><a href="#">Formularios</a>
									<ul>

										<li><a href="#">Gasto en Proyectos</a>
											<ul>
												<li><a href="#">Proyectos</a>
													<ul>
														<li><a href="altaProyectos.php">Alta de proyectos</a></li>
														<li><a href="editaProyectos.php">Edita Proyectos</a></li>
													</ul>
												</li>
												<li><a href="#">Ingresos</a>
													<ul>
														<li><a href="SFalta.php"> Solicitudes de Fondos </a></li>
														<li><a href="altaReintegros.php">Registro de Reintegros</a></li>
														<li><a href="altaGbNm.php">Registro de Gasto Basico / Nómina</a></li>
														<li><a href="editaIngresos.php">Modifica SF</a></li>
														<li><a href="validaSF.php"> Validaciones de SF  </a></li>
														<li><a href="compIngresos1.php"> Comprobaci&oacute;n de Ingresos </a></li>
														<li><a href="comp1.php">Comprobaciones en el Sistema</a></li>
													</ul>
												</li>
												<li><a href="#">Egresos</a>
													<ul>
														<li><a href="altaEgresos.php">Registro de Egresos</a></li>
														<li><a href="editaEgresos.php">Edita Egresos</a></li>
														<li><a href="cancelaEgresos.php">Cancelaci&oacute;n Egresos</a></li>
														<li><a href="validaEgresos.php">Validacion de Egresos</a></li>
													</ul>
												</li>
												<li><a href="impresionSF.php">Imp. Solicitudes de Fondos</a></li>
												<li><a href="impresionChePol.php">Imp. Cheques y P&oacute;lizas</a></li>
											</ul>
										</li>

										<li><a href="#">Gasto B&aacute;sico</a>
											<ul>
												<li><a href="#">Ingresos GB</a>
													<ul>
														<li><a href="GBaltaIngresos.php">Registro de Ingresos GB</a></li>
														<li><a href="GBeditaIngresos.php">Edita Ingresos GB</a></li>
														<li><a href="GBvalidaIngresos.php">Validaci&oacute;n de Ingresos GB</a></li>
													</ul>
												</li>
												<li><a href="#">Egresos GB</a>
													<ul>
														<li><a href="GBaltaEgresos.php">Registro de Egresos GB</a></li>
														<li><a href="GBeditaEgresos.php">Edita Egresos GB</a></li>
														<li><a href="GBcancelaEgresos.php">Cancelaci&oacute;n Egresos GB</a></li>
														<li><a href="GBvalidaEgresos.php">Validacion de Egresos GB</a></li>
													</ul>
												</li>
												<li><a href="GBimpresionChePol.php">Imp. Cheques y P&oacute;lizas GB</a></li>
											</ul>
										</li>
										<li><a href="#">Gasto en N&oacute;mina</a>
											<ul>
												<li><a href="#">Ingresos NM</a>
													<ul>
														<li><a href="NMaltaIngresos.php">Registro de Ingresos NM</a></li>
														<li><a href="NMeditaIngresos.php">Edita Ingresos NM</a></li>
													</ul>
												</li>
												<li><a href="#">Egresos NM</a>
													<ul>
														<li><a href="NMaltaEgresos.php">Registro de EgresosNM</a></li>
														<li><a href="NMeditaEgresos.php">Edita Egresos NM</a></li>
														<li><a href="NMcancelaEgresos.php">Cancelaci&oacute;n Egresos NM</a></li>
													</ul>
												</li>
												<li><a href="NMimpresionChePol.php">Imp. Cheques y P&oacute;lizas NM</a></li>
											</ul>
										</li>
									</ul>
								</li>
								<li><a href="../contacto.php">Contacto</a></li>
							</ul>
						</div>
						</div>
					<br>';
?>
