@php /*
<nav class="navbar navbar-expand-md navbar-dark  bg-dark mb-5">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            <img src="{{ asset('asset/img/icon.png') }}" alt="AXA" width="20%">
            Distribuciones AXA S.A.
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="{{ route('home') }}">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " aria-current="page" href="{{ route('estadisticas') }}">Estadisticas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " aria-current="page" href="{{ route('factura') }}">Control Factura</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " aria-current="page" href="{{ route('wms') }}">Control WMS</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ Auth::user()->name }}
                    </a>
                    <!-- Here's the magic. Add the .animate and .slideIn classes to your .dropdown-menu and you're all set! -->
                    <div class="dropdown-menu dropdown-menu-end animate slideIn" aria-labelledby="navbarDropdown">
                        <button type="button" id="boton_cerrar_sesion" class="dropdown-item" href="#">Cerrar Sesión</button>
                        <form id="form_cerrar_sesion" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
*/ @endphp

<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <!-- Navbar Brand-->
    <a class="navbar-brand ps-3" href="{{ route('home') }}">
        <img src="{{ asset('asset/img/icon.png') }}" alt="AXA" width="20%">
        Torre de Control
    </a>

    <!-- Sidebar Toggle-->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>

    <!-- Navbar Search-->

    <!-- Navbar-->
    <ul class="navbar-nav d-md-inline-block ms-auto me-0 me-md-3 my-2 my-md-0">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-user fa-fw"></i> {{ Auth::user()->name }}
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li>
                    <button type="button" id="boton_cerrar_sesion" class="dropdown-item" href="#">Cerrar Sesión</button>
                    <form id="form_cerrar_sesion" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </li>
    </ul>
</nav>
