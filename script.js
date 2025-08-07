$(document).ready(function() {
    const tables = ['table_a', 'table_b', 'table_c', 'table_d'];
    
    tables.forEach(function(tableName) {
        const tableElement = document.getElementById('datatable-' + tableName);
        if (tableElement) {
            $('#datatable-' + tableName).DataTable({
                pageLength: 10,
                responsive: true,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                },
                columnDefs: [
                    { orderable: false, targets: -1 }
                ]
            });
        }
    });
    
    $(document).on('click', '.edit-btn', function() {
        const table = $(this).data('table');
        const id = $(this).data('id');
        const values = $(this).data('values');
        editRow(table, id, values);
    });
    
    $(document).on('click', '.delete-btn', function() {
        const table = $(this).data('table');
        const id = $(this).data('id');
        confirmDelete(table, id);
    });
    
    if (window.location.hash) {
        const hash = window.location.hash.substring(1);
        const tabButton = document.getElementById(hash + '-tab');
        if (tabButton) {
            const tab = new bootstrap.Tab(tabButton);
            tab.show();
        }
    }
});

function editRow(table, id, data) {
    const modal = new bootstrap.Modal(document.getElementById('editModal'));
    document.getElementById('editTable').value = table;
    
    let hiddenFieldHtml = `<input type="hidden" name="original_id" value="${id}">`;
    
    const tableConfigs = {
        'table_a': [
            {name: 'kode_toko_baru', label: 'Kode Toko Baru', type: 'text', required: true},
            {name: 'kode_toko_lama', label: 'Kode Toko Lama', type: 'text', required: false}
        ],
        'table_b': [
            {name: 'kode_toko', label: 'Kode Toko', type: 'text', required: true},
            {name: 'nominal_transaksi', label: 'Nominal Transaksi', type: 'number', step: '0.01', required: true}
        ],
        'table_c': [
            {name: 'kode_toko', label: 'Kode Toko', type: 'text', required: true},
            {name: 'area_sales', label: 'Area Sales', type: 'select', options: ['A', 'B'], required: true}
        ],
        'table_d': [
            {name: 'kode_sales', label: 'Kode Sales', type: 'text', required: true},
            {name: 'nama_sales', label: 'Nama Sales', type: 'text', required: true}
        ]
    };
    
    const config = tableConfigs[table];
    const dataArray = data.split('|');
    let fieldsHtml = '';
    
    config.forEach((field, index) => {
        fieldsHtml += `<div class="mb-3">
            <label class="form-label">${field.label}</label>`;
        
        if (field.type === 'select') {
            fieldsHtml += `<select class="form-control" name="${field.name}" ${field.required ? 'required' : ''}>
                <option value="">Pilih ${field.label}</option>`;
            field.options.forEach(option => {
                const selected = dataArray[index] === option ? 'selected' : '';
                fieldsHtml += `<option value="${option}" ${selected}>${option}</option>`;
            });
            fieldsHtml += `</select>`;
        } else {
            const value = dataArray[index] && dataArray[index] !== 'null' ? dataArray[index] : '';
            fieldsHtml += `<input type="${field.type}" ${field.step ? 'step="' + field.step + '"' : ''} 
                class="form-control" name="${field.name}" value="${value}" ${field.required ? 'required' : ''}>`;
        }
        fieldsHtml += `</div>`;
    });
    
    document.getElementById('editFields').innerHTML = hiddenFieldHtml + fieldsHtml;
    modal.show();
}

function confirmDelete(table, id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: 'Data yang dihapus tidak dapat dikembalikan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = `
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="table" value="${table}">
                <input type="hidden" name="id" value="${id}">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function resetForm(table) {
    const form = document.getElementById(`edit-form-${table}`);
    if (form) {
        form.reset();
        const actionInput = form.querySelector('input[name="action"]');
        if (actionInput) {
            actionInput.value = 'create';
        }
    }
}

function showAlert(type, title, text) {
    Swal.fire({
        icon: type,
        title: title,
        text: text,
        timer: 3000,
        showConfirmButton: false
    });
}