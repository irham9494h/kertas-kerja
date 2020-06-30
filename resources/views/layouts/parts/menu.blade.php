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
                        <a class="dropdown-item @yield('menu-rekening')" href="{{route('rek.akun.index')}}">Rekening</a>
                        <a class="dropdown-item @yield('menu-organisasi')" href="{{route('org.urusan.index')}}">Organisasi</a>
                    </div>
                </li>
                <li class="nav-item dropdown @yield('menu-kk')">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        Kertas Kerja <span class="caret"></span>
                    </a>

                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item @yield('menu-kertas-kerja')" href="{{route('sb-tahun')}}">Kertas
                            Kerja</a>
                        <a class="dropdown-item @yield('menu-laporan')" href="{{route('lap-kk')}}">Laporan</a>
                    </div>
                </li>
                @can('isSuperAdmin')
                    <li class="nav-item dropdown @yield('menu-pengaturan')">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            Pengaturan <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item @yield('menu-tahun-rekening')" href="{{route('tahun-rek.index')}}">Tahun
                                Rekening</a>
                            <a class="dropdown-item @yield('menu-menejemen-pengguna')" href="{{route('user.index')}}">Manajemen
                                Pengguna</a>
                        </div>
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
