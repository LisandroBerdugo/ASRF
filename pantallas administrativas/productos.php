<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id']) || ($_SESSION['rol'] !== 'admin' && $_SESSION['rol'] !== 'vendedor')) {
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
$productos_por_pagina = 20;
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
        function mostrarEditarPopup(id) {
            fetch(`obtener_producto.php?id=${id}`)
                .then(response => response.json())
                .then(producto => {
                    document.getElementById('editar-id').value = producto.id;
                    document.getElementById('editar-nombre').value = producto.nombre;
                    document.getElementById('editar-precio').value = parseFloat(producto.precio).toFixed(2); // Formato de 2 decimales
                    document.getElementById('editar-stock').value = parseInt(producto.stock, 10); // Entero para el stock
                    document.getElementById('editar-id_marca').value = producto.id_marca;
                    document.getElementById('editar-id_color').value = producto.id_color;
                    document.getElementById('editar-id_microprocesador').value = producto.id_microprocesador;
                    document.getElementById('editar-id_ram').value = producto.id_ram;
                    document.getElementById('editar-id_tamano_pantalla').value = producto.id_tamano_pantalla;
                    document.getElementById('editar-id_idioma_teclado').value = producto.id_idioma_teclado;

                    document.getElementById('editar-popup').style.display = 'flex';
                    document.getElementById('overlay').style.display = 'block';
                })
                .catch(error => {
                    console.error("Error al obtener los datos del producto:", error);
                    alert("No se pudieron cargar los datos del producto.");
                });
        }

        function cerrarEditarPopup() {
            document.getElementById('editar-popup').style.display = 'none';
            document.getElementById('overlay').style.display = 'none'; // Ocultar el overlay
        }

        

        function eliminarProducto(id) {
            if (!confirm("¿Estás seguro de que deseas eliminar este producto?")) {
                return;
            }

            fetch('eliminar_producto.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id=${id}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert("Producto eliminado correctamente.");
                        location.reload(); // Recarga la página para actualizar la lista
                    } else {
                        alert("Error al eliminar el producto."+data.status);
                    }
                })
                .catch(error => {
                    console.error("Error al procesar la solicitud:", error);
                    alert("Ocurrió un error al intentar eliminar el producto.");
                });
        }

        function mostrarPopup() {
            document.getElementById('popup').style.display = 'flex';
            document.getElementById('overlay').style.display = 'block';
        }

        function cerrarPopup() {
            document.getElementById('popup').style.display = 'none';
            document.getElementById('overlay').style.display = 'none';
        }

        
        document.addEventListener('DOMContentLoaded', function() {
            const formEditar = document.querySelector('#form-editar-producto');
            formEditar.addEventListener('submit', function(event) {
                event.preventDefault(); // Evita el envío tradicional del formulario

                const formData = new FormData(this);

                fetch('editar_producto.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json()) // Procesar como JSON
                    .then(data => {
                        if (data.status === 'success') {
                            alert(data.message); // Mostrar mensaje de éxito
                            cerrarEditarPopup(); // Cerrar el popup de edición
                            location.reload(); // Recargar la tabla para reflejar los cambios
                        } else {
                            alert("Error: " + data.message); // Mostrar mensaje de error
                        }
                    })
                    .catch(error => {
                        console.error("Error al procesar la solicitud:", error);
                        alert("Ocurrió un error al intentar actualizar el producto.");
                    });
            });
        });

        function cerrarEditarPopup() {
            document.getElementById('editar-popup').style.display = 'none';
            document.getElementById('overlay').style.display = 'none'; // Oculta el overlay
        }


        document.addEventListener('DOMContentLoaded', function() {
            const formCrear = document.querySelector('#form-crear-producto');

            formCrear.addEventListener('submit', function(event) {
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
                    <th>Código</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos_pagina as $producto): ?>
                <tr data-id="<?php echo $producto->getId(); ?>">
                    <td><?php echo htmlspecialchars($producto->getNombre()); ?></td>
                    <td><?php echo number_format($producto->getPrecio(), 2); ?></td>
                    <td><?php echo intval($producto->getStock()); ?></td>
                    <td><?php echo htmlspecialchars($producto->getCodigoUnico()); ?></td>
                    <td>
                        <button class="btn-action edit" onclick="mostrarEditarPopup(<?php echo $producto->getId(); ?>)">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-action delete" onclick="eliminarProducto(<?php echo $producto->getId(); ?>)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>

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
            <form id="form-crear-producto" action="crear_producto.php" method="POST" enctype="multipart/form-data">
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

    <div id="editar-popup">
        <div class="popup-content">
            <span class="close-btn" onclick="cerrarEditarPopup()">&times;</span>
            <h2>Editar Producto</h2>
            <form id="form-editar-producto" method="POST" enctype="multipart/form-data">
                <input type="hidden" id="editar-id" name="id">

                <label for="editar-nombre">Modelo:</label>
                <input type="text" id="editar-nombre" name="nombre" required>

                <label for="editar-precio">Precio:</label>
                <input type="number" id="editar-precio" name="precio" step="0.01" required>

                <label for="editar-stock">Stock:</label>
                <input type="number" id="editar-stock" name="stock" required>

                <label for="editar-id_marca">Marca:</label>
                <select id="editar-id_marca" name="id_marca" required>
                    <option value="">Seleccione una marca</option>
                    <?php foreach ($marcas as $marca): ?>
                    <option value="<?php echo $marca['id']; ?>"><?php echo htmlspecialchars($marca['marca']); ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="editar-id_color">Color:</label>
                <select id="editar-id_color" name="id_color" required>
                    <option value="">Seleccione un color</option>
                    <?php foreach ($colores as $color): ?>
                    <option value="<?php echo $color['id']; ?>"><?php echo htmlspecialchars($color['color']); ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="editar-id_microprocesador">Microprocesador:</label>
                <select id="editar-id_microprocesador" name="id_microprocesador" required>
                    <option value="">Seleccione un microprocesador</option>
                    <?php foreach ($microprocesadores as $microprocesador): ?>
                    <option value="<?php echo $microprocesador['id']; ?>"><?php echo htmlspecialchars($microprocesador['microprocesador']); ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="editar-id_ram">RAM:</label>
                <select id="editar-id_ram" name="id_ram" required>
                    <option value="">Seleccione RAM</option>
                    <?php foreach ($rams as $ram): ?>
                    <option value="<?php echo $ram['id']; ?>"><?php echo htmlspecialchars($ram['ram']); ?> GB</option>
                    <?php endforeach; ?>
                </select>

                <label for="editar-id_tamano_pantalla">Tamaño de Pantalla:</label>
                <select id="editar-id_tamano_pantalla" name="id_tamano_pantalla" required>
                    <option value="">Seleccione Tamaño</option>
                    <?php foreach ($tamanos_pantalla as $tamano): ?>
                    <option value="<?php echo $tamano['id']; ?>"><?php echo htmlspecialchars($tamano['tamano']); ?> pulgadas</option>
                    <?php endforeach; ?>
                </select>

                <label for="editar-id_idioma_teclado">Idioma del Teclado:</label>
                <select id="editar-id_idioma_teclado" name="id_idioma_teclado" required>
                    <option value="">Seleccione un idioma</option>
                    <?php foreach ($idiomas_teclado as $idioma): ?>
                    <option value="<?php echo $idioma['id']; ?>"><?php echo htmlspecialchars($idioma['idioma']); ?></option>
                    <?php endforeach; ?>
                </select>

                <!-- Campo para subir nueva imagen -->
                <label for="editar-imagen">Nueva Imagen:</label>
                <input type="file" id="editar-imagen" name="imagen" accept="image/*">

                <button type="submit">Guardar Cambios</button>
            </form>
        </div>
    </div>



</body>
<script>
    /*
    // Manejador del formulario de edición
        document.getElementById('form-editar-producto').addEventListener('submit', function(event) {
            event.preventDefault();

            const formData = new FormData(this);
            alert('laksdjlaksjd');

            fetch('editar_producto.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    if (data === 'success') {
                        alert("Producto actualizado correctamente.");
                        location.reload();
                    } else {
                        alert("Error al actualizar el producto.");
                    }
                })
                .catch(error => {
                    console.error("Error al procesar la solicitud:", error);
                    alert("Ocurrió un error al actualizar el producto.");
                });
        });*/
</script>
</html>
