<?php

function handleCreate($pdo, $table, $data) {
    try {
        switch($table) {
            case 'table_a':
                $sql = "INSERT INTO table_a (kode_toko_baru, kode_toko_lama) VALUES (?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$data['kode_toko_baru'], $data['kode_toko_lama'] ?: null]);
                break;
            case 'table_b':
                $sql = "INSERT INTO table_b (kode_toko, nominal_transaksi) VALUES (?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$data['kode_toko'], $data['nominal_transaksi']]);
                break;
            case 'table_c':
                $sql = "INSERT INTO table_c (kode_toko, area_sales) VALUES (?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$data['kode_toko'], $data['area_sales']]);
                break;
            case 'table_d':
                $sql = "INSERT INTO table_d (kode_sales, nama_sales) VALUES (?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$data['kode_sales'], $data['nama_sales']]);
                break;
        }
        setSuccessMessage("Data berhasil ditambahkan ke $table!");
        redirectToTab($table);
    } catch(Exception $e) {
        setErrorMessage("Error: " . $e->getMessage());
        redirectToTab($table);
    }
}

function handleUpdate($pdo, $table, $data) {
    try {
        switch($table) {
            case 'table_a':
                $sql = "UPDATE table_a SET kode_toko_baru = ?, kode_toko_lama = ? WHERE kode_toko_baru = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$data['kode_toko_baru'], $data['kode_toko_lama'] ?: null, $data['original_id']]);
                break;
            case 'table_b':
                $sql = "UPDATE table_b SET nominal_transaksi = ? WHERE kode_toko = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$data['nominal_transaksi'], $data['kode_toko']]);
                break;
            case 'table_c':
                $sql = "UPDATE table_c SET area_sales = ? WHERE kode_toko = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$data['area_sales'], $data['kode_toko']]);
                break;
            case 'table_d':
                $sql = "UPDATE table_d SET nama_sales = ? WHERE kode_sales = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$data['nama_sales'], $data['kode_sales']]);
                break;
        }
        setSuccessMessage("Data berhasil diupdate di $table!");
        redirectToTab($table);
    } catch(Exception $e) {
        setErrorMessage("Error: " . $e->getMessage());
        redirectToTab($table);
    }
}

function handleDelete($pdo, $table, $data) {
    try {
        switch($table) {
            case 'table_a':
                $sql = "DELETE FROM table_a WHERE kode_toko_baru = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$data['id']]);
                break;
            case 'table_b':
                $sql = "DELETE FROM table_b WHERE kode_toko = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$data['id']]);
                break;
            case 'table_c':
                $sql = "DELETE FROM table_c WHERE kode_toko = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$data['id']]);
                break;
            case 'table_d':
                $sql = "DELETE FROM table_d WHERE kode_sales = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$data['id']]);
                break;
        }
        setSuccessMessage("Data berhasil dihapus dari $table!");
        redirectToTab($table);
    } catch(Exception $e) {
        setErrorMessage("Error: " . $e->getMessage());
        redirectToTab($table);
    }
}

function getData($pdo, $table) {
    $sql = "SELECT * FROM $table";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();
}

function downloadExcel($pdo, $table) {
    $data = getData($pdo, $table);
    
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment;filename="' . $table . '_data.csv"');
    header('Cache-Control: max-age=0');
    
    $output = fopen('php://output', 'w');
    
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
    
    if (!empty($data)) {
        fputcsv($output, array_keys($data[0]));
        
        foreach ($data as $row) {
            fputcsv($output, $row);
        }
    }
    
    fclose($output);
    exit;
}

function setSuccessMessage($message) {
    $_SESSION['alert_message'] = $message;
    $_SESSION['alert_type'] = 'success';
}

function setErrorMessage($message) {
    $_SESSION['alert_message'] = $message;
    $_SESSION['alert_type'] = 'error';
}

function redirectToTab($table) {
    $tabMap = [
        'table_a' => 'table-a',
        'table_b' => 'table-b', 
        'table_c' => 'table-c',
        'table_d' => 'table-d'
    ];
    $tab = $tabMap[$table] ?? 'table-a';
    header("Location: index.php#$tab");
    exit;
}

function downloadPDF($pdo, $table) {
    $data = getData($pdo, $table);
    generateHTML2PDF($data, $table);
}

