<style>
    /* ลบ background gradient ออก */

    .custom-btn {
        transition: all 0.3s ease-in-out;
    }

    .custom-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    @media (prefers-color-scheme: dark) {

        /* จัดการเฉพาะสีตัวอักษรเมื่อเป็น Dark mode */
        .page-container {
            color: gray !important;
        }

        /* ปรับสีปุ่มใน Dark mode */
        .custom-btn {
            background-color: #374151 !important;
            border-color: #374151 !important;
            color: white !important;
        }

        .custom-btn:hover {
            background-color: #4b5563 !important;
        }
    }
</style>

<div class="page-container text-dark">
    <div class="d-flex align-items-center justify-content-center min-vh-100 px-2">
        <div class="text-center">
            <h1 class="display-1 fw-bold">404</h1>
            <p class="fs-2 fw-medium mt-4">Oops! Page not found</p>
            <p class="mt-4 mb-5">The page you're looking for doesn't exist or has been moved.</p>

            <a href="index.php" class="btn btn-dark fw-semibold rounded-pill px-4 py-2 custom-btn">
                Go Dashboard
            </a>
        </div>
    </div>
</div>