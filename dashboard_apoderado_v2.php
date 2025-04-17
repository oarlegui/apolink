<?php
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'include/conexion.php';
require 'include/seguridad.php';

require_login();
require_rol(['apoderado']);

$apoderado_id = $_SESSION['usuario_id'];
$colegio_id = $_SESSION['colegio_id'];

// Obtener datos del alumno
$stmt = $conn->prepare("
  SELECT s.id AS alumno_id, s.nombre AS alumno, s.cuota_total,
         c.nombre AS curso, col.nombre AS colegio,
         IFNULL(SUM(p.monto), 0) AS pagado
  FROM students s
  JOIN cursos c ON s.curso_id = c.id
  JOIN colegios col ON s.colegio_id = col.id
  JOIN alumno_apoderado aa ON aa.alumno_id = s.id
  LEFT JOIN pagos p ON p.alumno_id = s.id
  WHERE aa.apoderado_id = ? AND s.colegio_id = ?
  GROUP BY s.id
  LIMIT 1
");
$stmt->bind_param("ii", $apoderado_id, $colegio_id);
$stmt->execute();
$alumno = $stmt->get_result()->fetch_assoc();
$stmt->close();

$cuota_total = (int) $alumno['cuota_total'];
$pagado = (int) $alumno['pagado'];
$pendiente = max($cuota_total - $pagado, 0);
$porcentaje = $cuota_total > 0 ? round(($pagado / $cuota_total) * 100) : 0;


// Obtener avances individuales de gira
$alumno_id = $alumno['alumno_id'];

$stmt_gira = $conn->prepare("
  SELECT IFNULL(SUM(monto), 0) AS total_aporte
  FROM aportes_gira
  WHERE alumno_id = ?
");
$stmt_gira->bind_param("i", $alumno_id);
$stmt_gira->execute();
$aporte = $stmt_gira->get_result()->fetch_assoc()['total_aporte'];
$stmt_gira->close();

$meta_gira = $alumno['cuota_gira'] ?? 0;
$pendiente_gira = max($meta_gira - $aporte, 0);


?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Apoderado - Apolink</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
    <style>
    body { display: flex; }
    .sidebar {
      width: 250px;
      min-height: 100vh;
      background-color: #f8f9fa;
      padding: 20px;
      border-right: 1px solid #dee2e6;
    }
    .main {
      flex: 1;
      padding: 30px;
    }
    .card-img-overlay {
      background: linear-gradient(0deg, rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.2));
      color: white;
      display: flex;
      align-items: flex-end;
      justify-content: center;
    }
    .card_box {
      max-width: 300px;
      height: 300px;
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar text-center">
  <img src="https://www.arlegui-it.cl/sistema-gestion-curso/includes/assets/images/logo_white.png" alt="Apolink" class="img-fluid mb-4" style="max-height: 50px;">
  <p class="text-muted">Perfil: <strong><?= ucfirst($_SESSION['rol']) ?></strong></p>
  <a href="dashboard_apoderado_v2.php" class="btn btn-outline-primary w-100 mb-2">🏠 Inicio</a>
  <a href="pagos.php" class="btn btn-outline-success w-100 mb-2">💳 Subir Pago</a>
  <a href="pagos_historial.php" class="btn btn-outline-primary w-100 mb-2">📃 Historial</a>
  <a href="eventos/calendar.php" class="btn btn-outline-secondary w-100 mb-2">📅 Calendario</a>
  <a href="empresas/empresas.php" class="btn btn-outline-warning w-100 mb-2">🏪 Servicios</a>
  <a href="logout.php" class="btn btn-outline-danger w-100 mt-4">🚪 Cerrar sesión</a>
</div>

<!-- Contenido -->
<div class="main">
  
  <h4 class="mb-2">👋 Bienvenido, <?= htmlspecialchars($_SESSION['nombre'] ?? 'Usuario') ?></h4>

  <p class="text-muted">Alumno: <strong><?= $alumno['alumno'] ?></strong> | Curso: <strong><?= $alumno['curso'] ?></strong> | Colegio: <strong><?= $alumno['colegio'] ?></strong></p>
    <!-- Estado de pago -->
  <div class="row mt-4 mb-4">
    <div class="col-md-3">
      <div class="card text-center shadow-sm">
        <div class="card-body">
          <h6>Total Cuota Anual</h6>
          <strong>$<?= number_format($cuota_total, 0, ',', '.') ?></strong>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-center shadow-sm">
        <div class="card-body">
          <h6>Pagado</h6>
          <strong class="text-success">$<?= number_format($pagado, 0, ',', '.') ?></strong>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-center shadow-sm">
        <div class="card-body">
          <h6>Pendiente</h6>
          <strong class="text-danger">$<?= number_format($pendiente, 0, ',', '.') ?></strong>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-center shadow-sm">
        <div class="card-body">
          <h6>Avance</h6>
          <strong><?= $porcentaje ?>%</strong>
        </div>
      </div>
    </div>
  </div>

  <?php $mostrar_gira = true; // Puedes cambiar a false para ocultarlo ?>
<div class="row">
  <!-- Gráfico Estado de Pago -->
  <div class="col-md-6">
    <div class="card shadow-sm p-4 mb-4">
      <h6 class="mb-3">📊 Estado de pago actual</h6>
      <canvas id="graficoEstadoPago" height="200"></canvas>
    </div>
  </div>

  <!-- Gráfico Gira de Estudios -->
   <div class="col-md-6">
    <div class="card shadow-sm p-4 mb-4">
      <h6 class="mb-3">🎒 Avance Gira de Estudios</h6>
      <?php if ($meta_gira > 0): ?>
        <canvas id="graficoGiraAlumno" height="200"></canvas>
      <?php else: ?>
        <p class="text-muted">Aún no se ha definido la meta de gira para este alumno.</p>
      <?php endif; ?>
    </div>
  </div>
</div>

<script>
  new Chart(document.getElementById('graficoEstadoPago').getContext('2d'), {
    type: 'bar',
    data: {
      labels: ['Pagado', 'Pendiente'],
      datasets: [{
        label: 'Cuota Curso',
        data: [<?= $pagado ?>, <?= $pendiente ?>],
        backgroundColor: ['#198754', '#DC3545']
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } },
      scales: { y: { beginAtZero: true } }
    }
  });

  <?php if ($meta_gira > 0): ?>
  new Chart(document.getElementById('graficoGiraAlumno').getContext('2d'), {
    type: 'bar',
    data: {
      labels: ['Aportado', 'Faltante'],
      datasets: [{
        label: 'Gira Estudio',
        data: [<?= $aporte ?>, <?= $pendiente_gira ?>],
        backgroundColor: ['#0d6efd', '#adb5bd']
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } },
      scales: { y: { beginAtZero: true } }
    }
  });
  <?php endif; ?>
</script>


<script>
  // Estado de pago actual
  new Chart(document.getElementById('graficoEstadoPago').getContext('2d'), {
    type: 'bar',
    data: {
      labels: ['Pagado', 'Pendiente'],
      datasets: [{
        label: 'Estado de cuota',
        data: [<?= $pagado ?>, <?= $pendiente ?>],
        backgroundColor: ['#198754', '#DC3545']
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } },
      scales: { y: { beginAtZero: true } }
    }
  });

  // Gira de estudios (simulado)
 <?php if ($gira_activa): ?>
  new Chart(document.getElementById('graficoGira').getContext('2d'), {
    type: 'bar',
    data: {
      labels: ['Guardado', 'Meta'],
      datasets: [{
        label: 'Fondo Gira',
        data: [<?= $monto_actual ?>, <?= $monto_meta ?>],
        backgroundColor: ['#0d6efd', '#adb5bd']
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } },
      scales: { y: { beginAtZero: true } }
    }
  });
<?php endif; ?>



  <!-- Espacio reservado para segundo gráfico -->
  <div class="card shadow-sm p-4 mb-4">
    <h6 class="mb-3">📈 Historial mensual de mis pagos</h6>
    <p class="text-muted">(próximamente)</p>

    <a href="pagos_historial.php" class="btn btn-sm btn-outline-secondary mt-3">Ver más</a>
  </div>

  <!-- Script gráfico -->
  <script>
    const ctx = document.getElementById('graficoEstadoPago').getContext('2d');
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['Pagado', 'Pendiente'],
        datasets: [{
          label: 'Estado de cuota',
          data: [<?= $pagado ?>, <?= $pendiente ?>],
          backgroundColor: ['#198754', '#DC3545']
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: false }
        },
        scales: {
          y: { beginAtZero: true }
        }
      }
    });
  </script>
  <!-- MODULO PRÓXIMOS EVENTOS Y EVALUACIONES -->
