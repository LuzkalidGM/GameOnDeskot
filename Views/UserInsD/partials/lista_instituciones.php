<div style="margin-bottom:16px;">
    <input
        type="text"
        id="filtroInstituciones"
        placeholder="Filtrar por nombre de institucion..."
        style="padding:8px 12px; width:320px; border-radius:6px; border:1px solid #ccc; font-size:1em;"
        onkeyup="filtrarInstituciones()"
        autocomplete="off"
    >
    <button onclick="modalExportarExcel('tablaInstituciones')" style="margin-left:16px; padding:8px 16px; border-radius:6px; background:#218838; color:#fff; border:none;">
        <i class="fas fa-file-excel"></i> Exportar a Excel/CSV
    </button>
    <button onclick="modalExportarPDF()" style="margin-left:8px; padding:8px 16px; border-radius:6px; background:#c82333; color:#fff; border:none;">
        <i class="fas fa-file-pdf"></i> Exportar a PDF
    </button>
</div>

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
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($instituciones as $i => $inst): ?>
                <tr>
                    <td><?= $i+1 ?></td>
                    <td><?= htmlspecialchars($inst['nombre']) ?></td>
                    <td><?= htmlspecialchars($inst['ruc']) ?></td>
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

<!-- Modal para pedir el nombre del archivo -->
<div id="modalNombreArchivo" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.3); align-items:center; justify-content:center;">
  <div style="background:#fff; padding:24px 20px; border-radius:8px; min-width:300px; box-shadow:0 2px 10px #0002;">
    <h3 style="margin-top:0;">Guardar archivo como...</h3>
    <input type="text" id="nombreArchivoInput" style="width:100%; padding:8px; font-size:1em; margin-bottom:14px;" placeholder="Ej: instituciones">
    <div style="text-align:right;">
      <button onclick="cerrarModalNombreArchivo()" style="margin-right:10px; background:#ccc; border:none; padding:8px 14px; border-radius:5px;">Cancelar</button>
      <button id="btnAceptarNombreArchivo" style="background:#218838; color:#fff; border:none; padding:8px 16px; border-radius:5px;">Aceptar</button>
    </div>
  </div>
</div>

<script>
// Filtrado instantáneo
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

// Modal para pedir nombre archivo y exportar
let tipoExportacion = null;
let tablaExportar = '';

function abrirModalNombreArchivo(tipo, tablaID) {
    tipoExportacion = tipo; // 'excel' o 'pdf'
    tablaExportar = tablaID || '';
    document.getElementById('nombreArchivoInput').value = '';
    document.getElementById('modalNombreArchivo').style.display = 'flex';
    document.getElementById('nombreArchivoInput').focus();

    // Botón aceptar
    document.getElementById('btnAceptarNombreArchivo').onclick = function() {
        let nombre = document.getElementById('nombreArchivoInput').value.trim();
        if (!nombre) {
            alert('Por favor ingrese un nombre de archivo.');
            return;
        }
        cerrarModalNombreArchivo();
        if (tipoExportacion === 'excel') {
            exportTableToExcel(tablaExportar, nombre);
        } else if (tipoExportacion === 'pdf') {
            exportTableToPDF(nombre);
        }
    }
}

function cerrarModalNombreArchivo() {
    document.getElementById('modalNombreArchivo').style.display = 'none';
}

// Llama estos en los botones:
function modalExportarExcel(tablaID){
    abrirModalNombreArchivo('excel', tablaID);
}
function modalExportarPDF(){
    abrirModalNombreArchivo('pdf');
}

// Exportar a Excel/CSV (punto y coma para compatibilidad)
function exportTableToExcel(tableID, filename = ''){
    let table = document.getElementById(tableID);
    let rows = table.querySelectorAll('tr');
    let csv = [];
    for (let i = 0; i < rows.length; i++) {
        // Solo filas visibles
        if (rows[i].style.display === "none") continue;
        let row = [], cols = rows[i].querySelectorAll('th,td');
        for (let j = 0; j < cols.length; j++) {
            let text = cols[j].innerText.replace(/\n/g, ' ').replace(/\s\s+/g, ' ').trim();
            text = '"' + text.replace(/"/g, '""') + '"';
            row.push(text);
        }
        csv.push(row.join(";")); // Cambiado a punto y coma
    }
    let csvFile = new Blob([csv.join("\n")], { type: "text/csv" });
    filename = filename ? filename + '.csv' : 'instituciones.csv';
    let downloadLink = document.createElement("a");
    downloadLink.download = filename;
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = "none";
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}

// Exportar a PDF (requiere jsPDF y autoTable)
function exportTableToPDF(filename = 'instituciones') {
    var { jsPDF } = window.jspdf;
    var doc = new jsPDF('l', 'pt', 'a4');
    doc.text("Listado de Instituciones Deportivas", 40, 40);

    let table = document.getElementById("tablaInstituciones");
    let rows = Array.from(table.querySelectorAll("tbody tr")).filter(r => r.style.display !== "none");
    let body = rows.map(row => Array.from(row.children).map(cell => cell.innerText.trim()));
    let head = [Array.from(table.querySelectorAll("thead tr th")).map(th => th.innerText.trim())];

    doc.autoTable({
        head: head,
        body: body,
        startY: 60,
        styles: { fontSize: 8 },
        headStyles: { fillColor: [184,28,34] }
    });

    doc.save(filename + '.pdf');
}
</script>

<!-- Incluye jsPDF y autoTable una sola vez en tu proyecto -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>