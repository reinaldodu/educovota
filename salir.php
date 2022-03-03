<?php
//**** Eliminar cookie de sesión *****
setcookie("DataVota", "", time()-3600);

//**** Redireccionar página web *****
header ("Location: index.php"); 
exit();
?>