function generateHTML2PDF($data, $table) {
    $html = generatePDFHTML($data, $table);
    
    header('Content-Type: text/html; charset=utf-8');
    echo $html;
    exit;
}

function generatePDFHTML($data, $table) {
    $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Data ' . ucfirst($table) . '</title>
    <style>
        @page { 
            size: A4; 
            margin: 1cm; 
        }
        body { 
            font-family: Arial, sans-serif; 
            font-size: 11px;
            line-height: 1.2;
            margin: 0;
            padding: 0;
        }
        .header { 
            text-align: center; 
            margin-bottom: 20px;
            border-bottom: 2px solid #1e3c72;
            padding-bottom: 10px;
        }
        .title { 
            font-size: 18px; 
            font-weight: bold; 
            color: #1e3c72; 
            margin-bottom: 5px;
        }
        .subtitle {
            font-size: 12px;
            color: #666;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin: 10px 0;
            font-size: 10px;
        }
        th, td { 
            border: 1px solid #333; 
            padding: 6px 8px; 
            text-align: left; 
            vertical-align: top;
        }
        th { 
            background-color: #1e3c72; 
            color: white; 
            font-weight: bold;
            text-align: center;
        }
        tr:nth-child(even) { 
            background-color: #f9f9f9; 
        }
        .footer { 
            text-align: center; 
            margin-top: 20px; 
            font-size: 9px; 
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
        .no-data {
            text-align: center;
            font-style: italic;
            color: #999;
        }
    </style>
    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 100);
        }
    </script>
</head>
<body>
    <div class="header">
        <div class="title">Data dari ' . ucwords(str_replace('_', ' ', $table)) . '</div>
        <div class="subtitle">Technical Test Avian</div>
        <div class="subtitle">Generated on ' . date('d/m/Y H:i:s') . '</div>
    </div>
    
    <table>';
    
    if (!empty($data)) {
        $html .= '<tr>';
        foreach (array_keys($data[0]) as $header) {
            $html .= '<th>' . htmlspecialchars(ucwords(str_replace('_', ' ', $header))) . '</th>';
        }
        $html .= '</tr>';
        
        foreach ($data as $row) {
            $html .= '<tr>';
            foreach ($row as $cell) {
                $html .= '<td>' . htmlspecialchars($cell ?? '-') . '</td>';
            }
            $html .= '</tr>';
        }
    } else {
        $html .= '<tr><td colspan="100%" class="no-data">Tidak ada data tersedia</td></tr>';
    }
    
    $html .= '</table>
    
    <div class="footer">
        <p>© ' . date('Y') . ' Technical Test Avian</p>
        <p>Total Records: ' . count($data) . '</p>
    </div>
</body>
</html>';
    
    return $html;
}

function handleExcelUpload($file, $table, $pdo) {
    $handle = fopen($file['tmp_name'], 'r');
    if ($handle !== FALSE) {
        $header = fgetcsv($handle);
        
        while (($data = fgetcsv($handle)) !== FALSE) {
            try {
                switch($table) {
                    case 'table_a':
                        if (count($data) >= 2) {
                            $sql = "INSERT INTO table_a (kode_toko_baru, kode_toko_lama) VALUES (?, ?)";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([$data[0], $data[1] ?: null]);
                        }
                        break;
                    case 'table_b':
                        if (count($data) >= 2) {
                            $sql = "INSERT INTO table_b (kode_toko, nominal_transaksi) VALUES (?, ?)";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([$data[0], $data[1]]);
                        }
                        break;
                    case 'table_c':
                        if (count($data) >= 2) {
                            $sql = "INSERT INTO table_c (kode_toko, area_sales) VALUES (?, ?)";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([$data[0], $data[1]]);
                        }
                        break;
                    case 'table_d':
                        if (count($data) >= 2) {
                            $sql = "INSERT INTO table_d (kode_sales, nama_sales) VALUES (?, ?)";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([$data[0], $data[1]]);
                        }
                        break;
                }
            } catch(Exception $e) {
                continue;
            }
        }
        fclose($handle);
        setSuccessMessage("Data berhasil diupload ke $table!");
        redirectToTab($table);
    } else {
        setErrorMessage("Gagal membaca file Excel!");
        redirectToTab($table);
    }
}

