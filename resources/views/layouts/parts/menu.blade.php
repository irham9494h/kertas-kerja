<nav class="main-header navbar navbar-expand-md navbar-dark navbar-danger shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            Kertas Kerja
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">

            <ul class="navbar-nav mr-auto">
                <li class="nav-item @yield('menu-beranda')">
                    <a href="{{route('home')}}" class="nav-link">Beranda</a>
                </li>
                <li class="nav-item dropdown @yield('menu-master')">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        Data Master <span class="caret"></span>
                    </a>

                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item @yield('menu-rekening')" href="#">Rekening</a>
                        <a class="dropdown-item @yield('menu-organisasi')" href="{{route('org.urusan.index')}}">Organisasi</a>
                    </div>
                </li>
                @can('isSuperAdmin')
                    <li class="nav-item @yield('menu-menejemen-pengguna')">
                        <a href="{{route('manajemen-pengguna.index')}}" class="nav-link">Manajemen Pengguna</a>
                    </li>
                @endcan
            </ul>

            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::user()->nama }} <span class="caret"></span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                              style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