<div class="card shadow-sm p-4 mb-4">
  <h6 class="mb-3">📅 Próximos eventos y evaluaciones</h6>
  <ul class="list-group">
    <?php
    
    // Establecer la configuración regional para que los meses se muestren en español
    setlocale(LC_TIME, 'es_ES.UTF-8', 'es_ES', 'spanish');

    // Verificar si la extensión intl está habilitada
    if (class_exists('IntlDateFormatter')) {
        // Crear formateador de fechas con localización en español
        $formatter = new IntlDateFormatter('es_ES', IntlDateFormatter::LONG, IntlDateFormatter::NONE);
        $formatter->setPattern('d MMM'); // Formato: "22 abr"
    } else {
        $formatter = null; // Si intl no está disponible, dejamos como null
    }
    $stmt = $conn->prepare("
      SELECT titulo, fecha, tipo
      FROM eventos
      WHERE curso_id IN (
        SELECT s.curso_id FROM students s
        JOIN alumno_apoderado aa ON s.id = aa.alumno_id
        WHERE aa.apoderado_id = ?
      )
      AND fecha >= CURDATE()
      ORDER BY fecha ASC
      LIMIT 5
    ");
    $stmt->bind_param("i", $apoderado_id);
    $stmt->execute();
    $eventos = $stmt->get_result();

    if ($eventos->num_rows > 0):
      while ($e = $eventos->fetch_assoc()):
        // Asignar colores según el tipo
        $color = '';
        switch ($e['tipo']) {
          case 'evento':
            $color = 'bg-primary'; // Azul
            break;
          case 'evaluación':
            $color = 'bg-danger'; // Rojo
            break;
          case 'otro':
            $color = 'bg-secondary'; // Gris
            break;
          default:
            $color = 'bg-light text-dark'; // Color por defecto
            break;
        }

        // Convertir la fecha al formato deseado
        $fecha = new DateTime($e['fecha']);
        $fecha_formateada = $formatter ? $formatter->format($fecha) : $fecha->format('d M'); // Fallback si intl no está disponible
    ?>
      <li class="list-group-item d-flex justify-content-between align-items-center">
        <div>
          <strong><?= $formatter->format($fecha) ?></strong> – <?= htmlspecialchars($e['titulo']) ?>
        </div>
        <span class="badge <?= $color ?>"><?= ucfirst($e['tipo']) ?></span>
      </li>
    <?php endwhile; else: ?>
      <li class="list-group-item text-muted">No hay eventos próximos registrados.</li>
    <?php endif;
    $stmt->close();
    ?>
  </ul>
  <a href="eventos/calendar.php" class="btn btn-sm btn-outline-secondary mt-3">Ver más</a>
</div>

  <!-- MODULO DE SERVICIOS ÚTILES DESTACADOS -->
  <!-- Servicios útiles destacados -->
<div class="card shadow-sm p-4 mb-5">
  <h6 class="mb-3">🏪 Servicios útiles recomendados</h6>
  <div class="row">
    <?php
    // Consulta para obtener las empresas patrocinadas
    $stmt = $conn->prepare("
      SELECT id, nombre, descripcion, imagen_principal, telefono, email
      FROM empresas
      WHERE patrocinada = 1
      ORDER BY RAND()
      LIMIT 4
    ");
    $stmt->execute();
    $empresas = $stmt->get_result();

    if ($empresas->num_rows > 0):
      while ($empresa = $empresas->fetch_assoc()): ?>
        <div class="col-md-3 mb-4">
          <div class="card shadow-sm">
            <!-- Imagen principal de la empresa -->
            <img 
              src="uploads/empresas/<?= htmlspecialchars($empresa['imagen_principal']) ?>" 
              class="card-img-top img-fluid" 
              alt="<?= htmlspecialchars($empresa['nombre']) ?>" 
              style="max-height: 200px; object-fit: cover;"
            >
            <div class="card-body text-center">
              <!-- Nombre de la empresa -->
              <h5 class="card-title"><?= htmlspecialchars($empresa['nombre']) ?></h5>
              <!-- Descripción corta -->
              <p class="card-text text-muted"><?= htmlspecialchars($empresa['descripcion']) ?></p>
              <!-- Botones de acción -->
              <div class="d-flex justify-content-between mt-3">
                <a href="tel:<?= htmlspecialchars($empresa['telefono']) ?>" class="btn btn-sm btn-outline-primary" title="Llamar">
                  📞 Teléfono
                </a>
                <a href="mailto:<?= htmlspecialchars($empresa['email']) ?>" class="btn btn-sm btn-outline-success" title="Enviar Email">
                  📧 Email
                </a>
                <a href="empresa.php?id=<?= htmlspecialchars($empresa['id']) ?>" class="btn btn-sm btn-outline-secondary" title="Ver más">
                  👁️ Ver más
                </a>
              </div>
            </div>
          </div>
        </div>
      <?php endwhile; 
    else: ?>
      <div class="col-12">
        <p class="text-muted text-center">No hay empresas patrocinadas disponibles en este momento.</p>
      </div>
    <?php endif;
    $stmt->close();
    ?>
  </div>
  <a href="empresas/empresas.php" class="btn btn-primary mt-3">Ver Todas las Empresas</a>
</div>

  <!-- MODULO DE GALERÍA DE EVENTOS -->
<div class="card shadow-sm p-4 mb-5">
  <h6 class="mb-3">🖼️ Galería de eventos recientes</h6>
  <div class="row">
    <?php
    // Consulta para obtener las galerías según la búsqueda
    $query = "
        SELECT g.id AS galeria_id, g.titulo, g.fecha_creacion, MIN(ig.ruta_imagen) AS imagen_random
        FROM galerias g
        JOIN imagenes_galeria ig ON g.id = ig.galeria_id
        GROUP BY g.id
        ORDER BY g.fecha_creacion DESC
        LIMIT 3
    ";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $galerias = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    // Verificar si hay resultados
    if ($galerias && count($galerias) > 0):
      foreach ($galerias as $evento): ?>
        <div class="col-md-4 mb-4">
          <div class="card shadow-sm">
            <img src="<?= htmlspecialchars($evento['imagen_random']) ?>" class="card-img" alt="<?= htmlspecialchars($evento['titulo']) ?>">
            <div class="card-img-overlay d-flex flex-column justify-content-end text-center">
              <h5 class="card-title mb-2"><?= htmlspecialchars($evento['titulo']) ?></h5>
              <p class="card-text text-white-50"><?= date('d M Y', strtotime($evento['fecha_creacion'])) ?></p>
            </div>
          </div>
        </div>
      <?php endforeach; 
    else: ?>
      <div class="col-12">
        <p class="text-muted">No se encontraron galerías recientes.</p>
      </div>
    <?php endif; ?>
  </div>
  <a href="galeria_completa.php" class="btn btn-primary mt-3">Ver Galería Completa</a>
</div>

        <!-- Modal del evento -->
        <div class="modal fade" id="modalEvento<?= $evento['evento_id'] ?>" tabindex="-1" aria-labelledby="modalLabel<?= $evento['evento_id'] ?>" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="modalLabel<?= $evento['evento_id'] ?>"><?= htmlspecialchars($evento['titulo']) ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                
              </div>
              <div class="modal-body">
                <p><strong>Fecha:</strong> <?= date('d M Y', strtotime($evento['fecha'])) ?></p>
                <p><strong>Descripción:</strong> <?= htmlspecialchars($evento['descripcion']) ?></p>
                <div class="row">
                  <?php
                  // Obtener imágenes del evento
                  $stmt_imgs = $conn->prepare("SELECT ruta FROM imagenes_eventos WHERE evento_id = ?");
                  $stmt_imgs->bind_param("i", $evento['evento_id']);
                  $stmt_imgs->execute();
                  $imagenes = $stmt_imgs->get_result();
                  while ($img = $imagenes->fetch_assoc()):
                  ?>
                    <div class="col-md-4 mb-3">
                      <a href="uploads/eventos/<?= htmlspecialchars($img['ruta']) ?>" class="glightbox" data-gallery="galeriaEvento<?= $evento['evento_id'] ?>">
                        <img src="uploads/eventos/<?= htmlspecialchars($img['ruta']) ?>" class="img-fluid rounded shadow-sm" alt="Imagen del evento">
                      </a>
                    </div>
                  <?php endwhile; ?>
                  <?php $stmt_imgs->close(); ?>
                </div>
              </div>
            </div>
          </div>
        </div>
 
    </div>
    <a href="eventos/calendar.php" class="btn btn-sm btn-outline-secondary mt-3">Ver más</a>
  </div>

  <!-- Inicializar GLightbox -->
  <script>
    const lightbox = GLightbox({ selector: '.glightbox' });
  </script>
</div>
</body>
</html>

