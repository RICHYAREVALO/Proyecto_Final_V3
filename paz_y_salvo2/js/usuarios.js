document.addEventListener('DOMContentLoaded', function() {
    const editarButtons = document.querySelectorAll('.editar-btn');
    const modalEditarUsuario = document.getElementById('modalEditarUsuario');
    const closeBtn = modalEditarUsuario.querySelector('.close');

    editarButtons.forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');

            // Abre la ventana modal al hacer clic en el botón de editar
            modalEditarUsuario.style.display = 'block';

            // Realiza una petición AJAX para obtener el formulario de edición
            fetch(`editar_usuario.php?id=${id}`)
                .then(response => response.text())
                .then(data => {
                    // Inserta el contenido del formulario en el cuerpo de la ventana modal
                    modalEditarUsuario.querySelector('.modal-content').innerHTML = data;
                })
                .catch(error => console.error('Error:', error));
        });
    });

    // Cierra la ventana modal al hacer clic en el botón de cerrar
    closeBtn.addEventListener('click', function() {
        modalEditarUsuario.style.display = 'none';
    });
});
