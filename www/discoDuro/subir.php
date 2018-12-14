<?php
        
include "../../seguridad/disco/bd.php";
include "../../seguridad/disco/functions.php";

sesion();
if (!isset($_SESSION["valido"]) || !$_SESSION["valido"]) {
    header ("Location: index.php?err=" . urlencode("Identifíquese primero"));
    exit;
}
$usuario = $_SESSION["usuario"];

$c = mysqli_connect(IP, USER, PASS, BD);
if (!$c) {
    echo "Código de error: " . mysqli_errno($c);
    echo "<br />Descripción del error: " . mysqli_error($c);
    exit;
}
mysqli_set_charset($c, "utf-8");

// Comprobacion de ficheros
if (!isset($_FILES["ficheros"])) {
    header ("Location: home.php?err=" . urlencode("Error desconocido"));
    exit;
}

if (!is_array($_FILES["ficheros"]["name"])) {
    header ("Location: home.php?err=" . urlencode("Error desconocido"));
    exit;
}

$nFicheros = count ($_FILES["ficheros"]["name"]);

// Consulta
$sql = "insert into ficheros values (?, ?, ?, ?, ?)";

$consulta = mysqli_prepare($c, $sql);
if (!$consulta) {
    echo "Código de error: " . mysqli_errno($c);
    echo "<br />Descripción del error: " . mysqli_error($c);
    exit;
}

mysqli_stmt_bind_param($consulta, "ssiss", $id1, $nombre1, $tamano1, $tipo1, $usuario1);

$msg = "";
for ($i = 0; $i < $nFicheros; $i++) {
    
    switch ($_FILES["ficheros"]["error"][$i]) {
        case UPLOAD_ERR_OK:
            $id1 = uniqid("", true);
            $ruta = RAIZ . $id1;
            if (move_uploaded_file($_FILES["ficheros"]["tmp_name"][$i], $ruta)) {
                $nombre1 = $_FILES["ficheros"]["name"][$i];
                $tamano1 = $_FILES["ficheros"]["size"][$i];
                $tipo1 = $_FILES["ficheros"]["type"][$i];
                $usuario1 = $usuario;
                mysqli_stmt_execute($consulta);
                $msg = "Fichero/s subido con éxito";
            } else{
                $msg = "Error desconocido";
            }
            break;
            
        case UPLOAD_ERR_NO_FILE:
            $msg = "No se ha seleccionado ningún fichero";
            break;
            
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            $msg = "Límite de capacidad excedido";
            break;
            
        default:
            $msg = "Error desconocido";
            break;
    }
}

header ("Location: home.php?err=" . urlencode($msg));
exit;

?>