 // Galerie  
function openLightbox(imageSrc) { 
    document.getElementById('lightboxImage').src = imageSrc; 
    document.getElementById('lightboxOverlay').style.display = 'flex'; 
}
function closeLightbox() { 
    document.getElementById('lightboxOverlay').style.display = 'none'; 
}

// Modale dashboard si favorites
function openMoveModal(id) { 
    document.getElementById('modal_image_id').value = id; 
    document.getElementById('moveModal').style.display = 'flex'; 
}
function closeMoveModal() { 
    document.getElementById('moveModal').style.display = 'none'; 
}

function openRenameModal(id, title) { 
    document.getElementById('rename_image_id').value = id; 
    document.getElementById('rename_new_title').value = title; 
    document.getElementById('renameModal').style.display = 'flex'; 
}
function closeRenameModal() { 
    document.getElementById('renameModal').style.display = 'none'; 
}

function openDeleteFileModal(id) { 
    document.getElementById('delete_file_id').value = id; 
    document.getElementById('deleteFileModal').style.display = 'flex'; 
}
function closeDeleteFileModal() { 
    document.getElementById('deleteFileModal').style.display = 'none'; 
}

function openDeleteFolderModal(id) { 
    document.getElementById('delete_folder_id').value = id; 
    document.getElementById('deleteFolderModal').style.display = 'flex'; 
}
function closeDeleteFolderModal() { 
    document.getElementById('deleteFolderModal').style.display = 'none'; 
}

function openDeleteFromFavoritesModal(id) { 
    document.getElementById('favorite_delete_id').value = id; 
    document.getElementById('deleteFavoriteModal').style.display = 'flex'; 
}
function closeDeleteFromFavoritesModal() { 
    document.getElementById('deleteFavoriteModal').style.display = 'none'; 
}

// Modale trash
function openPermanentDeleteModal(id) { 
    document.getElementById('perm_delete_id').value = id; 
    document.getElementById('permDeleteModal').style.display = 'flex'; 
}
function closePermanentDeleteModal() { 
    document.getElementById('permDeleteModal').style.display = 'none'; 
}

function openEmptyTrashModal() { 
    document.getElementById('emptyTrashModal').style.display = 'flex'; 
}
function closeEmptyTrashModal() { 
    document.getElementById('emptyTrashModal').style.display = 'none'; 
}

// Modale admin
function openDeleteUserModal(id) { 
    document.getElementById('delete_user_id').value = id; 
    document.getElementById('deleteUserModal').style.display = 'flex'; 
}
function closeDeleteUserModal() { 
    document.getElementById('deleteUserModal').style.display = 'none'; 
}

// Cautare ajax
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const galleryContainer = document.getElementById('galleryContainer');

    if (searchInput && galleryContainer) {
        searchInput.addEventListener('input', function() {
            const textCautat = this.value;
            let ajaxUrl = 'ajax_search.php?q=' + encodeURIComponent(textCautat);
            
            fetch(ajaxUrl)
                .then(response => response.text())
                .then(html => { 
                    galleryContainer.innerHTML = html; 
                })
                .catch(err => console.log('Eroare la cererea AJAX:', err));
        });
    }
});

// cookie pentru tema
function toggleTheme() {
    document.body.classList.toggle('dark-mode');
    
    const isDark = document.body.classList.contains('dark-mode');
    const themeValue = isDark ? 'dark' : 'light';
    
    if (typeof themeCookieName !== 'undefined') {
        document.cookie = themeCookieName + "=" + themeValue + "; path=/; max-age=" + 60*60*24*365;
    } else {
        console.error("Numele cookie-ului nu a fost definit în PHP!");
    }
}