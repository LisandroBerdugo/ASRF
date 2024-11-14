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

// Obtener opciones dinámicas para filtros
$colores = $bll->obtenerColores(); // Método para obtener colores
$marcas = $bll->obtenerMarcas(); // Método para obtener marcas
$tamanos = $bll->obtenerTamanosPantalla(); // Método para obtener tamaños de pantalla

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tienda en Línea</title>
    <link rel="stylesheet" href="vista_clientes.css">
</head>
<body>
    <h1>Productos Disponibles</h1>
    <div class="container">
        <form method="GET" action="vista_clientes.php" class="filters">
            <input type="text" name="buscar" placeholder="Buscar por modelo o código" value="<?php echo htmlspecialchars($filtros['buscar']); ?>">
            
            <select name="color">
                <option value="">Todos los Colores</option>
                <?php foreach ($colores as $color): ?>
                    <option value="<?php echo $color['id']; ?>" <?php if ($filtros['color'] == $color['id']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($color['color']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <select name="marca">
                <option value="">Todas las Marcas</option>
                <?php foreach ($marcas as $marca): ?>
                    <option value="<?php echo $marca['id']; ?>" <?php if ($filtros['marca'] == $marca['id']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($marca['marca']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <select name="tamano">
                <option value="">Todos los Tamaños</option>
                <?php foreach ($tamanos as $tamano): ?>
                    <option value="<?php echo $tamano['id']; ?>" <?php if ($filtros['tamano'] == $tamano['id']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($tamano['tamano']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <button type="submit">Filtrar</button>
        </form>

        <div class="products-grid">
            <?php foreach ($productos as $producto): ?>
                <div class="product-card">
                    <div class="product-image">
                        <img src="<?php echo $producto->getImagen(); ?>" alt="<?php echo htmlspecialchars($producto->getNombre()); ?>">
                    </div>
                    <div class="product-details">
                        <h3><?php echo htmlspecialchars($producto->getNombre()); ?></h3>
                        <p><strong>Código:</strong> <?php echo htmlspecialchars($producto->getCodigoUnico()); ?></p>
                        <p><strong>Marca:</strong> <?php echo htmlspecialchars($producto->getMarca()); ?></p>
                        <p><strong>Color:</strong> <?php echo htmlspecialchars($producto->getColor()); ?></p>
                        <p><strong>Procesador:</strong> <?php echo htmlspecialchars($producto->getMicroprocesador()); ?></p>
                        <p><strong>RAM:</strong> <?php echo htmlspecialchars($producto->getRam()); ?> GB</p>
                        <p><strong>Tamaño Pantalla:</strong> <?php echo htmlspecialchars($producto->getTamanoPantalla()); ?> pulgadas</p>
                        <p><strong>Idioma Teclado:</strong> <?php echo htmlspecialchars($producto->getIdiomaTeclado()); ?></p>
                        <p><strong>Precio:</strong> $<?php echo number_format($producto->getPrecio(), 2); ?></p>
                        <p><strong>Stock:</strong> <?php echo $producto->getStock(); ?> disponibles</p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>

</body>
</html>
