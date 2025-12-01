<?php //admin/components/scripts-content.php ?>
<script>
    function toggleUploadType(type) {
        const areaFile = document.getElementById('area-file');
        const areaUrl = document.getElementById('area-url');
        if(type === 'file') { areaFile.classList.remove('hidden'); areaUrl.classList.add('hidden'); } 
        else { areaFile.classList.add('hidden'); areaUrl.classList.remove('hidden'); }
    }

    const dropZone = document.getElementById('drop-zone');
    const fileInput = document.getElementById('fileInput');
    const dropContent = document.getElementById('drop-content');
    const filePreview = document.getElementById('file-preview');

    if(fileInput) {
        fileInput.addEventListener('change', function() { handleFiles(this.files); });

        ['dragenter', 'dragover'].forEach(evt => {
            dropZone.addEventListener(evt, (e) => { e.preventDefault(); dropZone.classList.add('border-indigo-500', 'bg-slate-800'); });
        });
        ['dragleave', 'drop'].forEach(evt => {
            dropZone.addEventListener(evt, (e) => { e.preventDefault(); dropZone.classList.remove('border-indigo-500', 'bg-slate-800'); });
        });
        dropZone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            fileInput.files = dt.files;
            handleFiles(dt.files);
        });
    }

    function handleFiles(files) {
        if(files.length > 0) {
            dropContent.classList.add('hidden');
            filePreview.classList.remove('hidden');
            filePreview.classList.add('flex');
            filePreview.innerHTML = ''; 
            const maxPreview = Math.min(files.length, 4);
            for (let i = 0; i < maxPreview; i++) {
                const file = files[i];
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'relative w-20 h-12 rounded overflow-hidden border border-slate-600 bg-slate-900';
                        div.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                        filePreview.appendChild(div);
                    }
                    reader.readAsDataURL(file);
                }
            }
            if(files.length > 4) {
                const moreDiv = document.createElement('div');
                moreDiv.className = 'w-20 h-12 rounded bg-slate-800 flex items-center justify-center text-xs font-bold text-slate-400 border border-slate-700';
                moreDiv.innerText = `+${files.length - 4}`;
                filePreview.appendChild(moreDiv);
            }
        }
    }

    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('msg')) {
        Swal.fire({ icon: 'success', title: 'Berhasil!', text: urlParams.get('msg'), background: '#1e293b', color: '#fff', confirmButtonColor: '#2563eb' });
        window.history.replaceState({}, document.title, window.location.pathname);
    }
</script>