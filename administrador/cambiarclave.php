<?php
require_once("../funciones.php");	
require_once("../conexionBD.php");
$link=conectarse(); 
//****** Verificamos si existe la cookie *****/
if(isset($_COOKIE['VotaDatAdmin'])) {
if (!isset($_POST['envia_cambioclave'])) {	
	include_once("cambiarclave.html");	
}
else {	

//*****************************************************
// VALIDAMOS ALGUNOS VALORES EN LA BD ANTES DE GUARDAR
//*****************************************************

//Validar los campos requeridos
valida(array("requerido"=>"clave_actual,clave1_nueva,clave2_nueva"));

if (strlen($_POST['clave1_nueva']) < 4) {
        include_once("encabezado.html");
	print "<strong>La contraseña debe ser como mínimo de 4 caracteres<br />";
        print"<br /><a href='javascript:history.go(-1)'>Volver al formulario</a></strong></div></body></html>";
        exit;
}

if ($_POST['clave1_nueva']!=$_POST['clave2_nueva']) {
        include_once("encabezado.html");
        print "<strong>La confirmación de la contraseña está mal escrita<br />";
        print"<br /><a href='javascript:history.go(-1)'>Volver al formulario</a></strong></div></body></html>";
        exit;
}
$fclave=md5($_POST['clave1_nueva']);
$fclave_actual=md5($_POST['clave_actual']);

//********************************
// GUARDAMOS LOS DATOS EN LA BD
//********************************

$resp=mysql_query(sprintf("select id from administradores where md5(id)=%s and password=%s",comillas($_POST['identificador']),comillas($fclave_actual)),$link);
if ($row = mysql_fetch_array($resp)) {
	$id_adm=$row['id'];
}
else {
        include_once("encabezado.html");
        print "<strong>La contraseña actual no corresponde<br />";
       	print"<br /><a href='javascript:history.go(-1)'>Volver al formulario</a></strong></div></body></html>";
       	exit;
}

//******Guardamos los datos de acceso ******
$cons_sql  = sprintf("UPDATE administradores SET password=%s WHERE id=%d", comillas($fclave), $id_adm);
mysql_query($cons_sql,$link);

//******Guardamos los datos de control ******
$ffecha=date("Y-m-d");
$fhora=date("G:i:s");
$fip = $_SERVER['REMOTE_ADDR']; 
$faccion="Admin_CambioClave";
$cons_sql2  = sprintf("INSERT INTO control(c_fecha,c_hora,c_ip,c_accion,c_idest) VALUES(%s,%s,%s,%s,%d)", comillas($ffecha), comillas($fhora), comillas($fip), comillas($faccion),$id_adm);
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
