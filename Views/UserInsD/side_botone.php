<div class="sidebar-panel-inst">
    <div class="sidebar-card-inst">
        <h3><i class="fas fa-info-circle"></i> Ayuda</h3>
        <div class="info-content">
            <p>Haz clic en una institución para ver más información o comparar servicios y precios.</p>
            <!-- Botones de exportación para pruebas -->
            <button id="btnExcelSidebar" type="button" style="margin:10px 0; padding:8px 16px; border-radius:6px; background:#218838; color:#fff; border:none;">
                <i class="fas fa-file-excel"></i> Exportar a Excel (Prueba)
            </button>
            <button id="btnPDFSidebar" type="button" style="margin:10px 0 0 10px; padding:8px 16px; border-radius:6px; background:#c82333; color:#fff; border:none;">
                <i class="fas fa-file-pdf"></i> Exportar a PDF (Prueba)
            </button>
        </div>
    </div>
</div>
<script>
// Asignar eventos a los botones de la barra lateral
document.addEventListener('DOMContentLoaded', function() {
    if(document.getElementById('btnExcelSidebar')) {
        document.getElementById('btnExcelSidebar').addEventListener('click', function(){
            if(typeof exportTableToExcel === "function") exportTableToExcel('tablaInstituciones');
        });
    }
    if(document.getElementById('btnPDFSidebar')) {
        document.getElementById('btnPDFSidebar').addEventListener('click', function(){
            if(typeof exportTableToPDF === "function") exportTableToPDF();
        });
    }
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var excelBtn = document.getElementById('btnExcelSidebar');
    var pdfBtn = document.getElementById('btnPDFSidebar');
    if (excelBtn) {
        excelBtn.disabled = false; // fuerza habilitado
        excelBtn.style.pointerEvents = "auto";
        excelBtn.style.opacity = 1;
    }
    if (pdfBtn) {
        pdfBtn.disabled = false; // fuerza habilitado
        pdfBtn.style.pointerEvents = "auto";
        pdfBtn.style.opacity = 1;
    }
});
</script>