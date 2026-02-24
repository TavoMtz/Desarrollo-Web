<?php
include("../VistaAdministrador/php/conex.php");
$link = Conectarse();

// Fetch visible promotions from the database
$query = "SELECT imagen, nombre_promocion, precio FROM ca_promociones WHERE visible = 1";
$result = mysqli_query($link, $query);
$promotions = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $promotions[] = $row;
    }
}
mysqli_close($link);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Promociones de Temporada</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="promo.css">
</head>
<body style="background-color: rgb(81, 49, 2);">
    <div>
        <a href="../index.php"><img src="img/logoKffcito.png" width ="200px" height="150px" ></a>
    </div>
    <div>
        <p style="font-size: 96px; color: chocolate;" align="center">¡Promociones de temporada!</p><br>
    </div>
   
    <div class="container"> 
        <div id="theCarousel" class="carousel slide" data-ride="carousel">
            <!-- Indicators -->
            <ol class="carousel-indicators">
                <?php foreach ($promotions as $index => $promotion): ?>
                    <li data-target="#theCarousel" data-slide-to="<?php echo $index; ?>" class="<?php echo $index === 0 ? 'active' : ''; ?>"></li>
                <?php endforeach; ?>
            </ol>

            <!-- Carousel Items -->
            <div class="carousel-inner" role="listbox">
                <?php foreach ($promotions as $index => $promotion): ?>
                    <div class="item <?php echo $index === 0 ? 'active' : ''; ?>">
                        <img src="<?php echo '../VistaAdministrador/' . $promotion['imagen']; ?>" 
                             class="img-responsive center-block" 
                             style="width: 250px; height: 250; max-height: 400px; object-fit: contain;">
                        <div class="carousel-caption">
                            <h3 style="color: chocolate;"><?php echo htmlspecialchars($promotion['nombre_promocion']); ?></h3>
                            <p style="color: chocolate; font-size: 18px;">Precio: $<?php echo number_format($promotion['precio'], 2); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Controls -->
            <a class="left carousel-control" href="#theCarousel" role="button" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                <span class="sr-only">Anterior</span>
            </a>
            <a class="right carousel-control" href="#theCarousel" role="button" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                <span class="sr-only">Siguiente</span>
            </a>
        </div>
    </div>
</body>
</html>