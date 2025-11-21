<div class="dropdown position-fixed bottom-0 end-0 mb-3 me-3 bd-mode-toggle">
    <button class="btn btn-bd-primary py-2 dropdown-toggle d-flex align-items-center" id="bd-theme" type="button" aria-expanded="false" data-bs-toggle="dropdown" aria-label="Toggle theme (auto)">
        <i class="fa-solid fa-circle-half-stroke my-1 theme-icon-active fa-lg"></i>
        <span class="visually-hidden" id="bd-theme-text">Toggle theme</span>
    </button>
    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="bd-theme-text">
        <li><button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="light" aria-pressed="false"><i class="fa-solid fa-sun me-2 opacity-50 theme-icon"></i> Light <i class="fa-solid fa-check ms-auto d-none"></i></button></li>
        <li><button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark" aria-pressed="false"><i class="fa-solid fa-moon me-2 opacity-50 theme-icon"></i> Dark <i class="fa-solid fa-check ms-auto d-none"></i></button></li>
        <li><button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="auto" aria-pressed="true"><i class="fa-solid fa-circle-half-stroke me-2 opacity-50 theme-icon"></i> Auto <i class="fa-solid fa-check ms-auto d-none"></i></button></li>
    </ul>
</div>

<header class="navbar sticky-top bg-dark flex-md-nowrap p-0 shadow" data-bs-theme="dark">
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6 text-white" href="#">ระบบห้องสมุด (LMS)</a>

    <ul class="navbar-nav flex-row d-md-none">
        <li class="nav-item text-nowrap">
            <button class="nav-link px-3 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSearch" aria-controls="navbarSearch" aria-expanded="false" aria-label="Toggle search">
                <svg class="bi" aria-hidden="true">
                    <use xlink:href="#search"></use>
                </svg>
            </button>
        </li>
        <li class="nav-item text-nowrap">
            <button class="nav-link px-3 text-white" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                <svg class="bi" aria-hidden="true">
                    <use xlink:href="#list"></use>
                </svg>
            </button>
        </li>
    </ul>

    <div id="navbarSearch" class="navbar-search w-100 collapse">
        <input class="form-control w-100 rounded-0 border-0" type="text" placeholder="ค้นหา..." aria-label="Search" />
    </div>
</header>