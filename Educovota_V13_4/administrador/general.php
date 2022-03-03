<?php
require_once("../funciones.php");	
require_once("../conexionBD.php");
$link=conectarse(); 
//***Leer variables del sistema******
$estado=mysql_query("select * from general",$link);
$leer= mysql_fetch_array($estado);
//****** Verificamos si existe la cookie *****/
if(isset($_COOKIE['VotaDatAdmin'])) {
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
	echo '<html>';
	echo '<head>';
	echo '<title>'.$leer['institucion'].' - Consulta por grados</title>';
	echo '<link href="../estilo4.css" rel="stylesheet" type="text/css" />';
	echo '</head>';
	echo '<body>';
	echo '<h1>'.$leer['institucion'].'</h1>';
	echo '<h2>CONSULTA POR GRADOS</h2>';
	echo '<div align="center">';
	echo '<table>';
	echo '<thead><tr><th>GRADO</th><th>REGISTRADOS</th></tr></thead>';
	$ContReg=0;
	$resp=mysql_query("select * from grados",$link);
	while($row = mysql_fetch_array($resp)) {
		$resp2=mysql_query(sprintf("select count(estudiantes.id) from estudiantes where grado=%d",$row['id']),$link);
		$row2 = mysql_fetch_array($resp2);
		echo '<tr>';
		echo '<td><a href="consulta.php?id='.$row['id'].'" title="Clic para consultar '.$row['grado'].'">'.$row['grado'].'</a></td>';
		echo '<td class="cen">'.$row2[0].'</td></tr>';
		$ContReg=$ContReg+$row2[0];
	}
		echo '<tr>';
		echo '<td><strong>Total registrados...</strong></td>';
		echo '<td class="cen"><strong>'.$ContReg.'</strong></td></tr>';
		echo '</table>';
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
