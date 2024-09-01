$(document).ready(function () {
    function listProducts() {
        $.ajax({
            url: '../../controllers/list.php',
            type: 'GET',
            data: {
                list: 'list'
            },
            success: function (response) {
                let data = JSON.parse(response);

                $('#productos').DataTable({
                    data: data,
                    style: 'display',
                    columns: [
                        { data: 'category' },
                        { data: 'description' },
                        //formato de precio y puntos de miles
                        {
                            data: 'price',
                            render: function (data, type, row) {
                                return `$ ${data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")}`;
                            }
                        },
                        {
                            data: null,
                            render: function (data, type, row) {
                                return `<button class="btn btn-danger btn-delete" data-id="${data.id}">
                                <i class="bi bi-trash"></i>
                                </button>
                                <button class="btn btn-warning btn-edit" data-id="${data.id}">
                                <i class="bi bi-pencil"></i>
                                </button> `
                                    ;
                            }
                        }
                    ]
                });
            }
        });
    }
    listProducts();
    $(document).on('click', '.btn-add', function () {
        Swal.fire({
            title: 'Agregar Producto',
            html: `<select class="form-control" id="category" name="category" required>
            <option value="">Seleccionar categoria</option>
            <option value="Protesis en Acrilico">Protesis en Acrilico</option>
            <option value="Ferulas">Ferulas</option><option value="Ortopedia">Ortopedia</option>
            <option value="Flexibles Termoplasticos">Flexibles Termoplasticos</option>
            </select>
            <textarea class="form-control mt-2" id="description" name="description" placeholder="Descripcion" required></textarea>
            <input type="number" class="form-control mt-2" id="price" name="price" placeholder="Precio" required>
            <input type="file" class="form-control mt-2" id="image" name="image" required>
            `,
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Agregar',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                if ($('#category').val() == '' || $('#description').val() == '' || $('#price').val() == '') {
                    Swal.showValidationMessage('Todos los campos son requeridos');
                } else {
                    let formdata = new FormData();
                    formdata.append('category', $('#category').val());
                    formdata.append('description', $('#description').val());
                    formdata.append('price', $('#price').val());
                    formdata.append('image', $('#image')[0].files[0]);
                    $.ajax({
                        url: '../../controllers/add.php',
                        type: 'POST',
                        data: formdata,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            response = JSON.parse(response);
                            if (response.status == 200) {
                                Swal.fire('Exito', response.message, 'success');
                                $('#productos').DataTable().destroy();
                                listProducts();
                            } else {
                                Swal.fire('Error', response.message, 'error');
                            }
                        }
                    });
                }
            }
        });
    });
    $(document).on('click', '.btn-delete', function () {
        let id = $(this).data('id');
        Swal.fire({
            title: 'Eliminar Producto',
            text: '¿Estas seguro de eliminar este producto?',
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Eliminar',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                $.ajax({
                    url: '../../controllers/delete.php',
                    type: 'GET',
                    data: {
                        id: id,
                        delete: 'delete'
                    },
                    success: function (response) {
                        //console.log(response);
                        response = JSON.parse(response);
                        if (response.status == 200) {
                            Swal.fire('Exito', response.message, 'success');
                            $('#productos').DataTable().destroy();
                            listProducts();
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    }
                });
            }
        });
    });
    $(document).on('click', '.btn-edit', function () {
        let id = $(this).data('id');
        $.ajax({
            url: '../../controllers/get.php',
            type: 'GET',
            data: {
                get: 'get',
                id: id
            },
            success: function (response) {
                let data = JSON.parse(response);
                $category = data.category;
                $description = data.description;
                $price = data.price;

                Swal.fire({
                    title: 'Editar Producto',
                    html: `<select class="form-control" id="category" name="category" required value="${$category}">
                    <option value="">Seleccionar categoria</option>
                    <option value="Protesis en Acrilico">Protesis en Acrilico</option>
                    <option value="Ferulas">Ferulas</option><option value="Ortopedia">Ortopedia</option>
                    <option value="Flexibles Termoplasticos">Flexibles Termoplasticos</option>
                    </select>
                    <textarea class="form-control mt-2" id="description" name="description" placeholder="Descripcion" required>${$description}</textarea>
                    <input type="number" class="form-control mt-2" id="price" name="price" placeholder="Precio" required value="${$price}">
                    `,
                    showCancelButton: true,
                    cancelButtonText: 'Cancelar',
                    confirmButtonText: 'Editar',
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        if ($('#category').val() == '' || $('#description').val() == '' || $('#price').val() == '') {
                            Swal.showValidationMessage('Todos los campos son requeridos');
                        } else {
                            let dataToSend = {
                                update: 'update',
                                id: id,
                                category: $('#category').val(),
                                description: $('#description').val(),
                                price: $('#price').val()
                            };
                            $.ajax({
                                url: '../../controllers/edit.php',
                                type: 'GET',
                                data: dataToSend,
                                success: function (response) {
                                    // console.log(response);
                                    response = JSON.parse(response);
                                    if (response.status == 200) {
                                        Swal.fire('Exito', response.message, 'success');
                                        $('#productos').DataTable().destroy();
                                        listProducts();
                                    }
                                }
                            });
                        }
                    }
                });
            }
        });
    });
});
