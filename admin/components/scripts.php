<?php //admin/components/scripts.php ?>
<script>
    const modal = document.getElementById('productModal');
    const modalTitle = document.getElementById('modalTitle');
    const formMode = document.getElementById('formMode');
    const formId = document.getElementById('formId');
    const formOldThumb = document.getElementById('formOldThumb');
    const inpName = document.getElementById('inpName');
    const inpStatus = document.getElementById('inpStatus');
    const checkboxes = document.querySelectorAll('.cat-checkbox');
    const previewContainer = document.getElementById('previewContainer');
    const imgPreview = document.getElementById('imgPreview');
    const itemsModal = document.getElementById('itemsModal');
const tbody = document.getElementById('itemsTableBody');
const loading = document.getElementById('loadingItems');

    function openModal(mode, data = null) {
        modal.classList.remove('hidden');
        setTimeout(() => { modal.classList.remove('modal-enter'); modal.classList.add('modal-active'); }, 10);
        document.querySelector('form').reset();
        checkboxes.forEach(cb => cb.checked = false);
        previewContainer.classList.add('hidden');

        if (mode === 'add') {
            modalTitle.innerHTML = '<i class="ph-bold ph-plus-circle text-blue-500"></i> Tambah Produk Baru';
            formMode.value = 'add';
        } else {
            modalTitle.innerHTML = '<i class="ph-bold ph-pencil-simple text-blue-500"></i> Edit Produk';
            formMode.value = 'edit';
            formId.value = data.id;
            inpName.value = data.name;
            inpStatus.value = data.status;
            formOldThumb.value = data.thumbnail;
            if(data.thumbnail) {
                previewContainer.classList.remove('hidden');
                imgPreview.src = "../assets/uploads/games/" + data.thumbnail;
            }
            if(data.category) {
                const cats = data.category.split(',');
                checkboxes.forEach(cb => { if(cats.includes(cb.value)) cb.checked = true; });
            }
        }
    }

    function closeModal() {
        modal.classList.remove('modal-active');
        modal.classList.add('modal-enter');
        setTimeout(() => { modal.classList.add('hidden'); }, 300);
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus Produk?', text: "Data tidak bisa dikembalikan!", icon: 'warning',
            showCancelButton: true, background: '#1e293b', color: '#fff',
            confirmButtonColor: '#ef4444', cancelButtonColor: '#334155',
            confirmButtonText: 'Hapus', cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) window.location.href = 'game_delete.php?id=' + id;
        })
    }

    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('msg')) {
        Swal.fire({ icon: 'success', title: 'Berhasil!', text: urlParams.get('msg'), background: '#1e293b', color: '#fff', confirmButtonColor: '#2563eb' });
        window.history.replaceState({}, document.title, window.location.pathname);
    }

    function openItemsModal(gameId, gameName) {
    itemsModal.classList.remove('hidden');
    setTimeout(() => { itemsModal.classList.remove('modal-enter'); itemsModal.classList.add('modal-active'); }, 10);
    
    document.getElementById('manageGameName').innerText = gameName;
    document.getElementById('inputGameId').value = gameId;
    document.getElementById('importGameId').value = gameId;
    
    // Reset ke tab List
    switchItemTab('list');
    
    // Load Data via AJAX
    loadItems(gameId);
}

function closeItemsModal() {
    itemsModal.classList.remove('modal-active');
    itemsModal.classList.add('modal-enter');
    setTimeout(() => { itemsModal.classList.add('hidden'); }, 300);
}

function switchItemTab(tab) {
    // Hide all
    document.getElementById('tab-content-list').classList.add('hidden');
    document.getElementById('tab-content-add').classList.add('hidden');
    document.getElementById('tab-content-import').classList.add('hidden');
    
    // Reset Buttons
    ['list', 'add', 'import'].forEach(t => {
        const btn = document.getElementById('tab-btn-'+t);
        btn.classList.remove('bg-blue-600', 'text-white', 'shadow-lg');
        btn.classList.add('text-slate-400', 'hover:bg-slate-800', 'hover:text-white');
    });

    // Show Active
    document.getElementById('tab-content-'+tab).classList.remove('hidden');
    const activeBtn = document.getElementById('tab-btn-'+tab);
    activeBtn.classList.add('bg-blue-600', 'text-white', 'shadow-lg');
    activeBtn.classList.remove('text-slate-400', 'hover:bg-slate-800');

    if(tab === 'add') {
        // Reset form add
        document.getElementById('formTitle').innerText = "Tambah Item Baru";
        document.getElementById('inputProductId').value = "";
        document.getElementById('inputItemName').value = "";
        document.getElementById('inputItemPrice').value = "";
        document.getElementById('inputPromoPrice').value = "";
        document.getElementById('inputPromoDate').value = "";
    }
}

// Fetch Data AJAX
function loadItems(gameId) {
    tbody.innerHTML = "";
    loading.classList.remove('hidden');
    
    fetch(`api_products.php?game_id=${gameId}`)
        .then(response => response.json())
        .then(data => {
            loading.classList.add('hidden');
            if(data.length === 0) {
                tbody.innerHTML = `<tr><td colspan="5" class="p-8 text-center text-slate-500">Belum ada item. Silakan tambah atau import.</td></tr>`;
                return;
            }
            
            let html = "";
            data.forEach(item => {
                let promoLabel = "-";
                let priceDisplay = `Rp ${new Intl.NumberFormat('id-ID').format(item.price)}`;
                
                if(item.promo_price > 0) {
                    promoLabel = `<span class="text-xs text-green-400 font-bold">Rp ${new Intl.NumberFormat('id-ID').format(item.promo_price)}</span>`;
                    priceDisplay = `<span class="line-through text-slate-500 text-xs mr-1">${priceDisplay}</span>`;
                }

                html += `
                <tr class="hover:bg-slate-800/50 group">
                    <td class="p-4 font-bold text-white">${item.name}</td>
                    <td class="p-4"><span class="px-2 py-1 rounded bg-slate-800 text-xs text-slate-300">${item.category}</span></td>
                    <td class="p-4 font-mono">${priceDisplay}</td>
                    <td class="p-4">${promoLabel}</td>
                    <td class="p-4 text-right">
                        <button onclick='editItem(${JSON.stringify(item)})' class="text-blue-400 hover:text-white bg-blue-500/10 hover:bg-blue-600 p-2 rounded transition"><i class="ph-bold ph-pencil-simple"></i></button>
                        <button onclick="deleteItem(${item.id})" class="text-red-400 hover:text-white bg-red-500/10 hover:bg-red-600 p-2 rounded transition"><i class="ph-bold ph-trash"></i></button>
                    </td>
                </tr>`;
            });
            tbody.innerHTML = html;
        });
}

function editItem(item) {
    switchItemTab('add');
    document.getElementById('formTitle').innerText = "Edit Item: " + item.name;
    document.getElementById('inputProductId').value = item.id;
    document.getElementById('inputItemName').value = item.name;
    document.getElementById('inputItemPrice').value = item.price;
    document.getElementById('inputItemCat').value = item.category;
    document.getElementById('inputPromoPrice').value = item.promo_price;
    document.getElementById('inputPromoDate').value = item.promo_end_date ? item.promo_end_date.replace(' ', 'T') : '';
}

function deleteItem(id) {
    if(confirm("Hapus item ini?")) {
        document.getElementById('delItemId').value = id;
        document.getElementById('deleteItemForm').submit();
    }
}
</script>