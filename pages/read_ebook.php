<div class="container-fluid p-0 bg-dark" style="height: calc(100vh - 56px);">
    <?php
    // รับ ID ของ E-book
    $id = $_GET['id'] ?? 0;
    if ($id > 0):
    ?>
        <iframe src="api/stream_ebook.php?id=<?= $id ?>#toolbar=0&navpanes=0&scrollbar=0"
            width="100%"
            height="100%"
            style="border: none;"
            oncontextmenu="return false;">
        </iframe>
    <?php else: ?>
        <div class="d-flex justify-content-center align-items-center h-100 text-white">
            <h3>ไม่พบเอกสารที่ระบุ</h3>
        </div>
    <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // ป้องกันคลิกขวา (Context Menu)
        $(document).on("contextmenu", function(e) {
            e.preventDefault();
        });

        // ป้องกัน Ctrl+S และ Ctrl+P
        $(document).on("keydown", function(e) {
            if ((e.ctrlKey || e.metaKey) && (e.key === 's' || e.key === 'p')) {
                e.preventDefault();
                alert('ไม่อนุญาตให้ดาวน์โหลดหรือพิมพ์เอกสารนี้');
            }
        });
    });
</script>