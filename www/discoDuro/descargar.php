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

$sql = "select id, nombre, tamanyo, tipo from ficheros where id = ?";

$consulta = mysqli_prepare($c, $sql);
if (!$consulta) {
    echo "Código de error: " . mysqli_errno($c);
    echo "<br />Descripción del error: " . mysqli_error($c);
    exit;
}

mysqli_stmt_bind_param($consulta, "s", $id1);
$id1 = $id;

mysqli_stmt_execute($consulta);
mysqli_stmt_bind_result($consulta, $idbd, $nombre, $tamano, $tipo);
mysqli_stmt_store_result($consulta);
$n = mysqli_stmt_affected_rows($consulta);

if ($n != 1) {
    header ("Location: home.php?err=" . urlencode("Error desconocido"));
    exit;
}

mysqli_stmt_fetch($consulta);

header ("Content-disposition: attachment; filename = $nombre");
header ("Content-type: $tipo");
readfile (RAIZ . $id);

?>