<?php
require_once '../BLL/VistaBLL.php';

$filtros = [
    'color' => $_GET['color'] ?? '',
    'marca' => $_GET['marca'] ?? '',
    'tamano' => $_GET['tamano'] ?? '',
    'buscar' => $_GET['buscar'] ?? ''
];

$bll = new VistaBLL();
$productos = $bll->obtenerProductos($filtros);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Productos Disponibles</title>
    <link rel="stylesheet" href="vista_clientes.css">
</head>
<body>
    <h1>Productos Disponibles</h1>
    <div class="container">
    <div class="filters">
        <input type="text" name="buscar" placeholder="Buscar por modelo o código">
        <select name="color">
            <option value="">Todos los Colores</option>
            <!-- Opciones de colores -->
        </select>
        <select name="marca">
            <option value="">Todas las Marcas</option>
            <!-- Opciones de marcas -->
        </select>
        <select name="tamano">
            <option value="">Todos los Tamaños</option>
            <!-- Opciones de tamaños -->
        </select>
        <button>Filtrar</button>
    </div>

    <div class="products-grid">
        <?php foreach ($productos as $producto): ?>
            <div class="product-card">
                <div class="product-image">
                    <img src="<?php echo $producto->getImagen(); ?>" alt="<?php echo $producto->getNombre(); ?>">
                </div>
                <div class="product-details">
                    <h3><?php echo htmlspecialchars($producto->getNombre()); ?></h3>
                    <p>Precio: $<?php echo number_format($producto->getPrecio(), 2); ?></p>
                    <p>Stock: <?php echo $producto->getStock(); ?> disponibles</p>
                    <a href="detalles_producto.php?id=<?php echo $producto->getId(); ?>" class="details-button">Ver Detalles</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>
