<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="/" class="app-brand-link">
            <img src="{{asset('img/logo.png')}}" alt="logo" style="height:80px">
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1 mt-3">
        <li class="menu-item 
            {{is_active_sidebar([
                'dashboard'
            ])}}"
        >
            <a href="{{route('dashboard')}}" class="menu-link">
                <i class="menu-icon tf-icons fa-solid fa-home"></i>

                <div data-i18n="Kesenian">Dashboard</div>
            </a>
        </li>
        <!-- Kesenian -->
        <li class="menu-item 
            {{is_active_sidebar([
                'kesenian.index',
                'kesenian.create',
                'kesenian.edit',
                'kesenian.show'
            ])}}"
        >
            <a href="{{route('kesenian.index')}}" class="menu-link">
                <i class="menu-icon tf-icons fa-solid fa-masks-theater"></i>

                <div data-i18n="Kesenian">Kesenian</div>
            </a>
        </li>
        <!-- Kesenian -->
        <li class="menu-item 
            {{is_active_sidebar([
                'wisata.index',
                'wisata.create',
                'wisata.edit',
                'wisata.show'
            ])}}"
        >
            <a href="{{route('wisata.index')}}" class="menu-link">
                <i class="menu-icon tf-icons fa-solid fa-map-marked-alt"></i>

                <div data-i18n="Kesenian">Titik Wisata</div>
            </a>
        </li>

        <!-- Produk -->
        <li class="menu-item 
            {{is_active_sidebar([
                'produk.index',
                'produk.create',
                'produk.edit',
                'produk.show'
            ])}}"
        >
            <a href="{{route('produk.index')}}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-package"></i>
                <div data-i18n="Produk">Produk</div>
            </a>
        </li>

        <!-- Toko -->
        <li class="menu-item 
            {{is_active_sidebar([
                'toko.index',
                'toko.create',
                'toko.edit',
                'toko.show'
            ])}}"
        >
            <a href="{{route('toko.index')}}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-store"></i>
                <div data-i18n="Toko">Toko</div>
            </a>
        </li>

        <li class="menu-item 
            {{is_active_sidebar([
                'carousel.index',
                'carousel.show',
                'carousel.edit',
            ])}}"
        >
            <a href="{{route('carousel.index')}}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-image"></i>

                <div data-i18n="Kesenian">Carousel</div>
            </a>
        </li>

        <li class="menu-item
            {{is_active_sidebar([
                'pengguna.index',
                'pengguna.create',
                'pengguna.edit',
                'pengguna.show',
                'admin.index',
                'admin.create',
                'admin.edit',
                'admin.show'
            ])}}"
        >
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div data-i18n="Pengguna">Akun</div>
            </a>

            <ul class="menu-sub">
                <li class="menu-item
                    {{is_active_sidebar([
                        'admin.index',
                        'admin.create',
                        'admin.edit',
                        'admin.show'
                    ])}}"
                >
                    <a href="{{route('admin.index')}}" class="menu-link">
                        <div data-i18n="Without menu">Admin</div>
                    </a>
                </li>
                <li class="menu-item
                    {{is_active_sidebar([
                        'pengguna.index',
                        'pengguna.create',
                        'pengguna.edit',
                        'pengguna.show'
                    ])}}"
                >
                    <a href="{{route('pengguna.index')}}" class="menu-link">
                        <div data-i18n="Without menu">Pengguna</div>
                    </a>
                </li>
            </ul>
        </li>
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Game</span>
        </li>
        @if(is_dev())
        <li class="menu-item 
            {{is_active_sidebar([
                'game-stamps.index',
                'game-stamps.create',
                'game-stamps.edit',
            ])}}"
        >
            <a href="{{route('game-stamps.index')}}" class="menu-link">
                <i class="menu-icon tf-icons fa-solid fa-trophy"></i>

                <div data-i18n="Kesenian">Game Stamp</div>
            </a>
        </li>
        @endif
        <li class="menu-item 
            {{is_active_sidebar([
                'hadiah.index',
                'hadiah.create',
                'hadiah.edit',
            ])}}"
        >
            <a href="{{route('hadiah.index')}}" class="menu-link">
                <i class="menu-icon tf-icons fa-solid fa-gift"></i>

                <div data-i18n="Kesenian">Hadiah</div>
            </a>
        </li>
        
    </ul>
</aside>
