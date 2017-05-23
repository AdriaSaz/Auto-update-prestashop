<?php

// este php necesita en entrada un fichero update.csv con 4 campos
// Referencia producto; Precio Mayorista (costo); Stock; Precio de venta sin iva (PVP)
// los numero en formado ingles (ej 2.55)
// el programa funciona así:
// lee el fichero y accede a la base de datos con la REFERENCIA del producto y recupera la ID del producto
// actualiza stock, precio mayorista, precio de venta sin IVA.
// al finalizar manda un correo con los articulos que no se  han podido actualizar

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

$dir = opendir('.'); //$dir = opendir('./updater');
echo "Conectamos a la bb.dd." . '<br>'; flush();
mysql_connect($url, $user, $pass) or die(mysql_error());
mysql_select_db("intromovil_web") or die(mysql_error());

// Como la primera fila son los nombres de las columnas:
$fila = 0;
// Tenemos que actualizar en dos tablas:
$update_table = "ps_product inner join ps_stock_available on (ps_product.id_product =
ps_stock_available.id_product)";


echo "Abrimos el fichero." . '<br>'; flush();
$handle = fopen($csv_file, "r");
$falta = fopen("falta.txt", "w");
echo "Recorremos el CSV..." . '<br>'; flush();
while (($data = fgetcsv($handle, 0, ";")) !== FALSE) {
$num = count($data);
echo "<p>" . '<br>'; flush();
echo "( $fila )" . '<br>'; flush();
$fila++;
for ($c=0; $c < $num; $c++) {
if ($c = 1) { //Referencia producto
$reference = $data[($c - 1)];

$row = mysql_query("SELECT * FROM ps_product WHERE reference='$reference'") or die (mysql_error()); //
$existe = mysql_num_rows($row); //


if ($existe == 0) {fwrite ($falta, "La referencia $reference no existe en bb.dd." . PHP_EOL);} //

echo $reference . " - Referencia Producto" . '<br>'; flush();
$buscaid = mysql_query("SELECT id_product FROM ps_product WHERE reference='$reference'") or

die (mysql_error()); //
$id_product = mysql_result($buscaid, 0);
echo $id_product . " - Referencia Producto" . '<br>'; flush();
}

if ($c = 2) { //precio de compra
$compra = $data[($c - 1)];
mysql_query("UPDATE $update_table SET wholesale_price='$compra' WHERE reference='$reference'")
or die(mysql_error());
echo $compra . " - Coste actualizado tabla ps:_product" . '<br>'; flush();

mysql_query("UPDATE ps_product_shop SET wholesale_price='$compra' WHERE

id_product='$id_product'")
or die(mysql_error());
echo $compra . " - Coste actualizado tabla ps:_product_shop" . '<br>'; flush();
}

if ($c = 3) { //Precio PVP Sin IVA
$price = $data[($c - 1)];
mysql_query("UPDATE $update_table SET price='$price' WHERE reference='$reference'")
or die(mysql_error()); 
echo $price . " - PVP tabla ps_product actualizado" . '<br>'; flush();
mysql_query("UPDATE ps_product_shop SET price='$price' WHERE id_product='$id_product'")
or die(mysql_error()); 
echo $price . " - PVP tabla ps_product.shop actualizado" . '<br>'; flush();
}

if ($c = 4) { //Stock
$quantity = $data[($c - 1)];
mysql_query("UPDATE $update_table SET ps_stock_available.quantity='$quantity' WHERE

reference='$reference'")
or die(mysql_error());
echo $quantity . " - stock actualizado" . '<br>'; flush();
}


echo "_____________________________________________________<p>";
}
}
fclose($handle);
fclose($falta);
//mandamos el archivo por mail
# Leer el contenido del archivo 
$archivo = file_get_contents("falta.txt"); 

# Asunto 
$asunto = "Articulos faltantes"; 
# Encabezado del E-mail 
$header = "From: ".$nombre_from." <".$email_from.">\r\n"; 
# Envio del email 
$ok = mail($email_to,$asunto,$archivo,$header); 
# Si el email se envío, se imprime... 
echo ($ok) ? "Enviado..." : "Falló el envío";

echo " <p>" . '<br>'; flush();
echo " - - - ACTUALIZACION COMPLETADA - - - <p>" . '<br>'; flush();
//Borro los archivos viejos para no tener problema al renombrar o descargar
//unlink('update.csv');

echo "Archivos temporales eliminados" . '<br>'; flush();
echo "Todo hecho" . '<br>'; flush();
?>


