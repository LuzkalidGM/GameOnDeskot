<?php
// Estadística: instalación con mayor cantidad de áreas deportivas
$max_areas = 0;
$top_instalacion = null;
foreach ($instituciones as $inst) {
    $areas = isset($inst['total_areas']) ? $inst['total_areas'] : 0;
    if ($areas > $max_areas) {
        $max_areas = $areas;
        $top_instalacion = $inst;
    }
}
?>

<div style="margin-bottom:16px;">
    <input
        type="text"
        id="filtroInstituciones"
        placeholder="Filtrar por nombre, RUC, dirección, teléfono o email..."
        style="padding:8px 12px; width:320px; border-radius:6px; border:1px solid #ccc; font-size:1em;"
        autocomplete="off"
    >
</div>

<?php if ($top_instalacion): ?>
    <div style="padding:16px; background:#fff3f3; border:1px solid #ffcaca; border-radius:10px; margin-bottom:18px; display:flex; align-items:center;">
        <i class="fas fa-trophy" style="color:#b81c22; font-size:1.6em; margin-right:18px;"></i>
        <div>
            <strong><?= htmlspecialchars($top_instalacion['nombre']) ?></strong> es la instalación con más áreas deportivas (<?= $max_areas ?> áreas).
        </div>
    </div>
<?php endif; ?>

<?php if (!empty($instituciones)): ?>
    <div style="overflow-x:auto;">
    <table id="tablaInstituciones" class="table table-striped" style="width:100%; min-width:750px;">
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>RUC</th>
                <th>Dirección</th>
                <th>Teléfono</th>
                <th>Email</th>
                <th>Calificación</th>
                <th>Tarifa (S/.)</th>
                <th>Áreas deportivas</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($instituciones as $i => $inst): ?>
                <tr>
                    <td><?= $i+1 ?></td>
                    <td><?= htmlspecialchars($inst['nombre']) ?></td>
                    <td><?= htmlspecialchars($inst['ruc_institucion']) ?></td>
                    <td><?= htmlspecialchars($inst['direccion']) ?></td>
                    <td>
                        <a href="https://wa.me/<?= preg_replace('/\D/', '', $inst['telefono']) ?>"
                        target="_blank" rel="noopener noreferrer"
                        title="Contactar a <?= htmlspecialchars($inst['nombre']) ?> por WhatsApp">
                            <?= htmlspecialchars($inst['telefono']) ?>
                            <i class="fab fa-whatsapp" style="color:#25d366;"></i>
                        </a>
                    </td>
                    <td><?= htmlspecialchars($inst['email']) ?></td>
                    <td><?= number_format($inst['calificacion'], 1) ?></td>
                    <td><?= number_format($inst['tarifa'], 2) ?></td>
                    <td><?= isset($inst['total_areas']) ? (int)$inst['total_areas'] : 0 ?></td>
                    <td>
                        <a href="ver_institucion.php?id=<?= $inst['id'] ?>" class="btn-small-inst btn-edit" title="Ver Detalles">
                            <i class="fas fa-eye"></i> Ver Detalle
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
<?php else: ?>
    <p>No hay instituciones registradas.</p>
<?php endif; ?>

