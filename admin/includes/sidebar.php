<div
    class="sidebar border border-right col-md-3 col-lg-2 p-0 bg-body-tertiary">
    <div
        class="offcanvas-md offcanvas-end bg-body-tertiary"
        tabindex="-1"
        id="sidebarMenu"
        aria-labelledby="sidebarMenuLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="sidebarMenuLabel">
                <?= SYSTEMNAME_ADMIN ?>
            </h5>
            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="offcanvas"
                data-bs-target="#sidebarMenu"
                aria-label="Close"></button>
        </div>
        <div class="offcanvas-body d-md-flex flex-column p-0 pt-lg-3 overflow-y-auto">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <?php $active = ($route == 'index') ? 'active' : ''; ?>
                    <a class="nav-link d-flex align-items-center gap-2 <?= $active ?>" aria-current="page" href="index">
                        <i class="fa-solid fa-gauge fa-fw"></i>
                        Dashboard
                    </a>
                </li>
            </ul>
            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-body-secondary text-uppercase">
                <span>จัดการผู้ใช้งาน</span>
                <a class="link-secondary" href="#" aria-label="Add a new report">
                    <i class="fa-solid fa-circle-plus"></i>
                </a>
            </h6>
            <ul class="nav flex-column mb-auto">
                <li class="nav-item">
                    <?php $active = ($route == 'user') ? 'active' : ''; ?>
                    <a class="nav-link d-flex align-items-center gap-2 <?= $active ?>" href="user">
                        <i class="fa-solid fa-users-gear fa-fw"></i>
                        ผู้ใช้งานและสมาชิก
                    </a>
                </li>
            </ul>
            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-body-secondary text-uppercase">
                <span>จัดการข้อมูลหนังสือ</span>
                <a class="link-secondary" href="#" aria-label="Add a new report">
                    <i class="fa-solid fa-circle-plus"></i>
                </a>
            </h6>
            <ul class="nav flex-column mb-auto">
                <li class="nav-item">
                    <?php $active = ($route == 'category') ? 'active' : ''; ?>
                    <a class="nav-link d-flex align-items-center gap-2 <?= $active ?>" href="category">
                        <i class="fa-solid fa-tags fa-fw"></i>
                        หมวดหมู่หนังสือ
                    </a>
                </li>
                <?php $active = ($route == 'book' || $route == 'book_form' || $route == 'read_book') ? 'active' : ''; ?>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center gap-2 <?= $active ?>" href="book">
                        <i class="fa-solid fa-book fa-fw"></i>
                        หนังสือ
                    </a>
                </li>
            </ul>
            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-body-secondary text-uppercase">
                <span>ยืม-คืน หนังสือ</span>
                <a class="link-secondary" href="#" aria-label="Add a new report">
                    <i class="fa-solid fa-circle-plus"></i>
                </a>
            </h6>
            <ul class="nav flex-column mb-auto">
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center gap-2" href="users">
                        <i class="fa-solid fa-clone fa-fw"></i>
                        ยืมหนังสือ
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center gap-2" href="users">
                        <i class="fa-solid fa-calendar-days fa-fw"></i>
                        คืนหนังสือ
                    </a>
                </li>
            </ul>
            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-body-secondary text-uppercase">
                <span>รายงาน</span>
                <a class="link-secondary" href="#" aria-label="Add a new report">
                    <i class="fa-solid fa-circle-plus"></i>
                </a>
            </h6>
            <ul class="nav flex-column mb-auto">
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center gap-2" href="#">
                        <i class="fa-regular fa-file-lines fa-fw"></i>
                        Current month
                    </a>
                </li>
            </ul>

            <hr class="my-3" />

            <ul class="nav flex-column mb-auto">
                <li class="nav-item">
                    <?php $active = ($route == 'settings') ? 'active' : ''; ?>
                    <a class="nav-link d-flex align-items-center gap-2 <?= $active ?>" href="settings">
                        <i class="fa-solid fa-gear fa-fw"></i>
                        Settings
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>