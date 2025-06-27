<div style="margin-bottom:16px;">
    <input
        type="text"
        id="filtroInstituciones"
        placeholder="Filtrar por nombre, RUC, dirección, teléfono o email..."
        style="padding:8px 12px; width:320px; border-radius:6px; border:1px solid #ccc; font-size:1em;"
        onkeyup="filtrarInstituciones()"
        autocomplete="off"
    >
    <button onclick="exportTableToExcel('tablaInstituciones')" style="margin-left:16px; padding:8px 16px; border-radius:6px; background:#218838; color:#fff; border:none;">
        <i class="fas fa-file-excel"></i> Exportar a Excel
    </button>
    <button onclick="exportTableToPDF()" style="margin-left:8px; padding:8px 16px; border-radius:6px; background:#c82333; color:#fff; border:none;">
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

<!-- Scripts de filtrado y exportación -->
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

// Exportar a Excel
function exportTableToExcel(tableID, filename = ''){
    var downloadLink;
    var dataType = 'application/vnd.ms-excel';
    var tableSelect = document.getElementById(tableID);
    // Solo tomar filas visibles
    var rows = tableSelect.querySelectorAll('tr');
    var tableHTML = '<table>';
    for(var i=0; i<rows.length; i++) {
        if(rows[i].style.display !== "none") {
            tableHTML += rows[i].outerHTML;
        }
    }
    tableHTML += '</table>';
    filename = filename?filename+'.xls':'instituciones.xls';
    downloadLink = document.createElement("a");
    document.body.appendChild(downloadLink);
    if(navigator.msSaveOrOpenBlob){
        var blob = new Blob(['\ufeff', tableHTML], {type: dataType});
        navigator.msSaveOrOpenBlob( blob, filename);
    }else{
        downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
        downloadLink.download = filename;
        downloadLink.click();
    }
}

// Exportar a PDF (requiere jsPDF y autoTable)
function exportTableToPDF() {
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

    doc.save('instituciones.pdf');
}
</script>

<!-- Incluye jsPDF y autoTable sólo una vez en tu proyecto -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>