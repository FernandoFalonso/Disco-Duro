<?php
        
include "../../seguridad/disco/bd.php";
include "../../seguridad/disco/functions.php";

sesion();
if (!isset($_SESSION["valido"]) || !$_SESSION["valido"]) {
    header ("Location: index.php?err=" . urlencode("Identifíquese primero"));
    exit;
}
$usuario = $_SESSION["usuario"];

$id = "";
if (isset($_POST["id"])) {
    $id = strip_tags(trim($_POST["id"]));
} else {
    header ("Location: home.php?err=" . urlencode("Error desconocido"));
    exit;
}

$c = mysqli_connect(IP, USER, PASS, BD);
if (!$c) {
    echo "Código de error: " . mysqli_errno($c);
    echo "<br />Descripción del error: " . mysqli_error($c);
    exit;
}
mysqli_set_charset($c, "utf-8");

if (unlink(RAIZ.$id)) {
    $sql = "delete from ficheros where id = ?";

    $consulta = mysqli_prepare($c, $sql);
    if (!$consulta) {
        echo "Código de error: " . mysqli_errno($c);
        echo "<br />Descripción del error: " . mysqli_error($c);
        exit;
    }

    mysqli_stmt_bind_param($consulta, "s", $id1);
    $id1 = $id;

    mysqli_stmt_execute($consulta);
    
    header ("Location: home.php");
    exit;
} else {
    header ("Location: home.php?err=" . urlencode("Se ha producido un error al intentar borrar el fichero"));
    exit;
}

?>