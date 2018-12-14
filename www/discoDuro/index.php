<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Log in</title>
        <link rel="stylesheet" href="css/styles.css" />
    </head>
    <body>
        <h3>Log in</h3>
        <?php
        if (isset($_GET["err"])) {
            echo "<p class='err'>" . $_GET["err"] . "</p>";
        }
        ?>
        <form action="validar.php" method="post">
            <table>
                <tr>
                    <td><label for="usuario">Usuario</label></td>
                    <td><input type="text" name="usuario" id="usuario" placeholder="Introduzca el usuario" /></td>
                </tr>
                <tr>
                    <td><label for="contra">Contraseña</label></td>
                    <td><input type="password" name="contra" id="contra" placeholder="************" /></td>
                </tr>
                <tr>
                    <td colspan="2"><input type="submit" value="Entrar" class="btn" /></td>
                </tr>
            </table>
        </form>
    </body>
</html>