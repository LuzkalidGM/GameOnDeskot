<?php
// Sumar totales
$total_instalaciones = 0;
$total_areas = 0;
foreach ($instituciones as $inst) {
    $total_instalaciones += $model->contarInstalacionesPorInstitucion($inst['id']);
    $total_areas += $model->contarAreasPorInstitucion($inst['id']);
}
?>

<div style="display: flex; gap: 24px; align-items: flex-start;">
    <div style="flex: 1;">
        <h2 style="margin-bottom:12px;">
            <i class="fas fa-list-alt" style="color:#b81c22"></i> Instituciones Registradas
        </h2>
        <!-- Tarjeta resumen fuera de la tabla -->
        <div style="display:flex; gap: 24px; margin-bottom:16px;">
            <div style="
                background: #fff3f3;
                border: 1px solid #ffcaca;
                border-radius: 10px;
                padding: 18px 28px;
                min-width: 180px;
                box-shadow: 0 1px 4px rgba(184,28,34,0.03);
                display: flex;
                align-items: center;">
                <i class="fas fa-building" style="color:#b81c22; font-size:1.6em; margin-right:16px;"></i>
                <div>
                    <div style="font-size:1.4em; font-weight:bold; color:#b81c22;">
                        <?= $total_instalaciones ?>
                    </div>
                    <div style="font-size:1em; color:#b81c22;">Instalaciones deportivas</div>
                </div>
            </div>
            <div style="
                background: #fff3f3;
                border: 1px solid #ffcaca;
                border-radius: 10px;
                padding: 18px 28px;
                min-width: 180px;
                box-shadow: 0 1px 4px rgba(184,28,34,0.03);
                display: flex;
                align-items: center;">
                <i class="fas fa-futbol" style="color:#b81c22; font-size:1.6em; margin-right:16px;"></i>
                <div>
                    <div style="font-size:1.4em; font-weight:bold; color:#b81c22;">
                        <?= $total_areas ?>
                    </div>
                    <div style="font-size:1em; color:#b81c22;">Áreas deportivas</div>
                </div>
            </div>
        </div>
        <!-- Aquí va tu tabla original -->
        <!-- ... -->
    </div>
    <!-- Panel de ayuda (ya existente en tu diseño) -->
    <div style="min-width:330px;">
        <div class="ayuda-card">
            <div style="display:flex; align-items:center; gap:8px;">
                <i class="fas fa-question-circle" style="color:#b81c22; font-size:1.5em;"></i>
                <span style="font-weight:600; font-size:1.17em;">Ayuda</span>
            </div>
            <div style="margin-top:12px; color:#222;">
                Haz clic en una institución para ver más información o comparar servicios y precios.
            </div>
        </div>
    </div>
</div>