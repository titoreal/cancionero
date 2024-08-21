<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("location:../../index.php");
}

require '../../clases/Conexion.php';

// Crear una nueva conexión
$conexion = new Conexion();
$conn = $conexion->conectar();

$sqlCanciones = "SELECT 
    c.id, 
    c.tema, 
    c.interprete, 
    d.casa AS disquera,  
    n.pais AS nacionalidad, 
    t.estilo AS tipo, 
    c.letra AS tipo 
FROM 
    cancion AS c
INNER JOIN 
    disquera AS d ON c.id_disquera = d.id
INNER JOIN 
    nacionalidad AS n ON c.id_nacionalidad = n.id
INNER JOIN 
    tipo AS t ON c.id_tipo = t.id";

$canciones = $conn->query($sqlCanciones);
$dir = "caratulas/";
?>

<!DOCTYPE html>
<html lang="es" class="h-100">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Canciones</title>
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/css/all.min.css" rel="stylesheet">


<body class="d-flex flex-column h-100">
    <nav class="navbar navbar-expand-lg navbar-light bg-white static-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="caratulas/realjobslogo.jpg" alt="logorealjobs" height="36">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php">Inicio</a>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a style="color:red" class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo $_SESSION['usuario']; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="../../servidor/login/logout.php">Salir del sistema</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container py-3">

        <h2 class="text-center">Canciones</h2>
        <hr>

        <?php if (isset($_SESSION['msg']) && isset($_SESSION['color'])) { ?>
            <div class="alert alert-<?= $_SESSION['color']; ?> alert-dismissible fade show" role="alert">
                <?= $_SESSION['msg']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

            <?php
            unset($_SESSION['color']);
            unset($_SESSION['msg']);
        } ?>

        <!-- Cajón de búsqueda alineado con boton nueva cancion -->

        <div class="row justify-content-between align-items-center">
            <!-- Botón "Nueva canción" alineado a la izquierda -->
            <div class="col-auto">
                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevoModal">
                    <i class="fa-solid fa-circle-plus"></i> Nueva canción
                </a>
            </div>

            <!-- Mostrar y opciones de mostrado en el centro -->
            <div class="col-auto d-flex align-items-center">
                <label for="num_registros" class="me-2 mb-0">Mostrar: </label>
                <select name="num_registros" id="num_registros" class="form-select me-4">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="20" selected>20</option>
                </select>
            </div>

            <!-- Cajón de búsqueda alineado a la derecha -->
            <div class="col-auto d-flex align-items-center">
                <label for="campo" class="me-2 mb-0">Buscar: </label>
                <input type="text" name="campo" id="campo" class="form-control">
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-sm table-striped table-hover mt-4">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th class="sortable" data-column="c.tema">Tema</th>
                        <th class="sortable" data-column="c.interprete">Intérprete</th>
                        <th class="sortable" data-column="c.id">Código</th>
                        <th class="sortable" data-column="d.casa">Disquera</th>
                        <th class="sortable" data-column="n.pais">Nacionalidad</th>
                        <th class="sortable" data-column="t.estilo">Tipo</th>
                        <th>Carátula</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody id="contenido">
                    <?php

                    while ($row = $canciones->fetch_assoc()) { ?>
                        <tr>

                            <td><?= $row['tema']; ?></td>
                            <td><?= $row['interprete']; ?></td>
                            <td><?= $row['id']; ?></td>
                            <td><?= $row['disquera']; ?></td>
                            <td><?= $row['nacionalidad']; ?></td>
                            <td><?= $row['tipo']; ?></td>
                            <td><img src="<?= $dir . $row['id'] . '.jpg?n=' . time(); ?>" width="75"></td>
                            <td>
                                <a href="#" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#letraModal"
                                    data-bs-id="<?= $row['id']; ?>"><i class="fa-solid fa-music"></i> Ver Letra</a>
                                <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#editaModal" data-bs-id="<?= $row['id']; ?>"><i
                                        class="fa-solid fa-pen-to-square"></i> Editar</a>
                                <a href="#" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#eliminaModal" data-bs-id="<?= $row['id']; ?>"><i
                                        class="fa-regular fa-trash-can"></i> Eliminar</a>
                            </td>
                        </tr>

                    <?php } ?>
                </tbody>

            </table>
        </div>

        <div id="pagination"></div>

        <?php
        $sqlDisquera = "SELECT id, casa FROM disquera";
        $disqueras = $conn->query($sqlDisquera);
        $sqlNacionalidad = "SELECT id, pais FROM nacionalidad";
        $nacionalidades = $conn->query($sqlNacionalidad);
        $sqlTipo = "SELECT id, estilo FROM tipo";
        $tipos = $conn->query($sqlTipo);
        ?>

        <?php include 'nuevoModal.php'; ?>

        <?php
        $disqueras->data_seek(0);
        $nacionalidades->data_seek(0);
        $tipos->data_seek(0);
        ?>

        <?php include 'editaModal.php'; ?>
        <?php include 'eliminaModal.php'; ?>
        <?php include 'letraModal.php'; ?>



        <script>

            let nuevoModal = document.getElementById('nuevoModal')
            let editaModal = document.getElementById('editaModal');
            let eliminaModal = document.getElementById('eliminaModal');
            let letraModal = document.getElementById('letraModal')
            let numRegistros = document.getElementById('num_registros');

            nuevoModal.addEventListener('shown.bs.modal', event => {
                nuevoModal.querySelector('.modal-body #tema').focus()
            })

            nuevoModal.addEventListener('hide.bs.modal', event => {
                nuevoModal.querySelector('.modal-body #tema').value = ""
                nuevoModal.querySelector('.modal-body #interprete').value = ""
                nuevoModal.querySelector('.modal-body #disquera').value = ""
                nuevoModal.querySelector('.modal-body #nacionalidad').value = ""
                nuevoModal.querySelector('.modal-body #tipo').value = ""
                nuevoModal.querySelector('.modal-body #caratula').value = ""
                nuevoModal.querySelector('.modal-body #letra').value = ""
            })

            editaModal.addEventListener('hide.bs.modal', event => {
                editaModal.querySelector('.modal-body #tema').value = ""
                editaModal.querySelector('.modal-body #interprete').value = ""
                editaModal.querySelector('.modal-body #disquera').value = ""
                editaModal.querySelector('.modal-body #nacionalidad').value = ""
                editaModal.querySelector('.modal-body #tema').value = ""
                editaModal.querySelector('.modal-body #img_caratula').value = ""
                editaModal.querySelector('.modal-body #caratula').value = ""
                editaModal.querySelector('.modal-body #letra').value = ""
            })

            document.querySelectorAll('.sortable').forEach(header => {
                header.addEventListener('click', () => {
                    const column = header.dataset.column;
                    const currentDirection = header.dataset.direction || 'asc';
                    const newDirection = currentDirection === 'asc' ? 'desc' : 'asc';

                    header.dataset.direction = newDirection;
                    loadCanciones(1, column, newDirection);
                });
            });


            editaModal.addEventListener('shown.bs.modal', event => {
                let button = event.relatedTarget;
                let id = button.getAttribute('data-bs-id');

                let inputId = editaModal.querySelector('.modal-body #id');
                let inputTema = editaModal.querySelector('.modal-body #tema');
                let inputInterprete = editaModal.querySelector('.modal-body #interprete');
                let inputDisquera = editaModal.querySelector('.modal-body #disquera');
                let inputNacionalidad = editaModal.querySelector('.modal-body #nacionalidad');
                let inputTipo = editaModal.querySelector('.modal-body #tipo');
                let caratula = editaModal.querySelector('.modal-body #img_caratula');
                let inputLetra = editaModal.querySelector('.modal-body #letra');


                let url = "getCancion.php";
                let formData = new FormData();
                formData.append('id', id);

                fetch(url, {
                    method: "POST",
                    body: formData
                }).then(response => response.json())
                    .then(data => {


                        inputId.value = data.id;
                        inputTema.value = data.tema;
                        inputInterprete.value = data.interprete;
                        inputDisquera.value = data.id_disquera;
                        inputNacionalidad.value = data.id_nacionalidad;
                        inputTipo.value = data.id_tipo;
                        caratula.src = '<?= $dir ?>' + data.id + '.jpg'
                        inputLetra.value = data.letra;


                    }).catch(err => console.log(err));

            });

            eliminaModal.addEventListener('shown.bs.modal', event => {
                let button = event.relatedTarget
                let id = button.getAttribute('data-bs-id')
                eliminaModal.querySelector('.modal-footer #id').value = id
            })

            document.getElementById('campo').addEventListener('input', loadCanciones);
            numRegistros.addEventListener('change', loadCanciones);

            function loadCanciones(page = 1, order = 'id', direction = 'asc') {

                let campo = document.getElementById('campo').value;
                let limit = document.getElementById('num_registros').value;

                let formData = new FormData();
                formData.append('campo', campo);
                formData.append('limit', limit);
                formData.append('page', page);
                formData.append('order', order);
                formData.append('direction', direction);


                fetch('getCancion.php', {
                    method: 'POST',
                    body: formData
                }).then(response => response.json())
                    .then(data => {
                        let tbody = document.getElementById('contenido');
                        tbody.innerHTML = '';

                        const dir = 'caratulas/';
                        let startNumber = (page - 1) * limit + 1;



                        data.canciones.forEach((cancion, index) => {
                            let rowNumber = startNumber + index; // Número secuencial
                            let row = `<tr>                
                <td>${rowNumber}</td>                            
                <td>${cancion.tema}</td>                
                <td>${cancion.interprete}</td>
                <td>${cancion.id}</td>
                <td>${cancion.disquera}</td>
                <td>${cancion.nacionalidad}</td>
                <td>${cancion.tipo}</td>
                <td><img src="${dir}${cancion.id}.jpg?t=${Date.now()}" width="75"></td>
                
                <td>
                <a href="#" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#letraModal" data-bs-id="${cancion.id}"><i class="fa-solid fa-music"></i> Ver Letra</a>
                    <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editaModal" data-bs-id="${cancion.id}"><i class="fa-solid fa-pen-to-square"></i> Editar</a>
                    <a href="#" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#eliminaModal" data-bs-id="${cancion.id}"><i class="fa-regular fa-trash-can"></i> Eliminar</a>
                    
                    </td>
            </tr>`;
                            tbody.innerHTML += row;
                        });

                        updatePagination(data.totalPages, page, data.totalRecords, data.limit, data.currentPage);
                    }).catch(err => console.error(err));
            }

            function updatePagination(totalPages, currentPage, totalRecords, limit, displayedPage) {
                let paginationContainer = document.getElementById('pagination');
                paginationContainer.innerHTML = '';

                // Add the record count message
                let startRecord = (displayedPage - 1) * limit + 1;
                let endRecord = Math.min(displayedPage * limit, totalRecords);
                let recordCountMessage = `Mostrando ${startRecord}-${endRecord} de ${totalRecords} registros`;

                let messageDiv = document.createElement('div');
                messageDiv.className = 'pagination-message';
                messageDiv.textContent = recordCountMessage;
                paginationContainer.appendChild(messageDiv);

                // Create a container for pagination buttons
                let buttonContainer = document.createElement('div');
                buttonContainer.className = 'pagination-buttons';
                paginationContainer.appendChild(buttonContainer);

                for (let i = 1; i <= totalPages; i++) {
                    let button = document.createElement('button');
                    button.innerText = i;
                    button.classList.add('btn', 'btn-sm', 'btn-outline-primary', 'pagination-button');
                    if (i === parseInt(currentPage)) {
                        button.classList.add('active');
                    }
                    button.addEventListener('click', () => loadCanciones(i));
                    buttonContainer.appendChild(button);
                }
            }

            // Event listeners
            document.getElementById('campo').addEventListener('input', () => loadCanciones(1));
            document.getElementById('num_registros').addEventListener('change', () => loadCanciones(1));

            // Initial load
            loadCanciones(1);

            document.addEventListener('DOMContentLoaded', function () {
                let letraModal = document.getElementById('letraModal');

                letraModal.addEventListener('shown.bs.modal', function (event) {
                    let button = event.relatedTarget;
                    let id = button.getAttribute('data-bs-id');

                    let url = "getCancion.php";
                    let formData = new FormData();
                    formData.append('id', id);

                    fetch(url, {
                        method: "POST",
                        body: formData
                    }).then(response => response.json())
                        .then(data => {
                            let letraCancion = letraModal.querySelector('#letraCancion');
                            let tituloCancion = letraModal.querySelector('#tituloCancion');
                            let caratulaCancion = letraModal.querySelector('#caratulaCancion');

                            tituloCancion.textContent = data.tema;
                            caratulaCancion.src = '<?= $dir ?>' + data.id + '.jpg' // 
                            letraCancion.innerHTML = data.letra.replace(/\n/g, '<br>');
                        })
                        .catch(err => console.error(err));
                });

                letraModal.addEventListener('hide.bs.modal', function () {
                    let letraCancion = letraModal.querySelector('#letraCancion');
                    let tituloCancion = letraModal.querySelector('#tituloCancion');
                    let caratulaCancion = letraModal.querySelector('#caratulaCancion');

                    tituloCancion.textContent = "";
                    caratulaCancion.src = "";
                    letraCancion.textContent = "";
                });
            });

        </script>

        <script src="../../assets/js/bootstrap.bundle.min.js"></script>
        <?php $conn->close(); ?>
</body>

</html>