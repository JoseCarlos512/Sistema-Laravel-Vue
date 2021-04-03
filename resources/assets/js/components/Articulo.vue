<template>
   <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Articulos
                    <button type="button" @click="abrirModal('articulo','registrar')" class="btn btn-secondary">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                    <button type="button" @click="cargarPDF()" class="btn btn-info">
                        <i class="icon-doc"></i>&nbsp;Reporte
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control col-md-3" v-model="criterio">
                                    <option value="nombre">Nombre</option>
                                    <option value="descripcion">Descripción</option>
                                </select>
                                <input type="text" v-model="buscar" @keyup.enter="listarArticulos(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                <button type="submit" @click="listarArticulos(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>codigo</th>
                                <th>Nombre</th>
                                <th>Categoria</th>
                                <th>Precio Venta</th>
                                <th>Stock</th>
                                <th>Descripción</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="articulo in arrayArticulo" :key="articulo.id">
                                <td>
                                    <button type="button" @click="abrirModal('articulo', 'actualizar', articulo)" class="btn btn-warning btn-sm">
                                        <i class="icon-pencil"></i>
                                    </button> &nbsp;
                                    <template v-if="articulo.condicion">
                                        <button type="button" class="btn btn-danger btn-sm" @click="desactivarArticulo(articulo.id)">
                                            <i class="icon-trash"></i>
                                        </button>
                                    </template>
                                    <template v-else>
                                        <button type="button" class="btn btn-info btn-sm" @click="activarArticulo(articulo.id)">
                                            <i class="icon-check"></i>
                                        </button>
                                    </template>
                                </td>
                                <td v-text="articulo.codigo"></td>
                                <td v-text="articulo.nombre"></td>
                                <td v-text="articulo.nombre_categoria"></td>
                                <td v-text="articulo.precio_venta"></td>
                                <td v-text="articulo.stock"></td>
                                <td v-text="articulo.descripcion"></td>
                                <td>
                                    <div v-if="articulo.condicion">
                                        <span class="badge badge-success">Activo</span>
                                    </div>
                                    <div v-else>
                                        <span class="badge badge-danger">Desactivado</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item" v-if="pagination.current_page > 1">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Ant</a>
                            </li>
                            <li class="page-item" v-for="page in pageNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                            </li>
                            <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Sig</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>
        <!--Inicio del modal agregar/actualizar-->
        <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="tituloModal"></h4>
                        <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Categoria</label>
                                <div class="col-md-9">
                                    <select class="form-control" v-model="id_categoria">
                                        <option value="0" disabled>Seleccione</option>
                                        <option v-for="categoria in arrayCategoria" :key="categoria.id" :value="categoria.id" v-text="categoria.nombre"></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Codigo</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="codigo" id="codigo" name="codigo" class="form-control" placeholder="Codigo de barras">
                                    <barcode :value="codigo" :options="{ format: 'EAN-13'}" >
                                        Generando codigo de barras
                                    </barcode>   
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="nombre" id="nombre" name="nombre" class="form-control" placeholder="Nombre de Articulo">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Precio Venta</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="precio_venta" id="precio_venta" name="precio_venta" class="form-control" placeholder="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Stock</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="stock" id="stock" name="stock" class="form-control" placeholder="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Descripción</label>
                                <div class="col-md-9">
                                    <input type="email" v-model="descripcion" id="descripcion" name="descripcion" class="form-control" placeholder="Ingrese descripcion">
                                </div>
                            </div>
                            <div v-show="errorArticulo" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in errorMostrarMsjArticulo" :key="error" v-text="error">

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        <div v-if="accion==1">
                            <button type="button" class="btn btn-primary" @click="registrarArticulo()">Guardar</button>
                        </div>
                        <div v-else-if="accion==2">
                            <button type="button" class="btn btn-primary" @click="actualizarArticulo()">Actualizar</button>
                        </div>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->
    </main>
</template>

<script>
import VueBarcode from 'vue-barcode';

    export default {
        data() {
            return {
                articulo_id: 0,
                id_categoria: 0,
                nombre_categoria: '',
                codigo: '',
                nombre: '',
                precio_venta: 0,
                stock: 0,
                descripcion: '',
                arrayArticulo: [],
                modal: 0,
                tituloModal: '',
                accion: 0,
                errorArticulo: 0,
                errorMostrarMsjArticulo: [],
                pagination: {
                    'total'         : 0,
                    'current_page'  : 0,
                    'per_page'      : 0,
                    'last_page'     : 0,
                    'from'          : 0,
                    'to'            : 0
                },
                offset: 3,
                criterio: 'nombre',
                buscar: '',
                arrayCategoria: []
            }
        },
        components: {
            'barcode': VueBarcode
        },
        computed: {
            isActived: function(){
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginacion
            pageNumber: function(){
                if(!this.pagination.to){
                    return []
                }

                var from = this.pagination.current_page - this.offset;
                if(from < 1){
                    from = 1;
                }

                var to = from + (this.offset * 2);
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }

                var pagesArray = [];
                while(from <= to ){
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            }
        },
        methods: {
            listarArticulos(page, buscar, criterio){
                //No entiendo porque necesariamente debo declarar let me, se podria usar defrente this.
                let me = this;
                var url = '/articulo?page=' + page +'&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url)
                .then(function (response) {
                    // handle success
                    var respuesta = response.data;
                    me.arrayArticulo = respuesta.articulos.data;
                    me.pagination = respuesta.pagination;

                    //Comprobar .data (ingresar a data)
                    //console.log(response);
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
                .then(function () {
                    // always executed
                });
            },
            cargarPDF(){
                window.open("http://127.0.0.1:8000/articulo/listarPDF",'_blank');
            },
            selectCategoria(){
                let me = this;
                var url = '/categoria/selectCategoria';
                axios.get(url)
                .then(function (response) {
                    // handle success
                    var respuesta = response.data;
                    me.arrayCategoria = respuesta.categorias;

                    //Comprobar .data (ingresar a data)
                    //console.log(response);
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
                .then(function () {
                    // always executed
                });
            },
            cambiarPagina(page, buscar, criterio){
                let me = this;

                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Enviar la peticion para visualizar la data de esa pagina
                me.listarArticulos(page, buscar, criterio);
            },
            registrarArticulo(){
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                })

                if(this.validarArticulo() == 1){
                    return;
                }

                let me = this;
                axios.post('/articulo/registrar', 
                {
                    'id_categoria': me.id_categoria,
                    'codigo': me.codigo,
                    'nombre': me.nombre,
                    'precio_venta': me.precio_venta,
                    'stock': me.stock,
                    'descripcion': me.descripcion
                }).then(function (response) {
                    // handle success
                    me.cerrarModal();
                    swalWithBootstrapButtons.fire(
                        'Registrado!',
                        'El registro ha sido registrado con exito.',
                        'success'
                    )
                    me.listarArticulos(1, '', 'nombre');
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
                .then(function () {
                    // always executed
                });
            },
            actualizarArticulo(){
                if(this.validarArticulo() == 1){
                    return;
                }

                let me = this;
                axios.put('/articulo/actualizar', 
                {
                    'id_categoria': me.id_categoria,
                    'codigo': me.codigo,
                    'nombre': me.nombre,
                    'precio_venta': me.precio_venta,
                    'stock': me.stock,
                    'descripcion': me.descripcion,
                    'id' : me.articulo_id
                }).then(function (response) {
                    // handle success
                    me.cerrarModal();
                    me.listarArticulos(1, '', 'nombre');
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
                .then(function () {
                    // always executed
                });
            },
            desactivarArticulo(id){
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                title: 'Estas seguro de desactivar este articulo?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                    //Inicio desactivar
                    let me = this;
                    axios.put('/articulo/desactivar', 
                    {
                        'id': id
                    }).then(function (response) {
                        me.listarArticulos(1, '', 'nombre');
                        swalWithBootstrapButtons.fire(
                            'Desactivado!',
                            'El registro ha sido desactivado con exito.',
                            'success'
                        )
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error);
                    })
                    .then(function () {
                        // always executed
                    });
                    // Fin de desactivar 

                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire(
                    'Cancelled',
                    'Your imaginary file is safe :)',
                    'error'
                    )
                }
                })
            },
            activarArticulo(id){
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                title: 'Estas seguro de activar este articulo?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                    //Inicio desactivar
                    let me = this;
                    axios.put('/articulo/activar', 
                    {
                        'id': id
                    }).then(function (response) {
                        me.listarArticulos(1, '', 'nombre');
                        swalWithBootstrapButtons.fire(
                            'Desactivado!',
                            'El registro ha sido activado con exito.',
                            'success'
                        )
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error);
                    })
                    .then(function () {
                        // always executed
                    });
                    // Fin de desactivar 

                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire(
                    'Cancelled',
                    'Your imaginary file is safe :)',
                    'error'
                    )
                }
                })
            },
            validarArticulo(){
                this.errorArticulo = 0;
                this.errorMostrarMsjArticulo = [];

                if(!this.id_categoria) this.errorMostrarMsjArticulo.push("(*) Seleccione unca categoria.");
                if(!this.nombre) this.errorMostrarMsjArticulo.push("(*) Ingrese el nombre del articulo.");
                if(!this.stock) this.errorMostrarMsjArticulo.push("(*) No dejar vacio el stock.");
                if(!this.precio_venta) this.errorMostrarMsjArticulo.push("(*) No dejar vacio el precio de venta.");

                //Array vacio es diferente de null por consiguiente se ejecutara, pero si pones length no
                if(this.errorMostrarMsjArticulo.length) this.errorArticulo = 1;

                return this.errorArticulo;
            },
            abrirModal(modelo, accion, data = []){ 
                switch (modelo) {
                    case "articulo":
                    {
                        switch (accion) {
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Articulo';
                                this.id_categoria = 0;
                                this.nombre_categoria = '';
                                this.codigo = '';
                                this.nombre = '';
                                this.precio_venta = 0;
                                this.stock = 0;
                                this.descripcion = '';
                                this.accion = 1;
                                break;
                            }
                            case 'actualizar':
                            {    
                                this.modal = 1;
                                this.tituloModal = 'Actualizar Articulo';
                                this.articulo_id = data['id'];
                                this.id_categoria = data['id_categoria'];
                                this.codigo = data['codigo'];
                                this.nombre = data['nombre'];
                                this.precio_venta = data['precio_venta'];
                                this.stock = data['stock'];
                                this.descripcion = data['descripcion'];
                                this.accion = 2;
                                 break;
                            }
                        }
                    }
                    this.selectCategoria();
                }
            },
            cerrarModal(){
                this.modal =0;
                this.tituloModal = '';
                this.id_categoria= '';
                this.nombre_categoria = '';
                this.codigo = '';
                this.nombre = '';
                this.precio_venta = 0;
                this.stock = 0;
                this.descripcion = '';
                this.errorArticulo = 0;
            }
        },
        mounted() {
            this.listarArticulos(1, this.buscar, this.criterio );
        }      
    }
</script>

<style>
    .modal-content{
        width:  100% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important; 
    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
</style>