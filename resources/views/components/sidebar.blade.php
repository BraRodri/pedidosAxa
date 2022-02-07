<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <a class="nav-link" href="{{ route('home') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Inicio
                </a>
                <div class="sb-sidenav-menu-heading">Acciones</div>
                <a class="nav-link" href="{{ route('estadisticas') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                    Estadisticas
                </a>
                <a class="nav-link" href="{{ route('factura') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-book"></i></div>
                    Control Factura
                </a>
                <a class="nav-link" href="{{ route('wms') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-dolly-flatbed"></i></div>
                    Control WMS
                </a>
            </div>
        </div>
        <div class="sb-sidenav-footer">
            Distribuciones AXA S.A.
        </div>
    </nav>
</div>