function renderTableManager($pdo, $tableName, $tableTitle, $columns) {
    $data = getData($pdo, $tableName);
    $primaryKey = $columns[0];
    ?>
    
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header card-header-custom">
                    <h5 class="mb-0"><i class="fas fa-plus me-2"></i>Tambah/Edit Data</h5>
                </div>
                <div class="card-body">
                    <form method="POST" id="edit-form-<?php echo $tableName; ?>">
                        <input type="hidden" name="action" value="create">
                        <input type="hidden" name="table" value="<?php echo $tableName; ?>">
                        
                        <?php foreach ($columns as $column): ?>
                            <div class="mb-3">
                                <label class="form-label"><?php echo ucwords(str_replace('_', ' ', $column)); ?></label>
                                <?php if ($tableName === 'table_c' && $column === 'area_sales'): ?>
                                    <select class="form-control" name="<?php echo $column; ?>" <?php echo $column === $primaryKey ? 'required' : ''; ?>>
                                        <option value="">Pilih Area</option>
                                        <option value="A">Area A</option>
                                        <option value="B">Area B</option>
                                    </select>
                                <?php elseif ($tableName === 'table_b' && $column === 'nominal_transaksi'): ?>
                                    <input type="number" step="0.01" class="form-control" name="<?php echo $column; ?>" <?php echo $column === $primaryKey ? 'required' : ''; ?>>
                                <?php else: ?>
                                    <input type="text" class="form-control" name="<?php echo $column; ?>" <?php echo $column === $primaryKey ? 'required' : ''; ?>>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                        
                        <button type="submit" class="btn btn-primary-custom btn-custom w-100 mb-2">
                            <i class="fas fa-save me-2"></i>Simpan Data
                        </button>
                        <button type="button" onclick="resetForm('<?php echo $tableName; ?>')" class="btn btn-secondary btn-custom w-100">
                            <i class="fas fa-times me-2"></i>Reset
                        </button>
                    </form>
                    
                    <hr>
                    
                    <h6 class="mb-3"><i class="fas fa-file-excel me-2"></i>Upload Excel/CSV</h6>
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="upload_table" value="<?php echo $tableName; ?>">
                        <div class="mb-3">
                            <input type="file" class="form-control" name="excel_file" accept=".csv,.xls,.xlsx" required>
                            <div class="form-text">Format: CSV/Excel dengan kolom sesuai urutan</div>
                        </div>
                        <button type="submit" class="btn btn-success-custom btn-custom w-100">
                            <i class="fas fa-upload me-2"></i>Upload File
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="table-container">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0"><?php echo $tableTitle; ?></h5>
                    <div>
                        <a href="?action=download_excel&table=<?php echo $tableName; ?>" class="btn btn-success-custom btn-custom">
                            <i class="fas fa-file-excel me-2"></i>Excel
                        </a>
                        <a href="?action=download_pdf&table=<?php echo $tableName; ?>" class="btn btn-danger-custom btn-custom">
                            <i class="fas fa-file-pdf me-2"></i>PDF
                        </a>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table id="datatable-<?php echo $tableName; ?>" class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <?php foreach ($columns as $column): ?>
                                    <th><?php echo ucwords(str_replace('_', ' ', $column)); ?></th>
                                <?php endforeach; ?>
                                <th width="150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($data)): ?>
                                <?php foreach ($data as $row): ?>
                                    <tr>
                                        <?php foreach ($columns as $column): ?>
                                            <td><?php echo $row[$column] ?? '-'; ?></td>
                                        <?php endforeach; ?>
                                        <td>
                                            <?php 
                                            $editData = implode('|', array_map(function($col) use ($row) { 
                                                return $row[$col] ?? ''; 
                                            }, $columns)); 
                                            ?>
                                            <button type="button" class="btn btn-warning-custom btn-sm me-1 edit-btn" 
                                                    data-table="<?php echo $tableName; ?>" 
                                                    data-id="<?php echo $row[$primaryKey]; ?>" 
                                                    data-values="<?php echo htmlspecialchars($editData); ?>">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger-custom btn-sm delete-btn" 
                                                    data-table="<?php echo $tableName; ?>" 
                                                    data-id="<?php echo $row[$primaryKey]; ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-3">
                    <span class="creative-badge">Total Data: <?php echo count($data); ?></span>
                </div>
            </div>
        </div>
    </div>
    
    <?php
}
?>