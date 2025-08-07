<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

if ($_POST) {
    $action = $_POST['action'] ?? '';
    $table = $_POST['table'] ?? '';
    
    if ($action === 'create' && $table) {
        handleCreate($pdo, $table, $_POST);
    } elseif ($action === 'update' && $table) {
        handleUpdate($pdo, $table, $_POST);
    } elseif ($action === 'delete' && $table) {
        handleDelete($pdo, $table, $_POST);
    }
}

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $table = $_GET['table'] ?? '';
    
    if ($action === 'download_excel' && $table) {
        downloadExcel($pdo, $table);
    } elseif ($action === 'download_pdf' && $table) {
        downloadPDF($pdo, $table);
    }
}

if (isset($_FILES['excel_file']) && $_FILES['excel_file']['error'] == 0) {
    handleExcelUpload($_FILES['excel_file'], $_POST['upload_table'], $pdo);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Technical Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="header-banner text-center">
        <div class="container">
            <h1 class="display-4 fw-bold mb-3">
                <i class="fas fa-database me-3"></i>
                Technical Test Avian
            </h1>
            <h2 class="h4 mb-0">
                <i class="fas fa-user-graduate me-2"></i>
                Database Management System
            </h2>
            <p class="lead mt-2">Emily Joyceline Gunawan</p>
        </div>
    </div>

    <div class="container">
        <div class="main-container">
            
            <!-- Navigation Tabs -->
            <ul class="nav nav-tabs mb-4" id="mainTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="table-a-tab" data-bs-toggle="tab" data-bs-target="#table-a" type="button" role="tab">
                        <i class="fas fa-table me-2"></i>Table A - Kode Toko
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="table-b-tab" data-bs-toggle="tab" data-bs-target="#table-b" type="button" role="tab">
                        <i class="fas fa-money-bill me-2"></i>Table B - Transaksi
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="table-c-tab" data-bs-toggle="tab" data-bs-target="#table-c" type="button" role="tab">
                        <i class="fas fa-map-marker-alt me-2"></i>Table C - Area Sales
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="table-d-tab" data-bs-toggle="tab" data-bs-target="#table-d" type="button" role="tab">
                        <i class="fas fa-user-tie me-2"></i>Table D - Sales
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="mainTabsContent">
                
                <div class="tab-pane fade show active" id="table-a" role="tabpanel">
                    <?php renderTableManager($pdo, 'table_a', 'Table A - Kode Toko', ['kode_toko_baru', 'kode_toko_lama']); ?>
                </div>

                <div class="tab-pane fade" id="table-b" role="tabpanel">
                    <?php renderTableManager($pdo, 'table_b', 'Table B - Transaksi', ['kode_toko', 'nominal_transaksi']); ?>
                </div>

                <div class="tab-pane fade" id="table-c" role="tabpanel">
                    <?php renderTableManager($pdo, 'table_c', 'Table C - Area Sales', ['kode_toko', 'area_sales']); ?>
                </div>

                <div class="tab-pane fade" id="table-d" role="tabpanel">
                    <?php renderTableManager($pdo, 'table_d', 'Table D - Sales', ['kode_sales', 'nama_sales']); ?>
                </div>

            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="editForm">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="table" id="editTable">
                        <div id="editFields"></div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary-custom btn-custom">
                                <i class="fas fa-save me-2"></i>Update Data
                            </button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-2"></i>Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="script.js"></script>
    
    <script>
        <?php if (isset($_SESSION['alert_message'])): ?>
            $(document).ready(function() {
                showAlert('<?php echo $_SESSION['alert_type']; ?>', 
                         '<?php echo $_SESSION['alert_type'] === 'success' ? 'Berhasil!' : 'Error!'; ?>', 
                         '<?php echo $_SESSION['alert_message']; ?>');
            });
            <?php unset($_SESSION['alert_message'], $_SESSION['alert_type']); ?>
        <?php endif; ?>
    </script>
</body>
</html>

