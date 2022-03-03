<?php
require_once("../funciones.php");	
require_once("../conexionBD.php");
$link=conectarse();
//***Leer variables del sistema******
$estado=mysql_query("select * from general",$link);
$leer= mysql_fetch_array($estado);
//****** Verificamos si existe la cookie *****/
if(isset($_COOKIE['VotaDatAdmin'])) {
	
	//****Agregar nuevo administrador*******
	if (isset($_POST['envia_admin'])) {
		if ((borra_espacios($_POST['usuario_adm'])!="")and(borra_espacios($_POST['nombres_adm'])!="")and(borra_espacios($_POST['apellidos_adm'])!="")and($_POST['clave_adm']!="")) {
			$fusuario_adm=borra_espacios($_POST['usuario_adm']);

			$fnombres_adm=cambia_mayuscula($_POST['nombres_adm']);
			$fapellidos_adm=cambia_mayuscula($_POST['apellidos_adm']);
			if (strlen($_POST['clave_adm']) < 4) {
			        include_once("encabezado.html");
			        print "<strong>La contraseña debe ser como mínimo de 4 caracteres<br />";
			        print"<br /><a href='javascript:history.go(-1)'>Volver al formulario</a></strong></div></body></html>";
			        exit;
			}
			$fclave_adm=md5($_POST['clave_adm']);
		}
		else {
			include_once("encabezado.html");
			print "<strong>Debe llenar todos los campos<br />";
			print"<br /><a href='javascript:history.go(-1)'>Volver al formulario</a></strong></div></body></html>";
			exit;
		}
		
		//*****Validamos que no exista un usuario duplicado**** 
		$duplica=0;
		$resp3=mysql_query("select usuario from administradores",$link);
		while($row3 = mysql_fetch_array($resp3)) {
		        if (cambia_mayuscula($fusuario_adm)==cambia_mayuscula($row3["usuario"])){
		               $duplica=1;
		        }
		}
		if ($duplica==1) {
		        include_once("encabezado.html");
		        print "<strong>Ya existe un administrador con este nombre de usuario<br />";
		        print"<br /><a href='javascript:history.go(-1)'>Volver al formulario</a></strong></div></body></html>";
		        exit;
		}
		
		//******Guardamos los datos en la BD ******
		$cons_sql  = sprintf("INSERT INTO administradores(usuario,nombres,apellidos,password) VALUES(%s,%s,%s,%s)", comillas($fusuario_adm),comillas($fnombres_adm),comillas($fapellidos_adm),comillas($fclave_adm));
		mysql_query($cons_sql,$link);

		//****obtener el id del administrador guardado
		$id_adm=mysql_insert_id($link);

		//******Guardamos los datos de control ******
                $ffecha=date("Y-m-d");
                $fhora=date("G:i:s");
                $fip = $_SERVER['REMOTE_ADDR'];
                $faccion="Admin_Crea_Administrador (id:".$id_adm.")";
                $cons_sql5  = sprintf("INSERT INTO control(c_fecha,c_hora,c_ip,c_accion,c_idest) VALUES(%s,%s,%s,%s,%d)", comillas($ffecha), comillas($fhora), comillas($fip), comillas($faccion),$_COOKIE['VotaDatAdmin']);
mysql_query($cons_sql5,$link);

	}
	//****Actualizar información de administrador*******
	if (isset($_POST['edita_admin'])) {
		if (($_POST['usuario_adm']!="")and($_POST['nombres_adm']!="")and($_POST['apellidos_adm']!="")and($_POST['clave_adm']!="")) {
			$fusuario_adm=borra_espacios($_POST['usuario_adm']);

			$fnombres_adm=cambia_mayuscula(borra_espacios($_POST['nombres_adm']));
			$fapellidos_adm=cambia_mayuscula(borra_espacios($_POST['apellidos_adm']));
			$fclave_adm=md5($_POST['clave_adm']);
		}
		else {
			include_once("encabezado.html");
			print "<strong>Debe llenar todos los campos<br />";
			print"<br /><a href='javascript:history.go(-1)'>Volver al formulario</a></strong></div></body></html>";
			exit;
		}
		//****Actualizar en la BD*******
		$cons_sql3  = sprintf("UPDATE administradores SET usuario=%s, nombres=%s, apellidos=%s, password=%s WHERE id=%d", comillas($fusuario_adm),comillas($fnombres_adm), comillas($fapellidos_adm), comillas($fclave_adm), $_POST['identificador']);
		mysql_query($cons_sql3,$link);
	
		//******Guardamos los datos de control ******
                $ffecha=date("Y-m-d");
                $fhora=date("G:i:s");
                $fip = $_SERVER['REMOTE_ADDR'];
                $faccion="Admin_Actualiza_Administrador (id:".$_POST['identificador'].")";
                $cons_sql5  = sprintf("INSERT INTO control(c_fecha,c_hora,c_ip,c_accion,c_idest) VALUES(%s,%s,%s,%s,%d)", comillas($ffecha), comillas($fhora), comillas($fip), comillas($faccion),$_COOKIE['VotaDatAdmin']);
mysql_query($cons_sql5,$link);
	
	}
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
	echo '<html>';
	echo '<head>';
	echo '<title>'.$leer['institucion'].' - Administradores del sistema</title>';
	echo '<link href="../estilo4.css" rel="stylesheet" type="text/css" />';
	echo '</head>';
	echo '<body>';
	echo '<h1>'.$leer['institucion'].'</h1>';
	echo '<h2>ADMINISTRADORES DEL SISTEMA</h2>';
	echo '<div align="center">';	
	//*****Formulario para agregar administrador *******
	if((isset($_GET['agrega']))and($_GET['agrega']=="ok")) { 
		echo '<form name="addadmin" action="administradores.php" method="post">';
	        echo '<table>';
	        echo '<tr>';
	        echo '<td style="text-align:right;"><label for="usuario_adm">';
	        echo '<strong>Usuario:</strong>';
	        echo '</label></td>';
	        echo '<td><input type="text" name="usuario_adm" size="30" maxlength="50" title="Escriba el nombre de usuario" />';
	        echo '</td></tr>';
	        echo '<tr>';
	        echo '<td style="text-align:right;"><label for="nombres_adm">';
	        echo '<strong>Nombres:</strong>';
	        echo '</label></td>';
	        echo '<td><input type="text" name="nombres_adm" size="30" maxlength="50" title="Escriba los nombres del administrador" />';
	        echo '</td></tr>';
	        echo '<tr>';
	        echo '<td style="text-align:right;"><label for="apellidos_adm">';
	        echo '<strong>Apellidos:</strong>';
	        echo '</label></td>';
	        echo '<td><input type="text" name="apellidos_adm" size="30" maxlength="50" title="Escriba los apellidos del administrador" />';
	        echo '</td></tr>';
	        echo '<tr>';
	        echo '<td style="text-align:right;"><label for="clave_adm">';
	        echo '<strong>Contraseña:</strong>';
	        echo '</label></td>';
	        echo '<td><input type="password" name="clave_adm" size="30" maxlength="30" title="Escriba la contraseña de acceso" />';
	        echo '</td></tr>';

	        echo '<tr><td class="cen" colspan="2"><input type="submit" name="envia_admin" value="Guardar" title="Agregar administrador" />&nbsp&nbsp&nbsp&nbsp';
		echo '<input type="button" name="Cancel" value="Cancelar" onclick="window.location =\'administradores.php\' "/></td></tr>';
		echo '</form></table>';
	}
	else {
		echo '<div class=cen>';
		echo '<strong><a href="administradores.php?agrega=ok" title="Agregar administrador">Agregar administrador</a></strong>';
		echo '</div>';
	}
	
	//*****Formulario para editar administrador *******
	if((isset($_GET['id'])) and ($_GET['id']!=md5(1)) and (isset($_GET['editar'])) and ($_GET['editar']=="ok")) { 
		$resp4=mysql_query(sprintf("select * from administradores where md5(id)=%s",comillas($_GET['id'])),$link);
        	if ($row4 = mysql_fetch_array($resp4)) {	

			echo '<br /><form name="editadmin" action="administradores.php" method="post">';
		       	echo '<table>';
		       	echo '<tr>';
		        echo '<td style="text-align:right;"><label for="usuario_adm">';
		        echo '<strong>Usuario:</strong>';
		        echo '</label></td>';
		        echo '<td><input type="text" name="usuario_adm" value="'.$row4['usuario'].'" size="30" maxlength="50" title="Escriba el nombre de usuario" />';
		        echo '</td></tr>';
		        echo '<tr>';
		        echo '<td style="text-align:right;"><label for="nombres_adm">';
		        echo '<strong>Nombres:</strong>';
		        echo '</label></td>';
		        echo '<td><input type="text" name="nombres_adm" value="'.$row4['nombres'].'" size="30" maxlength="50" title="Escriba los nombres del administrador" />';
		        echo '</td></tr>';
		        echo '<tr>';
		        echo '<td style="text-align:right;"><label for="apellidos_adm">';
		        echo '<strong>Apellidos:</strong>';
		        echo '</label></td>';
		        echo '<td><input type="text" name="apellidos_adm" value="'.$row4['apellidos'].'" size="30" maxlength="50" title="Escriba los apellidos del administrador" />';
		        echo '</td></tr>';
		        echo '<tr>';
		        echo '<td style="text-align:right;"><label for="clave_adm">';
		        echo '<strong>Contraseña:</strong>';
		        echo '</label></td>';
		        echo '<td><input type="password" name="clave_adm" size="30" maxlength="30" title="Escriba la contraseña de acceso" />';
		        echo '</td></tr>';
			echo '<input type="hidden" name="identificador" value="'.$row4['id'].'" />';

		        echo '<tr><td class="cen" colspan="2"><input type="submit" name="edita_admin" value="Guardar" title="Agregar administrador" />&nbsp&nbsp&nbsp&nbsp';
			echo '<input type="button" name="Cancel" value="Cancelar" onclick="window.location =\'administradores.php\' "/></td></tr>';
			echo '</form></table>';
		}
		else {
		      	echo '<table>';
		        echo '<tr><td class="cen"><strong>No hay datos para el administrador</strong></td></tr>';
		        echo '</table>';
		}	
	}
	//******Mostrar mensaje para borrar administrador*******
	if((isset($_GET['id']))and($_GET['id']!=md5(1))and(isset($_GET['elimina']))and($_GET['elimina']=="0")) {
		
		$resp5=mysql_query(sprintf("select usuario from administradores where md5(id)=%s",comillas($_GET['id'])),$link);
	        if ($row5 = mysql_fetch_array($resp5)) {

			echo '<br /><div class="cen"><strong>';
			echo '¿Desea borrar el administrador '.$row5['usuario'].' del sistema? ';
			echo '<a href="administradores.php?id='.$_GET['id'].'&elimina=1" title="Borrar administrador del sistema">Si</a>&nbsp&nbsp&nbsp&nbsp';
			echo '<a href="administradores.php" title="Cancelar la eliminación del administrador">No</a>';
			echo '</strong></div>';
		}
		else {
			echo '<table>';
		        echo '<tr><td class="cen"><strong>No hay datos para el administrador</strong></td></tr>';
		        echo '</table>';
		}
	}
	
	//*****Eliminar administrador******
	if((isset($_GET['id']))and($_GET['id']!=md5(1))and(isset($_GET['elimina']))and($_GET['elimina']=="1")) {
		$resp6=mysql_query(sprintf("select usuario from administradores where md5(id)=%s",comillas($_GET['id'])),$link);
	        $row6 = mysql_fetch_array($resp6);
		$resp2=mysql_query(sprintf("delete from administradores where md5(id)=%s",comillas($_GET['id'])),$link);

		//******Guardamos los datos de control ******
                $ffecha=date("Y-m-d");
                $fhora=date("G:i:s");
                $fip = $_SERVER['REMOTE_ADDR'];
                $faccion="Admin_Borra_Administrador (usuario:".$row6['usuario'].")";
                $cons_sql5  = sprintf("INSERT INTO control(c_fecha,c_hora,c_ip,c_accion,c_idest) VALUES(%s,%s,%s,%s,%d)", comillas($ffecha), comillas($fhora), comillas($fip), comillas($faccion),$_COOKIE['VotaDatAdmin']);
mysql_query($cons_sql5,$link);

	}
	
	//****MUESTRA LA TABLA DE ADMINISTRADORES******
	echo '<br /><table>';
	echo '<thead><tr><th>NOMBRE</th><th colspan="2">OPCIONES</th></tr></thead>';
	$ContAdm=0;
	$resp=mysql_query(sprintf("select * from administradores order by nombres"),$link);
	while($row = mysql_fetch_array($resp)) {
		if ($row['id']!=1) {
			echo '<tr>';
			echo '<td>'.$row['nombres'].' '.$row['apellidos'].' ('.$row['usuario'].')</td>';
			echo '<td class="cen"><a href="administradores.php?id='.md5($row['id']).'&editar=ok" title="Editar administrador"><img src="../iconos/lapiz.png" border="0" width="20px" border="0" alt="Editar" /></a></td>';
			echo '<td class="cen"><a href="administradores.php?id='.md5($row['id']).'&elimina=0" title="Borrar administrador"><img src="../iconos/delete.png" border="0" alt="Borrar" /></a></td></tr>';
			$ContAdm=$ContAdm+1;
		}
	}
	if($ContAdm==0) {
		echo '<tr><td colspan="3"><strong>No existen administradores registrados en el sistema.</strong></td></tr>';
	}
	echo '</table><br />';
	echo '</div>';
	echo '</body>';
	echo '</html>';
}
else {
	include_once("encabezado.html");
      	echo '<table>';
        echo '<tr><td class="cen"><strong>Su sesión ha finalizado, por favor vuelva a ingresar al sistema</strong></td></tr>';
        echo '</table></div></body></html>';
}
mysql_close($link);
?>
