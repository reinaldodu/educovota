<?php
require_once("../funciones.php");
require_once("../conexionBD.php");
$link=conectarse();
//***Leer variables del sistema******
$estado=mysql_query("select * from general",$link);
$leer= mysql_fetch_array($estado);

//****** Verificamos si existe la cookie *****/
if(isset($_COOKIE['VotaDatAdmin'])) {
if (!isset($_POST['envia_csv'])) {
	// Muestra formulario para subir el archivo CSV
        echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
        echo '<html>';
        echo '<head>';
        echo '<title>'.$leer['institucion'].' - Importar archivo</title>';
        echo '<link href="../estilo4.css" rel="stylesheet" type="text/css" />';
        echo '</head>';
        echo '<body>';
	include_once("../java.html");
    echo '<div align="center">';
	echo "<h2>Importar datos desde archivo CSV</h2>";
	echo '<br /><table><tr><td><strong>Los campos para el archivo CSV son:</strong>';
    echo '<ol>';
    echo '<li>Grado del estudiante (numérico): Debe coincidir con el número del grado que aparece en el sistema.</li>';
    echo'<li>Nombres.</li>';
    echo '<li>Apellidos.</li>';
    echo '<li>Documento (sin puntos ni comas).</li>';
    echo '<li>Contraseña (opcional).</li>';
    echo '</ol>';
	echo '<strong><u>Tenga en cuenta que al importar el archivo CSV, se borrarán los datos de votación y los estudiantes existentes.</u></strong></td></tr></table><br />';
	echo '<table><tr><td>';
	echo '<form enctype="multipart/form-data" action="importar.php" method="POST">';
	echo '<strong>Seleccionar archivo csv:</strong> <input name="userfile" type="file" />';
	echo '<br /><br /><input type="submit" name="envia_csv" value="Importar archivo" />';
	echo '</form>';
	echo '</td></tr></table>';
	echo '<p style="font-size:10px; text-align:center;">(El tamaño máximo del archivo CSV es de 10MB)</p>';
    echo '</div></body></html>';
}
else {
	$max_tamano=10485760;  //10 MB 
	$dir_csv = "csv";
	$nombre_csv = "estudiantes.csv";
	if (isset($_POST['envia_csv'])) {
		//Datos del arhivo
		$tamano_archivo = $_FILES['userfile']['size']; 
		$archivo_tmp = $_FILES['userfile']['tmp_name']; 
		$tipo_archivo = $_FILES['userfile']['type']; 
		//Se verifica si las características del archivo son las correctas 
		if (($tipo_archivo == "application/vnd.ms-excel")||($tipo_archivo == "text/csv")||($tipo_archivo=="text/comma-separated-values")) {
			if ($tamano_archivo < $max_tamano) { 
		   		if (move_uploaded_file($archivo_tmp, "$dir_csv/$nombre_csv")){ 
					echo '<div class="cen">';
					if (($gestor = fopen("csv/estudiantes.csv", "r")) !== FALSE) {
						if (($gestor2 = fopen("csv/estudiantes.csv", "r")) !== FALSE) {
							$data2=fgetcsv($gestor2, 1000, ";");
							fclose($gestor2);
						}
						if((count($data2)>3)and(count($data2)<6)) {
							$fila=0;
							//Elimina la tabla estudiantes
							$cons_sql  = "DROP TABLE estudiantes";
					                mysql_query($cons_sql,$link);
							//Crea la tabla estudiantes
							$cons_sql = "CREATE TABLE estudiantes (
								id int(11) NOT NULL AUTO_INCREMENT,
								grado int(11) NOT NULL,
								nombres varchar(50) NOT NULL,
								apellidos varchar(50) NOT NULL,
								documento varchar(30) NOT NULL,
								clave varchar(100) NOT NULL,
								PRIMARY KEY (id))";
					                mysql_query($cons_sql,$link);
							//Elimina la tabla voto
							$cons_sql  = "DROP TABLE voto";
					                mysql_query($cons_sql,$link);
							//Crea la tabla voto
							$cons_sql="CREATE TABLE voto (
								id int(11) NOT NULL AUTO_INCREMENT,
								id_estudiante int(11) NOT NULL,
								candidato int(11) NOT NULL,
								PRIMARY KEY (id))";
					                mysql_query($cons_sql,$link);
							//Elimina la tabla de control
							$cons_sql  = "DROP TABLE control";
					                mysql_query($cons_sql,$link);
							//Crea la tabla de control
							$cons_sql="CREATE TABLE control (
								id int(11) NOT NULL AUTO_INCREMENT,
								c_fecha date NOT NULL,
								c_hora time NOT NULL,
								c_ip varchar(20) NOT NULL,
								c_accion varchar(50) NOT NULL,
								c_idest int(11) NOT NULL,
								PRIMARY KEY (id))";
					                mysql_query($cons_sql,$link);
							while (($datos = fgetcsv($gestor, 1000, ";")) !== FALSE) {
								$fila=$fila+1;
								//Si no existe clave la guarda en blanco
								if (count($datos)==4) $datos[]="";
								//insertar registros en la tabla estudiantes
								$cons_sql  = "INSERT INTO estudiantes (grado, nombres, apellidos, documento, clave) VALUES ('$datos[0]', '$datos[1]', '$datos[2]', '$datos[3]', md5('$datos[4]'))";
						               	mysql_query($cons_sql,$link);
							}
							include_once("encabezado2.html");
							echo "<h2>Carga exitosa</h2>";
							echo "<strong>Los datos fueron cargados correctamente.</strong><br /><br />";
							echo "Número de registros leidos...".$fila."<br />";
							echo "Tamaño del archivo...".$tamano_archivo." bytes<br /><br /></div>";
						}
						else {
								include_once("encabezado.html");
								echo "<h2>Error</h2>";
    		 					echo "<strong>El archivo CSV debe contener mínimo cuatro (4) y máximo cinco (5) campos por registro<br />(grado, nombres, apellidos, documento y clave -opcional-).<br /> También debe tener en cuenta que el caracter separador de campos en el archivo CSV, sea punto y coma(;).<br /> Por favor verifíque de nuevo su archivo CSV.</strong><br /><br /></div>"; 
						}
					}
					include_once("../java.html");
					echo '<input type=button value="Cerrar" onclick="CerrarVentana()"></div>';
	   			}
				else { 
					include_once("encabezado.html");
						echo "<h2>Error</h2>";
    		 			echo "<strong>Ocurrió algún error al intentar subir el archivo.  Verifique la estructura de su archivo CSV o si tiene permisos de lectura, escritura y ejecución para el directorio csv.</strong>"; 
       					echo "<br /><a href='javascript:history.go(-1)'>Volver a subir un nuevo archivo</a></strong></div>";
	   			} 
			}
			else {
				include_once("encabezado.html");
				echo "<h2>Error</h2>";
				echo "<strong>El archivo que desea subir sobrepasa el tamaño máximo de 10MB.";
       				echo "<br /><a href='javascript:history.go(-1)'>Volver a subir un nuevo archivo</a></strong></div>";
			}
		} 
		else {
			include_once("encabezado.html");
			echo "<h2>Error</h2>";
			echo '<div class="cen"><h2 class="txtinicial">Solamente puede subir archivos de tipo csv.<br /></h2>';
       			echo "<br /><strong><a href='javascript:history.go(-1)'>Volver a subir un nuevo archivo</a></strong></div>";
		}
	}
}
}
else {
        include_once("encabezado.html");
        echo '<table>';
        echo '<tr><td class="cen"><strong>Su sesión ha finalizado, por favor vuelva a ingresar al sistema</strong></td></tr>';
        echo '</table></div></body></html>';
}
mysql_close($link);

?>
