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
    </ul>
</aside>
