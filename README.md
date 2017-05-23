# Auto-update-prestashop
// este php necesita en entrada un fichero update.csv (el nombre da igual se configura en el archivo php) con 4 campos
// Referencia producto; Precio Mayorista (costo); Stock; Precio de venta sin iva (PVP)
// los numero en formado ingles (ej 2.55)
// el programa funciona así:
// lee el fichero y accede a la base de datos con la REFERENCIA del producto y recupera la ID del producto
// actualiza stock, precio mayorista, precio de venta sin IVA.
// al finalizar manda un correo con los articulos que no se  han podido actualizar


Parametros a configurar
//CONFIGURACIÓN CORREO
// Nombre de quien envia
$nombre_from = ""; 
// Correo de quien envia
$email_from = ""; 
// Para quien 
$email_to = ""; 

//Configuracion BBDD
$url=""; 		//direccion bbdd
$user="";		//usuario bbdd 
$pass="";		//contraseña usuario
$bbdd="";		//nombre bbdd
$csv_file="";	//Archivo csv 
