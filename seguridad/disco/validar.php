<?php

include "bd.php";
include "functions.php";

$c = mysqli_connect(IP, USER, PASS, BD);
if (!$c) {
    echo "Código de error: " . mysqli_errno($c);
    echo "<br />Descripción del error: " . mysqli_error($c);
    exit;
}
mysqli_set_charset($c, "utf-8");

$usuario = "";
if (isset($_POST["usuario"]) && !empty($_POST["usuario"])) {
    $usuario = strip_tags(trim($_POST["usuario"]));
} else {
    header ("Location: index.php?err=" . urlencode("Introduzca un usuario"));
    exit;
}

$contra = "";
if (isset($_POST["contra"]) && !empty($_POST["contra"])) {
    $contra = strip_tags(trim($_POST["contra"]));
} else {
    header ("Location: index.php?err=" . urlencode("Introduzca una contraseña"));
    exit;
}

$sql = "select * from usuarios where usuario = ?";

$consulta = mysqli_prepare($c, $sql);
if (!$consulta) {
    echo "Código de error: " . mysqli_errno($c);
    echo "<br />Descripción del error: " . mysqli_error($c);
    exit;
}

mysqli_stmt_bind_param($consulta, "s", $usuario1);
$usuario1 = $usuario;

mysqli_stmt_execute($consulta);
mysqli_stmt_bind_result($consulta, $usuariobd, $contrabd, $cuotabd);
mysqli_stmt_store_result($consulta);
$n = mysqli_stmt_affected_rows($consulta);

mysqli_stmt_fetch($consulta);

if ($n != 0) {
    if (!password_verify($contra, $contrabd)) {
        header ("Location: index.php?err=" . urlencode("La contraseña es incorrecta"));
        exit;
    }
} else {
    header ("Location: index.php?err=" . urlencode("No existe la cuenta a la que está intentando acceder"));
    exit;
}

// Datos de sesion
sesion();
$_SESSION["valido"] = true;
$_SESSION["usuario"] = $usuario;
$_SESSION["cuota"] = $cuotabd;

header ("Location: home.php");

?>