<?php
// ตรวจสอบการ Login (กันเหนียวในฝั่ง PHP ด้วย)
if (!isset($_SESSION['member_login'])) {
    header("Location: signin");
    exit;
}
?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

<div class="container py-5">
    <div class="d-flex align-items-center mb-4">
        <h2 class="fw-bold mb-0"><i class="fa-solid fa-clock-rotate-left me-2"></i>ประวัติการยืม-คืนหนังสือ</h2>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-4">
            <table id="historyTable" class="table table-hover align-middle w-100">
                <thead class="table-light">
                    <tr>
                        <th width="10%">รูปปก</th>
                        <th width="35%">ชื่อหนังสือ</th>
                        <th width="15%" class="text-center">วันที่ยืม</th>
                        <th width="15%" class="text-center">กำหนดคืน</th>
                        <th width="15%" class="text-center">วันที่คืนจริง</th>
                        <th width="10%" class="text-center">สถานะ</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>

<script>
    $(document).ready(function() {
        $('#historyTable').DataTable({
            "ajax": {
                "url": "api/history_api.php?action=get_history",
                "type": "GET"
            },
            "order": [
                [2, "desc"]
            ], // เรียงลำดับตามคอลัมน์ที่ 2 (วันที่ยืม) จากมากไปน้อย
            "columns": [{
                    "data": "image",
                    "render": function(data, type, row) {
                        let imgPath = data ? `assets/images/${data}` : 'assets/images/blank_cover_book.jpg';
                        return `<img src="${imgPath}" class="rounded shadow-sm" style="width: 50px; height: 75px; object-fit: cover;">`;
                    },
                    "orderable": false // ห้ามเรียงลำดับคอลัมน์รูป
                },
                {
                    "data": "title",
                    "render": function(data, type, row) {
                        return `<h6 class="fw-bold mb-0 text-dark">${data}</h6>`;
                    }
                },
                {
                    "data": "loan_date",
                    "className": "text-center",
                    "render": function(data) {
                        // แปลงวันที่เป็นรูปแบบ DD/MM/YYYY
                        return data ? moment(data).format('DD/MM/YYYY') : '-';
                    }
                },
                {
                    "data": "due_date",
                    "className": "text-center",
                    "render": function(data) {
                        return data ? moment(data).format('DD/MM/YYYY') : '-';
                    }
                },
                {
                    "data": "item_return_date", // ใช้ฟิลด์นี้ตรวจสอบวันที่คืนจริง
                    "className": "text-center",
                    "render": function(data) {
                        return data ? `<span class="text-success">${moment(data).format('DD/MM/YYYY')}</span>` : '-';
                    }
                },
                {
                    "data": null, // คำนวณสถานะจากหลายฟิลด์
                    "className": "text-center",
                    "render": function(data, type, row) {
                        let badge = '';

                        // Logic การตรวจสอบสถานะ
                        if (row.loan_status === 'receive') {
                            badge = '<span class="badge bg-warning text-dark"><i class="fas fa-box me-1"></i>รอรับของ</span>';
                        } else if (row.item_status === 'return') {
                            badge = '<span class="badge bg-success"><i class="fas fa-check me-1"></i>คืนแล้ว</span>';
                        } else if (row.loan_status === 'borrow' && row.item_status === 'borrow') {
                            // เช็คว่าเกินกำหนดหรือไม่
                            let today = moment().format('YYYY-MM-DD');
                            let dueDate = moment(row.due_date).format('YYYY-MM-DD');

                            if (today > dueDate) {
                                badge = '<span class="badge bg-danger"><i class="fas fa-exclamation-circle me-1"></i>เกินกำหนด</span>';
                            } else {
                                badge = '<span class="badge bg-primary"><i class="fas fa-book-reader me-1"></i>กำลังยืม</span>';
                            }
                        } else {
                            badge = '<span class="badge bg-secondary">อื่นๆ</span>';
                        }
                        return badge;
                    }
                }
            ],
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/th.json"
            },
            "responsive": true // รองรับมือถือ
        });
    });
</script>