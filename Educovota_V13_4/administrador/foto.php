<?php

require_once("../funciones.php");
require_once("../conexionBD.php");
$link=conectarse();
//****** Verificamos si existe la cookie *****/
if(isset($_COOKIE['VotaDatAdmin'])) {
if(isset($_GET['id'])) {
        echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
        echo '<html>';
        echo '<head>';
        echo '<style type="text/css" media="print"> .nover {display:none}</style>';
        echo '<title>Actualización de fotografía</title>';
        echo '<link href="../estilo2.css" rel="stylesheet" type="text/css" />';
        echo '</head>';
        echo '<body>';
	include_once("../java.html");
        $resp=mysql_query(sprintf("select * from candidatos where md5(candidatos.id)=%s",comillas($_GET['id'])),$link);
        if ($row = mysql_fetch_array($resp)) {

		$max_tamano=4048576;  //4 MB 
		$dir_imagenes = "../fotos";
		$nombre_imagen = $row['id'];
		if (isset($_POST['envia_img'])) {
			//Datos del arhivo
			$tamano_archivo = $_FILES['userfile']['size']; 
			$archivo_tmp = $_FILES['userfile']['tmp_name']; 
			$tipo_archivo=getimagesize($archivo_tmp);

			if ($tipo_archivo[2]==1) {
				$extension=".gif";
			}
			if ($tipo_archivo[2]==2) {
				$extension=".jpg";
			}
			if ($tipo_archivo[2]==3) {
				$extension=".png";
			}

			//Se verifica si las características del archivo son las correctas 
			if (($tipo_archivo[2] == 1) || ($tipo_archivo[2] == 2) || ($tipo_archivo[2] == 3)) {
				if ($tamano_archivo < $max_tamano) { 
			   		if (move_uploaded_file($archivo_tmp, "$dir_imagenes/$nombre_imagen$extension")){ 
						escala($dir_imagenes.'/'.$nombre_imagen.$extension,130);
      					echo '<div class="cen"><h2 class="txtinicial">La imagen ha sido actualizada correctamente.</h2><br /><br />';
						echo '<input type=button value="Cerrar" onclick="CerrarVentana()"></div>';
						//***Borrar imágenes (si existen) de diferente tipo.
                        if ($tipo_archivo[2]==1) {
							if (file_exists($dir_imagenes."/".$nombre_imagen.".jpg")) unlink($dir_imagenes."/".$nombre_imagen.".jpg");
                            if (file_exists($dir_imagenes."/".$nombre_imagen.".png")) unlink($dir_imagenes."/".$nombre_imagen.".png");
                        }
                        if ($tipo_archivo[2]==2) {
                            if (file_exists($dir_imagenes."/".$nombre_imagen.".gif")) unlink($dir_imagenes."/".$nombre_imagen.".gif");
                            if (file_exists($dir_imagenes."/".$nombre_imagen.".png")) unlink($dir_imagenes."/".$nombre_imagen.".png");
                        }
                        if ($tipo_archivo[2]==3) {
                            if (file_exists($dir_imagenes."/".$nombre_imagen.".jpg")) unlink($dir_imagenes."/".$nombre_imagen.".jpg");
                            if (file_exists($dir_imagenes."/".$nombre_imagen.".gif")) unlink($dir_imagenes."/".$nombre_imagen.".gif");
                        }

		   			}
					else { 
						include_once("encabezado.html");
	    		 		echo "<strong>Ocurrió algún error al subir la imagen. No pudo guardarse.</strong><br />(Verifique que el directorio <u>fotos</u> tiene habilitado los permisos de lectura, escritura y ejecución en su sistema)."; 
						echo '<br /><br /><input type=button value="Cerrar" onclick="CerrarVentana()"></div>';
		   			} 
				}
				else {
					include_once("encabezado.html");
					echo "<strong>La imagen que desea subir sobrepasa el tamaño máximo de 4MB.";
        				echo "<br /><a href='javascript:history.go(-1)'>Volver a subir una nueva imagen</a></strong></div>";
				}
			} 
			else {
				echo '<div class="cen"><h2 class="txtinicial">Solamente puede subir imágenes de tipo jpg, gif o png.<br /> (recuerde que el tamaño máximo de la imagen es de 4MB)</h2>';
        			echo "<br /><strong><a href='javascript:history.go(-1)'>Volver a subir una nueva imagen</a></strong></div>";
			}
		}
		else {
			echo '<h2>'.$row['nombres'].' '.$row['apellidos'].'</h2>';
			//Muestra foto del estudiante, si no existe muestra imagen sinfoto.png
			$sinfoto=1;
			if (file_exists($dir_imagenes."/".$nombre_imagen.".gif")) {
				escala($dir_imagenes.'/'.$nombre_imagen.'.gif',100);
				$sinfoto=0;
			}
			if ($sinfoto==1) {
			if (file_exists($dir_imagenes."/".$nombre_imagen.".jpg")) {
				escala($dir_imagenes.'/'.$nombre_imagen.'.jpg',100);
				$sinfoto=0;
			}
			}
			if ($sinfoto==1) {
			if (file_exists($dir_imagenes."/".$nombre_imagen.".png")) {
				escala($dir_imagenes.'/'.$nombre_imagen.'.png',100);
				$sinfoto=0;
			}
			}
			if ($sinfoto==1) {
				escala($dir_imagenes.'/sinfoto.png',100);
			}
			// Muestra formulario para subir foto
			echo '<table class="cen"><tr><td>';
			echo '<form enctype="multipart/form-data" action="foto.php?id='.$_GET['id'].'" method="POST">';
			//echo '<input type="hidden" name="MAX_FILE_SIZE" value="1948576" />';
			echo 'Seleccionar imagen: <input name="userfile" type="file" />';
			echo '<input type="submit" name="envia_img" value="Enviar" />';
			echo '</form>';
			echo '</td></tr></table>';
			echo '<br /><h2 class="txtinicial cen">(El tamaño máximo para la imagen son 4MB)</h2>';
		}

	}
        else {
                echo '<table>';
                echo '<tr><td class="cen" colspan="2"><strong>No hay datos para este candidato</strong></td></tr>';
                echo '</table>';
        }
        echo '</body></html>';
}
}
mysql_close($link);

?>
