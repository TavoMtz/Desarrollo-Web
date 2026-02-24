        <?php
            function Conectarse(){
                $servidor = "92.249.45.29";
                $usuario = "proydweb_p2025";
                $password = "Dw3bp202%";
                $bd = "proydweb_p2025";
                $link = mysqli_connect($servidor, $usuario, $password, $bd);
                if (!$link) {
                    die("Error de conexión: " . mysqli_connect_error());
                }
                return $link;
            }
            $link = Conectarse();
        ?>