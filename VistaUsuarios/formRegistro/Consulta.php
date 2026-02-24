<html>
<!consulta.php>

    <head>
        <title>Ejemplo php y MySQL consulta a la base de datos</title>
    </head>

    <body>
        <h2 align='center'>Ejemplo consulta base de datos PHP y MySQL</h2>
        <?php //Inico php
        include("conect.php"); //Incluye el archivo conect.php
        $link = conectarse(); //Llama a la funcion conectarse
        $result = mysqli_query($link, "select * from ca_usuario"); //Realiza la consulta a la base de datos
        ?>
        <TABLE BORDER=1 CELLSPACING="1" CELLPADDING="1" align='center'>
            <TR>
                <TD>ID</TD>
                <TD>Nombre</TD>
                <TD>Apellidos</TD>
                <TD>DNI</TD>
            </TR>
            <?php
            while ($row = mysqli_fetch_array($result)) {
                printf(
                    "<TR><TD>%d</TD><TD>%s</TD>
                    <TD>%s</TD><TD>%d</TD></TR>",
                    $row["id"],
                    $row["nombre"],
                    $row["apellidos"],
                    $row["dni"]
                );
            }
            mysqli_free_result($result);
            mysqli_close($link);
            ?>
        </table>

</html>