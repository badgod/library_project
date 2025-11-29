<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="index">
            <img src="assets/images/logo.png" alt="โลโก้ระบบ" height="28" class="d-inline-block align-text-top">
            <?= SYSTEMNAME ?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse"
            aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav me-auto mb-2 mb-md-0">
                <?php $active = ($route == 'index') ? 'active' : ''; ?>
                <li class="nav-item"><a class="nav-link <?= $active ?>" href="index">หน้าหลัก</a></li>
                <?php $active = ($route == 'books' || $route == 'book_detail') ? 'active' : ''; ?>
                <li class="nav-item"><a class="nav-link <?= $active ?>" href="books">หนังสือทั้งหมด</a></li>
                <?php $active = ($route == 'how_to_borrow_return') ? 'active' : ''; ?>
                <li class="nav-item"><a class="nav-link <?= $active ?>" href="how_to_borrow_return">วิธียืม-คืนหนังสือ</a></li>
                <?php $active = ($route == 'about') ? 'active' : ''; ?>
                <li class="nav-item"><a class="nav-link <?= $active ?>" href="about">เกี่ยวกับเรา</a></li>
                <?php $active = ($route == 'contact') ? 'active' : ''; ?>
                <li class="nav-item"><a class="nav-link <?= $active ?>" href="contact">ติดต่อเรา</a></li>
            </ul>

            <ul class="navbar-nav ms-auto align-items-center">
                <?php if (isset($_SESSION['member_login'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="cart">
                            <i class="fa-solid fa-basket-shopping"></i>
                            <?php
                            $cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
                            if ($cart_count > 0):
                            ?>
                                <span class="badge bg-danger"><?= $cart_count ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="fa-solid fa-circle-user"></i> <?php echo htmlspecialchars($_SESSION['member_name']); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="history"><i class="fa-solid fa-book"></i> ประวัติการยืม-คืน</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="profile"><i class="fa-solid fa-user"></i> ข้อมูลส่วนตัว</a></li>
                            <li><a class="dropdown-item" href="change_password"><i class="fa-solid fa-key"></i> เปลี่ยนรหัสผ่าน</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item text-danger" href="signout"><i class="fa-solid fa-right-from-bracket"></i> ออกจากระบบ</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item"><a class="btn btn-outline-light btn-sm" href="signin">เข้าสู่ระบบ</a></li>
                <?php endif; ?>
            </ul>

        </div>
    </div>
</nav>