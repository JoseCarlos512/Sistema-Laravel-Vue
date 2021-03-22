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
                    <i class="fa fa-align-justify"></i> Ingresos
                    <button type="button" @click="mostrarDetalle()" class="btn btn-secondary">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
                <!-- CARD BODY LISTADO INGRESOS (TABLE)-->
                <template v-if="listado">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                        <option value="tipo_comprobante">Tipo Comprobante</option>
                                        <option value="num_comprobante">Numero Comprobante</option>
                                        <option value="fecha_hora">Fecha-Hora</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarIngreso(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarIngreso(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Usuario</th>
                                        <th>Proveedor</th>
                                        <th>Tipo Comprobante</th>
                                        <th>Serie Comprobante</th>
                                        <th>Numero Comprobante</th>
                                        <th>Fecha Hora</th>
                                        <th>Total</th>
                                        <th>Impuesto</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="ingreso in arrayIngreso" :key="ingreso.id">
                                        <td>
                                            <button type="button" @click="abrirModal('ingreso', 'actualizar', ingreso)" class="btn btn-success btn-sm">
                                                <i class="icon-eye"></i>
                                            </button>
                                            <template v-if="ingreso.estado == 'Registrado'">
                                                <button type="button" class="btn btn-danger btn-sm" @click="desactivarIngreso(ingreso.id)">
                                                    <i class="icon-trash"></i>
                                                </button>
                                            </template>
                                        </td>
                                        <td v-text="ingreso.usuario"></td>
                                        <td v-text="ingreso.nombre"></td>
                                        <td v-text="ingreso.tipo_comprobante"></td>
                                        <td v-text="ingreso.serie_comprobante"></td>
                                        <td v-text="ingreso.num_comprobante"></td>
                                        <td v-text="ingreso.fecha_hora"></td>
                                        <td v-text="ingreso.total"></td>
                                        <td v-text="ingreso.impuesto"></td>
                                        <td v-text="ingreso.estado"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
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
                </template>
                <!-- FIN LISTADO INGRESOS (TABLE)-->
                <!-- CARD BODY DETALLE -->
                <template v-else>
                    <div class="card-body">
                        <div class="form-group row border">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label>Proveedor(*)</label>
                                    <v-select
                                        :on-search="selectProveedor"
                                        label="nombre"
                                        :options="arrayProveedor"
                                        placeholder="Buscar Proveedores..."
                                        :onChange="getDatosProveedor"                                        
                                    >

                                    </v-select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label>Impuesto(*)</label>
                                <input type="text" class="form-control" v-model="impuesto" placeholder="Ingrese el impuesto">
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tipo Comprobante(*)</label>
                                    <select class="form-control" v-model="tipo_comprobante">
                                        <option value="0">Seleccione</option>
                                        <option value="BOLETA">Boleta</option>
                                        <option value="FACTURA">Factura</option>
                                        <option value="TICKET">Ticket</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Serie Comprobante(*)</label>
                                    <input type="text" class="form-control" v-model="serie_comprobante" placeholder="Ingrese la serie comprobante">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Numero Comprobante(*)</label>
                                    <input type="text" class="form-control" v-model="num_comprobante" placeholder="Ingrese el numero comprobante">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row border">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Articulo</label>
                                    <div class="form-inline">
                                        <input type="text" class="form-control" v-model="codigo" @keyup.enter="buscarArticulo()" placeholder="Ingrese el articulo">
                                        <button class="btn btn-primary">...</button>
                                        <input type="text" readonly class="form-control" v-model="articulo">
                                    </div>
                                </div> 
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Precio</label>
                                    <input type="number" value="0" step="any" class="form-control" v-model="precio">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Cantidad</label>
                                    <input type="number" value="0" class="form-control" v-model="cantidad">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <button @click="agregarDetalle()" class="btn btn-success form-control btnagregar"><i class="icon-plus"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row border">
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered tablle-striped table-sm">
                                    <thead>
                                        <th>Opciones</th>
                                        <th>Articulo</th>
                                        <th>Precio</th>
                                        <th>Cantidad</th>
                                        <th>Subtotal</th>
                                    </thead>
                                    <tbody v-if="arrayDetalle.length">
                                        <tr v-for="detalle in arrayDetalle" :key="detalle.id">
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm">
                                                    <i class="icon-close"></i>
                                                </button>
                                            </td>
                                            <td v-text="detalle.articulo"></td>
                                            <td>
                                                <input v-model="detalle.precio" type="number" value="3" class="form-control">
                                            </td>
                                            <td>
                                                <input v-model="detalle.cantidad" type="number" value="2" class="form-control">
                                            </td>
                                            <td>
                                                {{detalle.precio * detalle.cantidad}}
                                            </td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right"><strong>Total Parcial:</strong></td>
                                            <td>$ 5</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right"><strong>Total Impuesto:</strong></td>
                                            <td>$ 5</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right"><strong>Total Neto:</strong></td>
                                            <td>$ 5</td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="5">No hay articulos agregados</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-secondary" @click="cerrarDetalle()">Cerrar</button>
                                <button type="button" class="btn btn-primary" @click="registrarIngreso()">Registrar Compra</button>
                            </div>
                        </div>
                    </div>
                </template>
                <!-- FIN DETALLE -->
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
                        <!-- Modal en blanco -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        <div v-if="accion==1">
                            <button type="button" class="btn btn-primary" @click="registrarPersona()">Guardar</button>
                        </div>
                        <div v-else-if="accion==2">
                            <button type="button" class="btn btn-primary" @click="actualizarPersona()">Actualizar</button>
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
import vSelect from 'vue-select';
    export default {
        data() {
            return {
                ingreso_id: 0,
                idproveedor: '',
                tipo_comprobante: 'BOLETA',
                serie_comprobante: [],
                num_comprobante: '',
                impuesto: 0.18,
                total: 0.0,
                arrayIngreso: [],
                arrayProveedor: [],
                arrayDetalle: [],
                listado: 1,
                modal: 0,
                tituloModal: '',
                accion: 0,
                errorIngreso: 0,
                errorMostrarMsjIngreso: [],
                pagination: {
                    'total'         : 0,
                    'current_page'  : 0,
                    'per_page'      : 0,
                    'last_page'     : 0,
                    'from'          : 0,
                    'to'            : 0
                },
                offset: 3,
                criterio: 'num_comprobante',
                buscar: '',
                arrayArticulo: [],
                idarticulo: 0,
                codigo: '',
                articulo: '',
                precio: 0,
                cantidad: 0
            }
        },
        components: {
            vSelect
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
            listarIngreso(page, buscar, criterio){
                //No entiendo porque necesariamente debo declarar let me, se podria usar defrente this.
                let me = this;
                var url = '/ingreso?page=' + page +'&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url)
                .then(function (response) {
                    // handle success
                    var respuesta = response.data;
                    me.arrayIngreso = respuesta.ingresos.data;
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
            selectProveedor(search,loading){
                let me = this;
                loading(true); //Sin declarar asigno true a loading
                
                var url = '/proveedor/selectProveedor?filtro='+search;
                axios.get(url).then(function (response) {
                    let respuesta = response.data;
                    //No se que hace q:search
                    q: search
                    me.arrayProveedor = respuesta.proveedores;
                    loading(false);
                })
                .catch(function (error) {
                    console.log(error);
                });

            },
            getDatosProveedor(val1){
                let me = this;
                me.loading = true;
                me.idproveedor = val1.id;
            },
            buscarArticulo(){
                let me = this;
                var url = '/articulo/buscarArticulo?filtro=' + me.codigo;

                axios.get(url).then(function(response){
                    var respuesta = response.data;
                    me.arrayArticulo = respuesta.articulos;

                    if(me.arrayArticulo.length > 0) {
                        me.articulo = me.arrayArticulo[0]['nombre'];
                        me.idarticulo = me.arrayArticulo[0]['id'];
                    } else {
                        me.articulo = 'No existe articulo';
                        me.idarticulo = 0;
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cambiarPagina(page, buscar, criterio){
                let me = this;

                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Enviar la peticion para visualizar la data de esa pagina
                me.listarIngreso(page, buscar, criterio);
            },
            agregarDetalle(){
                let me = this;
                me.arrayDetalle.push({
                    idarticulo: me.idarticulo,
                    articulo: me.articulo,
                    cantidad: me.cantidad,
                    precio: me.precio
                });
            },
            registrarPersona(){
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })

                if(this.validarPersona() == 1){
                    return;
                }

                let me = this;
                axios.post('/user/registrar', 
                {
                    'nombre': me.nombre,
                    'tipo_documento': me.tipo_documento,
                    'num_documento': me.num_documento,
                    'direccion': me.direccion,
                    'telefono': me.telefono,
                    'email': me.email,
                    'usuario': me.usuario,
                    'password': me.password,
                    'idrol': me.idrol
                }).then(function (response) {
                    // handle success
                    me.cerrarModal();
                    swalWithBootstrapButtons.fire(
                        'Registrado!',
                        'El usuaeio ha sido registrado con exito.',
                        'success'
                    )
                    me.listarPersonas(1, '', 'nombre');
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
                .then(function () {
                    // always executed
                });
            },
            actualizarPersona(){
                if(this.validarPersona() == 1){
                    return;
                }

                let me = this;
                axios.put('/user/actualizar', 
                {
                    'nombre': me.nombre,
                    'tipo_documento': me.tipo_documento,
                    'num_documento': me.num_documento,
                    'direccion': me.direccion,
                    'telefono': me.telefono,
                    'email': me.email,
                    'usuario': me.usuario,
                    'password': me.password,
                    'idrol': me.idrol,
                    'id' : me.persona_id
                }).then(function (response) {
                    // handle success
                    me.cerrarModal();
                    me.listarPersonas(1, '', 'nombre');
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
                .then(function () {
                    // always executed
                });
            },
            validarPersona(){
                this.errorPersona = 0;
                this.errorMostrarMsjPersona = [];

                if(!this.nombre) this.errorMostrarMsjPersona.push("(*) Ingrese el nombre de la Persona.");
                if(!this.num_documento) this.errorMostrarMsjPersona.push("(*) Ingrese su numero de documento.");
                if(!this.usuario) this.errorMostrarMsjPersona.push("(*) Ingrese su usuario.");
                if(!this.password) this.errorMostrarMsjPersona.push("(*) Ingrese su password.");
                if(this.idrol == 0) this.errorMostrarMsjPersona.push("(*) Seleccione un rol.");

                //Array vacion es diferente de null por consiguiente se ejecutara, pero si pones length no
                if(this.errorMostrarMsjPersona.length) this.errorPersona = 1;

                return this.errorPersona;
            },
            desactivarUsuario(id){
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                title: 'Estas seguro de desactivar este usuario?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                    //Inicio desactivar
                    let me = this;
                    axios.put('/user/desactivar', 
                    {
                        'id': id
                    }).then(function (response) {
                        me.listarPersonas(1, '', 'nombre');
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
            activarUsuario(id){
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                title: 'Estas seguro de activar esta categoria?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                    //Inicio desactivar
                    let me = this;
                    axios.put('/user/activar', 
                    {
                        'id': id
                    }).then(function (response) {
                        me.listarPersonas(1, '', 'nombre');
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
            abrirModal(modelo, accion, data = []){ 
                this.selectRol();
                switch (modelo) {
                    case "persona":
                    {
                        switch (accion) {
                            case 'registrar':
                            {
                                this.tituloModal = 'Registrar Usuario';
                                this.accion = 1;
                                this.modal = 1;
                                this.nombre = '';
                                this.tipo_documento = 'DNI';
                                this.num_documento = '';
                                this.direccion = '';
                                this.telefono = '';
                                this.email = '';
                                this.usuario = '';
                                this.password = '';
                                this.idrol = 0;
                                break;
                            }
                            case 'actualizar':
                            {    
                                 this.modal = 1;
                                 this.tituloModal = "Actualizar Cliente";
                                 this.accion = 2;
                                 this.persona_id = data['id'];
                                 this.nombre = data['nombre'];
                                 this.tipo_documento = data['tipo_documento'];
                                 this.num_documento = data['num_documento'];
                                 this.direccion = data['direccion'];
                                 this.telefono = data['telefono'];
                                 this.email = data['email'];
                                 this.usuario = data['usuario'];
                                 this.password = data['password'];
                                 this.idrol = data['idrol'];
                                 break;
                            }
                        }
                    }
                }
            },
            mostrarDetalle(){
                this.listado = 0;
            },
            cerrarDetalle(){
                this.listado = 1;
            },
            cerrarModal(){
                this.modal =0;
                this.tituloModal = '';
                this.nombre = '';
                this.tipo_documento = 'DNI';
                this.num_documento = '';
                this.direccion = '';
                this.telefono = '';
                this.email = '';
                this.usuario = '';
                this.password = '';
                this.idrol = 0;
                this.errorPersona = '';
            }
        },
        mounted() {
            this.listarIngreso(1, this.buscar, this.criterio );
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
    @media (min-width: 600px) {
        .btnagregar {
            margin-top: 2rem;
        }
    }
</style>