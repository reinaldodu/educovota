<?php
require_once("../funciones.php");	
require_once("../conexionBD.php");
$link=conectarse(); 
//****** Verificamos si existe la cookie *****/
if(isset($_COOKIE['ClermontDatAdmin'])) {
if (!isset($_POST['envia_perfil'])) {	
	include_once("perfil.html");	
}
else {	

//*****************************************************
// VALIDAMOS ALGUNOS VALORES EN LA BD ANTES DE GUARDAR
//*****************************************************

//Validar los campos requeridos

if ($_POST['admusuario']!="") {
	$fadmusuario=$_POST['admusuario'];
}
else {
	include_once("encabezado.html");
	print "<strong>No ha escrito el nombre de usuario<br />";
	print"<br /><a href='javascript:history.go(-1)'>Volver al formulario</a></strong></div></body></html>";
	exit;
}
if ($_POST['admnombres']!="") {
	$fadmnombres=$_POST['admnombres'];
}
else {
	include_once("encabezado.html");
	print "<strong>No ha escrito los nombres<br />";
	print"<br /><a href='javascript:history.go(-1)'>Volver al formulario</a></strong></div></body></html>";
	exit;
}

if ($_POST['admapellidos']!="") {
	$fadmapellidos=$_POST['admapellidos'];
}
else {
	include_once("encabezado.html");
	print "<strong>No ha escrito los apellidos<br />";
	print"<br /><a href='javascript:history.go(-1)'>Volver al formulario</a></strong></div></body></html>";
	exit;
}

//********************************
// GUARDAMOS LOS DATOS EN LA BD
//********************************

$cons_sql  = sprintf("UPDATE administradores SET usuario=%s, adm_nombres=%s, adm_apellidos=%s WHERE id=%d", comillas($fadmusuario), comillas($fadmnombres), comillas($fadmapellidos), $_POST['admidentifica']);
mysql_query($cons_sql,$link);

//******Guardamos los datos de control ******
$ffecha=date("Y-m-d");
$fhora=date("G:i:s");
$fip = $_SERVER['REMOTE_ADDR']; 
$faccion="Admin_CambioPerfil";
$cons_sql2  = sprintf("INSERT INTO control(c_fecha,c_hora,c_ip,c_accion,c_idest) VALUES(%s,%s,%s,%s,%d)", comillas($ffecha), comillas($fhora), comillas($fip), comillas($faccion),$_POST['admidentifica']);
mysql_query($cons_sql2,$link);
include_once("confirma2.html");	
mysql_close($link);
}
}
else {
        include_once("encabezado.html");
        echo '<table>';
        echo '<tr><td class="cen"><strong>Su sesión ha finalizado, por favor vuelva a ingresar al sistema</strong></td></tr>';
        echo '</table></div></body></html>';
}
?>
