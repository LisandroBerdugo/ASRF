<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

require_once '../BLL/ProductoBLL.php';

$productoBLL = new ProductoBLL();
$productos = $productoBLL->obtenerProductos();

// Obtener datos dinámicos
$microprocesadores = $productoBLL->obtenerMicroprocesadores();
$marcas = $productoBLL->obtenerMarcas();
$colores = $productoBLL->obtenerColores();
$rams = $productoBLL->obtenerRAM();
$tamanos_pantalla = $productoBLL->obtenerTamanosPantalla();
$idiomas_teclado = $productoBLL->obtenerIdiomasTeclado();

// Configuración de paginación
$productos_por_pagina = 10;
$total_productos = count($productos);
$total_paginas = ceil($total_productos / $productos_por_pagina);
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($pagina_actual - 1) * $productos_por_pagina;
$productos_pagina = array_slice($productos, $inicio, $productos_por_pagina);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Productos</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="productos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script>
        function mostrarPopup() {
            document.getElementById('popup').style.display = 'flex';
            document.getElementById('overlay').style.display = 'block';
        }

        function cerrarPopup() {
            document.getElementById('popup').style.display = 'none';
            document.getElementById('overlay').style.display = 'none';
        }

        document.addEventListener('DOMContentLoaded', function () {
            const formCrear = document.querySelector('#form-crear-producto');

            formCrear.addEventListener('submit', function (event) {
                event.preventDefault();
                const formData = new FormData(this);

                fetch('crear_producto.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    if (data === 'success') {
                        cerrarPopup();
                        alert("Producto agregado exitosamente.");
                        location.reload();
                    } else {
                        alert("Error al agregar el producto: " + data);
                    }
                })
                .catch(error => {
                    console.error("Error al procesar la solicitud:", error);
                    alert("Ocurrió un error al agregar el producto.");
                });
            });
        });
    </script>
</head>
<body>
    <div id="overlay"></div>

    <div class="toolbar">
        <button class="btn add-new" onclick="mostrarPopup()"><i class="fas fa-plus"></i> Agregar Nuevo</button>
    </div>

    <section class="table-section">
        <table class="user-table">
            <thead>
                <tr>
                    <th>Modelo</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Imagen</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos_pagina as $producto): ?>
                    <tr data-id="<?php echo $producto->getId(); ?>">
                        <td><?php echo htmlspecialchars($producto->getNombre()); ?></td>
                        <td><?php echo htmlspecialchars($producto->getPrecio()); ?></td>
                        <td><?php echo htmlspecialchars($producto->getStock()); ?></td>
                        <td>
                            <?php if ($producto->getImagen()): ?>
                                <img src="<?php echo htmlspecialchars($producto->getImagen()); ?>" alt="Imagen del producto" style="width: 100px; height: auto;">
                            <?php else: ?>
                                <span>No hay imagen</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <button class="btn-action delete" onclick="eliminarProducto(<?php echo $producto->getId(); ?>)"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <div class="pagination">
        <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
            <a href="?pagina=<?php echo $i; ?>" class="page-btn <?php if ($i === $pagina_actual) echo 'active'; ?>"><?php echo $i; ?></a>
        <?php endfor; ?>
    </div>

    <div id="popup">
        <div class="popup-content">
            <span class="close-btn" onclick="cerrarPopup()">&times;</span>
            <h2>Agregar Producto</h2>
            <form id="form-crear-producto" enctype="multipart/form-data">
                <label for="nombre">Modelo:</label>
                <input type="text" id="nombre" name="nombre" required>

                <label for="precio">Precio:</label>
                <input type="number" id="precio" name="precio" step="0.01" required>

                <label for="stock">Stock:</label>
                <input type="number" id="stock" name="stock" required>

                <label for="imagen">Imagen:</label>
                <input type="file" id="imagen" name="imagen" accept="image/*" required>

                <label for="id_marca">Marca:</label>
                <select id="id_marca" name="id_marca" required>
                    <option value="">Seleccione una marca</option>
                    <?php foreach ($marcas as $marca): ?>
                        <option value="<?php echo $marca['id']; ?>"><?php echo htmlspecialchars($marca['marca']); ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="id_color">Color:</label>
                <select id="id_color" name="id_color" required>
                    <option value="">Seleccione un color</option>
                    <?php foreach ($colores as $color): ?>
                        <option value="<?php echo $color['id']; ?>"><?php echo htmlspecialchars($color['color']); ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="id_microprocesador">Microprocesador:</label>
                <select id="id_microprocesador" name="id_microprocesador" required>
                    <option value="">Seleccione un microprocesador</option>
                    <?php foreach ($microprocesadores as $microprocesador): ?>
                        <option value="<?php echo $microprocesador['id']; ?>"><?php echo htmlspecialchars($microprocesador['microprocesador']); ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="id_ram">RAM:</label>
                <select id="id_ram" name="id_ram" required>
                    <option value="">Seleccione RAM</option>
                    <?php foreach ($rams as $ram): ?>
                        <option value="<?php echo $ram['id']; ?>"><?php echo htmlspecialchars($ram['ram']); ?> GB</option>
                    <?php endforeach; ?>
                </select>

                <label for="id_tamano_pantalla">Tamaño de Pantalla:</label>
                <select id="id_tamano_pantalla" name="id_tamano_pantalla" required>
                    <option value="">Seleccione Tamaño</option>
                    <?php foreach ($tamanos_pantalla as $tamano): ?>
                        <option value="<?php echo $tamano['id']; ?>"><?php echo htmlspecialchars($tamano['tamano']); ?> pulgadas</option>
                    <?php endforeach; ?>
                </select>

                <label for="id_idioma_teclado">Idioma del Teclado:</label>
                <select id="id_idioma_teclado" name="id_idioma_teclado" required>
                    <option value="">Seleccione un idioma</option>
                    <?php foreach ($idiomas_teclado as $idioma): ?>
                        <option value="<?php echo $idioma['id']; ?>"><?php echo htmlspecialchars($idioma['idioma']); ?></option>
                    <?php endforeach; ?>
                </select>

                <button type="submit">Guardar</button>
            </form>
        </div>
    </div>
</body>
</html>