<!-- Modal para mostrar el CSV si está en Nativefier/Electron -->
<div id="csvModal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.6); z-index:9999; align-items:center; justify-content:center;">
  <div style="background:#fff; padding:24px; border-radius:8px; max-width:95vw; max-height:95vh; overflow:auto;">
    <h3>Exportar a Excel/CSV</h3>
    <p>Copia el siguiente contenido y pégalo en Excel:</p>
    <textarea id="csvTextArea" style="width:100%; height:300px;"></textarea>
    <div style="margin-top:12px;">
      <button onclick="copyCSVToClipboard()">Copiar al portapapeles</button>
      <button onclick="closeCSVModal()">Cerrar</button>
    </div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    function filtrarInstituciones() {
        let input = document.getElementById('filtroInstituciones');
        let filtro = input.value.toLowerCase();
        let tabla = document.getElementById('tablaInstituciones');
        let filas = tabla.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

        for (let i = 0; i < filas.length; i++) {
            let celdas = filas[i].getElementsByTagName('td');
            let mostrar = false;
            // Filtrar por nombre, RUC, dirección, teléfono o email
            for (let j = 1; j <= 5; j++) {
                if (celdas[j] && celdas[j].textContent.toLowerCase().indexOf(filtro) > -1) {
                    mostrar = true;
                    break;
                }
            }
            filas[i].style.display = mostrar ? '' : 'none';
        }
    }

    function exportTableToExcel(tableID){
        let isElectron = navigator.userAgent.toLowerCase().indexOf('electron') > -1 || window.nativefier;
        let table = document.getElementById(tableID);
        let rows = table.querySelectorAll('tr');
        let csv = [];
        for (let i = 0; i < rows.length; i++) {
            if (rows[i].style.display === "none") continue;
            let row = [], cols = rows[i].querySelectorAll('th,td');
            for (let j = 0; j < cols.length; j++) {
                let text = cols[j].innerText.replace(/\n/g, ' ').replace(/\s\s+/g, ' ').trim();
                text = '"' + text.replace(/"/g, '""') + '"';
                row.push(text);
            }
            csv.push(row.join(";"));
        }
        let csvString = csv.join("\r\n");

        if (isElectron) {
            document.getElementById('csvTextArea').value = csvString;
            document.getElementById('csvModal').style.display = 'flex';
        } else {
            let csvFile = new Blob([csvString], { type: "text/csv" });
            let downloadLink = document.createElement("a");
            downloadLink.download = 'instituciones.csv';
            downloadLink.href = window.URL.createObjectURL(csvFile);
            downloadLink.style.display = "none";
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
        }
    }

    function copyCSVToClipboard() {
        let textarea = document.getElementById('csvTextArea');
        textarea.select();
        document.execCommand('copy');
        alert('¡CSV copiado al portapapeles!');
    }

    function closeCSVModal() {
        document.getElementById('csvModal').style.display = 'none';
    }

    function exportTableToPDF() {
        if (!window.jspdf || !window.jspdf.jsPDF) {
            alert("jsPDF no está cargado correctamente.");
            return;
        }
        let isElectron = navigator.userAgent.toLowerCase().indexOf('electron') > -1 || window.nativefier;
        var { jsPDF } = window.jspdf;
        var doc = new jsPDF('l', 'pt', 'a4');
        doc.text("Listado de Instituciones Deportivas", 40, 40);

        let table = document.getElementById("tablaInstituciones");
        let rows = Array.from(table.querySelectorAll("tbody tr")).filter(r => r.style.display !== "none");
        let body = rows.map(row => Array.from(row.children).map(cell => cell.innerText.trim()));
        let head = [Array.from(table.querySelectorAll("thead tr th")).map(th => th.innerText.trim())];

        if (doc.autoTable) {
            doc.autoTable({
                head: head,
                body: body,
                startY: 60,
                styles: { fontSize: 8 },
                headStyles: { fillColor: [184,28,34] }
            });
            try {
                doc.save('instituciones.pdf');
            } catch(e) {
                if (isElectron) {
                    alert("La exportación a PDF no es compatible en este modo escritorio. Use la versión web o copie la tabla manualmente.");
                } else {
                    alert("Ocurrió un error al exportar a PDF.");
                }
            }
        } else {
            alert("autoTable plugin no está cargado correctamente.");
        }
    }

    document.getElementById('filtroInstituciones').addEventListener('keyup', filtrarInstituciones);

    // Expone funciones globales para el modal
    window.copyCSVToClipboard = copyCSVToClipboard;
    window.closeCSVModal = closeCSVModal;
});
</script>