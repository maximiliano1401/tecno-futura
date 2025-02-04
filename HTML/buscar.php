<?php
include "../PHP/conexion.php";
session_start();

// Verificar sesión activa
if (!isset($_SESSION['ID_Cliente'])) {
    header("Location: index.html");
    exit;
}

if (isset($_GET['busqueda'])) {
    $busqueda = $_GET['busqueda'];

    // Consultar en la base de datos, uso LEFT JOIN para unificar ambas tablas con datos parciales a RUTA1
    $sql = "
        SELECT p.*, pf.Ruta1 AS Foto
        FROM productos p
        LEFT JOIN productos_fotos pf ON p.ID_Producto = pf.ID_Producto
        WHERE p.Nombre LIKE '%$busqueda%'
    ";
    $resultado = mysqli_query($conexion, $sql);

    if (!$resultado) {
        die("Error en la consulta: " . mysqli_error($conexion));
    }
} else {
    header("Location: menu.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/buscar.css">
    <title>Resultados de búsqueda</title>
</head>

<body>
    <img class="boton" src="../IMG/Button.png" onclick="window.location.href='menu.php'">
    <div class="container resultados">
        <h1 class="titulo">Resultados de búsqueda</h1>
        <?php if (mysqli_num_rows($resultado) > 0): ?>
            <ul class="lista-productos">
                <?php while ($producto = mysqli_fetch_assoc($resultado)): ?>
                    <li class="producto-item">
                        <a href="producto.php?id_producto=<?php echo $producto['ID_Producto']; ?>" class="producto-enlace">
                            <img src="../IMG/<?php echo $producto['Foto']; ?>" alt="Imagen de <?php echo $producto['Nombre']; ?>" class="producto-img">
                            <div class="producto-info">
                                <strong class="producto-nombre"><?php echo $producto['Nombre']; ?></strong>
                                <p class="producto-descripcion"><?php echo $producto['Descripcion']; ?></p>
                                <p class="producto-precio">Precio: $<?php echo $producto['Precio']; ?></p>
                            </div>
                        </a>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p class="mensaje-no-encontrado">No se encontraron resultados para "<?php echo $busqueda; ?>"</p>
        <?php endif; ?>
    </div>
</body>

</html>
