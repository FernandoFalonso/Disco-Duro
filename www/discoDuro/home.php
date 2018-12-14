<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Archivos</title>
        <link rel="stylesheet" href="css/styles.css" />
        <script src="confirmacion.js"></script>
    </head>
    <body>
        <main>
        <?php
        
        // Errores
        if (isset($_GET["err"])) {
            echo "<p class='err'>" . $_GET["err"] . "</p>";
        }
        
        include "../../seguridad/disco/bd.php";
        include "../../seguridad/disco/functions.php";
        
        sesion();
        if (!isset($_SESSION["valido"]) || !$_SESSION["valido"]) {
            header ("Location: index.php?err=" . urlencode("Identifíquese primero"));
            exit;
        }
        $usuario = $_SESSION["usuario"];
        $cuota = $_SESSION["cuota"];

        $c = mysqli_connect(IP, USER, PASS, BD);
        if (!$c) {
            echo "Código de error: " . mysqli_errno($c);
            echo "<br />Descripción del error: " . mysqli_error($c);
            exit;
        }
        mysqli_set_charset($c, "utf-8");
        
        // Consulta
        $sql = "select * from ficheros where usuario = ?";

        $consulta = mysqli_prepare($c, $sql);
        if (!$consulta) {
            echo "Código de error: " . mysqli_errno($c);
            echo "<br />Descripción del error: " . mysqli_error($c);
            exit;
        }

        mysqli_stmt_bind_param($consulta, "s", $usuario1);
        $usuario1 = $usuario;

        mysqli_stmt_execute($consulta);
        mysqli_stmt_bind_result($consulta, $idbd, $nombrebd, $tamanobd, $tipobd, $usuariobd);
        mysqli_stmt_store_result($consulta);
        $n = mysqli_stmt_affected_rows($consulta);
        
        if ($n == 0) {
            echo "<p class='err msg'>No hay ningún archivo</p>";
        } else {
            
        ?>
        
        <table>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Tamaño</th>
            </tr>
        
        <?php
            
        }
        
        $cuotaGastada = 0;
        while (mysqli_stmt_fetch($consulta)) {
            $cuotaGastada += $tamanobd;
            
        ?>
        
        <tr>
            <td><?=$idbd?></td>
            <td><?=$nombrebd?></td>
            <td><?=$tamanobd?> b</td>
            <td>
                <form action="borrar.php" method="post">
                    <input type="hidden" name="id" value="<?=$idbd?>">
                    <input type="submit" class="btn borr" value="Borrar" />
                </form>
            </td>
            <td>
                <form action="descargar.php" method="post">
                    <input type="hidden" name="id" value="<?=$idbd?>">
                    <input type="submit" value="Descargar" class="btn" />
                </form>
            </td>
        </tr>
        
        <?php
        
        }
        $cuota -= $cuotaGastada;
        
        ?>
        </table>
        </main>
        <div id="header">
            <form enctype="multipart/form-data" action="subir.php" method="post">
                <b>Subir ficheros</b><br />
                <input type="hidden" name="MAX_FILE_SIZE" value="<?=$cuota?>" />
                <input type="file" name="ficheros[]" multiple="multiple" class="btn" />
                <br />
                <input type="submit" value="Subir" class="btn" />
            </form>
            <div class="derecha">
                <b>Sesión</b>
                <br /><br />
                Usuario: <b><?=$usuario?></b>
                <br />
                Cuota: <b><?=$cuota?></b> b
                <br />
                <button onclick="location.href = 'cerrar.php'" class="btn">Cerrar sesión</button>
            </div>
        </div>
    </body>
</html>