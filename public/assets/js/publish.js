

document.addEventListener('DOMContentLoaded', function () {

    
    const imageInput  = document.getElementById('imageInput');   
    const uploadZone  = document.getElementById('uploadZone');   
    const uploadPrev  = document.getElementById('uploadPreview');
    const previewImg  = document.getElementById('previewImg');   
    const removeBtn   = document.getElementById('removeImg');    
    const resetBtn    = document.getElementById('resetBtn');     


    
    function showPreview(file) {
        if (!file) return;

        
        const reader = new FileReader();

       
        reader.onload = function (e) {
            previewImg.src = e.target.result; 
            uploadPrev.style.display = 'block';  
            uploadZone.style.display = 'none';   
        };

        
        reader.readAsDataURL(file);
    }


  
    function resetUpload() {
        if (imageInput)  imageInput.value = ''; 
        if (previewImg)  previewImg.src   = ''; 
        if (uploadPrev)  uploadPrev.style.display = 'none';  
        if (uploadZone)  uploadZone.style.display = 'block'; 
    }


    
    if (imageInput) {
        imageInput.addEventListener('change', function () {
            const file = this.files[0]; 
            if (file) showPreview(file);
        });
    }


    
    if (removeBtn) {
        removeBtn.addEventListener('click', resetUpload);
    }



    if (uploadZone) {

       
        uploadZone.addEventListener('dragover', function (e) {
            e.preventDefault();
            uploadZone.classList.add('drag-over'); 
        });

        uploadZone.addEventListener('dragleave', function () {
            uploadZone.classList.remove('drag-over');
        });

       
        uploadZone.addEventListener('drop', function (e) {
            e.preventDefault();                          
            uploadZone.classList.remove('drag-over');    

            const dt    = e.dataTransfer;                
            const files = dt.files;                      

            if (files && files.length > 0) {
                
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(files[0]);        
                imageInput.files = dataTransfer.files;   
                showPreview(files[0]);                   
            }
        });
    }


    
    if (resetBtn) {
        resetBtn.addEventListener('click', function () {
            resetUpload();
        });
    }

});