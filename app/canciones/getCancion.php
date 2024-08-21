<?php

require '../../clases/Conexion.php';

// Crear una nueva conexión
$conexion = new Conexion();
$conn = $conexion->conectar();

// Columns to show in the table
$columns = ['c.id', 'c.tema', 'c.interprete', 'd.casa', 'n.pais', 't.estilo'];

// Search field
$campo = isset($_POST['campo']) ? $conn->real_escape_string($_POST['campo']) : null;
$limit = isset($_POST['limit']) ? (int) $conn->real_escape_string($_POST['limit']) : 20;
$page = isset($_POST['page']) ? (int) $conn->real_escape_string($_POST['page']) : 1;
$order = isset($_POST['order']) ? $conn->real_escape_string($_POST['order']) : 'c.id';
$direction = isset($_POST['direction']) && $_POST['direction'] === 'desc' ? 'DESC' : 'ASC';

if (!in_array($order, ['c.id', 'c.tema', 'c.interprete', 'd.casa', 'n.pais', 't.estilo'])) {
    $order = 'c.id';
}

// Calculate the offset
$offset = ($page - 1) * $limit;

// ID to search (if exists)
$id = isset($_POST['id']) ? $conn->real_escape_string($_POST['id']) : null;

if ($id) {
    // If an ID is provided, we search only for that ID
    $sql = "SELECT id, tema, interprete, id_disquera, id_nacionalidad, id_tipo, letra FROM cancion WHERE id=$id LIMIT 1";
    $resultado = $conn->query($sql);
    $cancion = $resultado->fetch_assoc();
    echo json_encode($cancion, JSON_UNESCAPED_UNICODE);
} else {
    // If no ID is provided, we perform the dynamic search
    $where = '';
    if ($campo != null) {
        $where = "WHERE (";
        $cont = count($columns);
        for ($i = 0; $i < $cont; $i++) {
            $where .= $columns[$i] . " LIKE '%" . $campo . "%' OR ";
        }
        $where = substr_replace($where, "", -3);
        $where .= ")";
    }

    // Count total records for pagination
    $sqlCount = "SELECT COUNT(*) as total FROM cancion AS c
    INNER JOIN disquera AS d ON c.id_disquera = d.id
    INNER JOIN nacionalidad AS n ON c.id_nacionalidad = n.id
    INNER JOIN tipo AS t ON c.id_tipo = t.id
    $where";

    $resultCount = $conn->query($sqlCount);
    $rowCount = $resultCount->fetch_assoc();
    $totalRecords = $rowCount['total'];
    $totalPages = ceil($totalRecords / $limit);

    $sql = "SELECT
    c.id,
    c.tema,
    c.interprete,
    d.casa AS disquera,  
    n.pais AS nacionalidad,
    t.estilo AS tipo
FROM
    cancion AS c
INNER JOIN
    disquera AS d ON c.id_disquera = d.id
INNER JOIN
    nacionalidad AS n ON c.id_nacionalidad = n.id
INNER JOIN
    tipo AS t ON c.id_tipo = t.id
$where
ORDER BY $order $direction
LIMIT $offset, $limit";

    $resultado = $conn->query($sql);
    $canciones = [];
    if ($resultado->num_rows > 0) {
        while ($row = $resultado->fetch_assoc()) {
            $canciones[] = $row;
        }
    }

    $response = [
        'canciones' => $canciones,
        'totalPages' => $totalPages,
        'totalRecords' => $totalRecords,
        'currentPage' => $page,
        'limit' => $limit
    ];

    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    $conn->close(); 
}
