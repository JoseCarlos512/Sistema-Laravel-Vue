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
                    <i class="fa fa-align-justify"></i> Proveedores
                    <button type="button" @click="abrirModal('persona','registrar')" class="btn btn-secondary">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control col-md-3" v-model="criterio">
                                    <option value="nombre">Nombre</option>
                                    <option value="num_documento">Documento</option>
                                    <option value="telefono">Telefono</option>
                                    <option value="email">E-mail</option>
                                </select>
                                <input type="text" v-model="buscar" @keyup.enter="listarPersonas(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                <button type="submit" @click="listarPersonas(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Nombre</th>
                                <th>Tipo Documento</th>
                                <th>Numero</th>
                                <th>Direccion</th>
                                <th>Telefono</th>
                                <th>Email</th>
                                <th>Contacto</th>
                                <th>Telefono Contacto</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="persona in arrayPersona" :key="persona.id">
                                <td>
                                    <button type="button" @click="abrirModal('persona', 'actualizar', persona)" class="btn btn-warning btn-sm">
                                        <i class="icon-pencil"></i>
                                    </button>
                                </td>
                                <td v-text="persona.nombre"></td>
                                <td v-text="persona.tipo_documento"></td>
                                <td v-text="persona.num_documento"></td>
                                <td v-text="persona.direccion"></td>
                                <td v-text="persona.telefono"></td>
                                <td v-text="persona.email"></td>
                                <td v-text="persona.contacto"></td>
                                <td v-text="persona.telefono_contacto"></td>
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
                                <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="nombre" class="form-control" placeholder="Nombre de la persona">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Tipo DOcumento</label>
                                <div class="col-md-9">
                                    <select v-model="tipo_documento" class="form-control">
                                        <option value="DNI">DNI</option>
                                        <option value="RUC">RUC</option>
                                        <option value="PASS">PASS</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Numero</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="num_documento" class="form-control" placeholder="Numero de documento">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Direccion</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="direccion" class="form-control" placeholder="Ingrese su direccion">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Telefono</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="telefono" class="form-control" placeholder="Ingrese el numero telefonico">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Email</label>
                                <div class="col-md-9">
                                    <input type="email" v-model="email" class="form-control" placeholder="Ingrese correo electronico">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Contacto</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="contacto" class="form-control" placeholder="Ingrese nombre del contacto">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Telefono Proveedor</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="telefono_contacto" class="form-control" placeholder="Ingrese numero privado delcontacto">
                                </div>
                            </div>
                            <div v-show="errorPersona" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in errorMostrarMsjPersona" :key="error" v-text="error">

                                    </div>
                                </div>
                            </div>
                        </form>
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
    export default {
        data() {
            return {
                persona_id: 0,
                nombre: '',
                tipo_documento: '',
                num_documento: [],
                direccion: 0,
                telefono: '',
                email: '',
                contacto : '',
                telefono_contacto : '',
                arrayPersona: [],
                modal: 0,
                tituloModal: '',
                accion: 0,
                errorPersona: 0,
                errorMostrarMsjPersona: [],
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
                buscar: ''
            }
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
            listarPersonas(page, buscar, criterio){
                //No entiendo porque necesariamente debo declarar let me, se podria usar defrente this.
                let me = this;
                var url = '/proveedor?page=' + page +'&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url)
                .then(function (response) {
                    // handle success
                    var respuesta = response.data;
                    me.arrayPersona = respuesta.personas.data;
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
            cambiarPagina(page, buscar, criterio){
                let me = this;

                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Enviar la peticion para visualizar la data de esa pagina
                me.listarPersonas(page, buscar, criterio);
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
                axios.post('/proveedor/registrar', 
                {
                    'nombre': me.nombre,
                    'tipo_documento': me.tipo_documento,
                    'num_documento': me.num_documento,
                    'direccion': me.direccion,
                    'telefono': me.telefono,
                    'email': me.email,
                    'contacto': me.contacto,
                    'telefono_contacto': me.telefono_contacto
                }).then(function (response) {
                    // handle success
                    me.cerrarModal();
                    swalWithBootstrapButtons.fire(
                        'Registrado!',
                        'El registro ha sido registrado con exito.',
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
                axios.put('/proveedor/actualizar', 
                {
                    'nombre': me.nombre,
                    'tipo_documento': me.tipo_documento,
                    'num_documento': me.num_documento,
                    'direccion': me.direccion,
                    'telefono': me.telefono,
                    'email': me.email,
                    'contacto': me.contacto,
                    'telefono_contacto': me.telefono_contacto,
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


                //Array vacion es diferente de null por consiguiente se ejecutara, pero si pones length no
                if(this.errorMostrarMsjPersona.length) this.errorPersona = 1;

                return this.errorPersona;
            },
            abrirModal(modelo, accion, data = []){ 
                switch (modelo) {
                    case "persona":
                    {
                        switch (accion) {
                            case 'registrar':
                            {
                                this.tituloModal = 'Registrar Cliente';
                                this.accion = 1;
                                this.modal = 1;
                                this.nombre = '';
                                this.tipo_documento = 'RUC';
                                this.num_documento = '';
                                this.direccion = '';
                                this.telefono = '';
                                this.email = '';
                                this.contacto = '';
                                this.telefono_contacto = '';
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
                                 this.contacto = data['contacto'];
                                 this.telefono_contacto = data['telefono_contacto'];
                                 break;
                            }
                        }
                    }
                }
            },
            cerrarModal(){
                this.modal =0;
                this.tituloModal = '';
                this.nombre = '';
                this.tipo_documento = '';
                this.num_documento = '';
                this.direccion = '';
                this.telefono = '';
                this.email = '';
                this.contacto = '';
                this.telefono_contacto = '';
                this.errorPersona = '';
            }
        },
        mounted() {
            this.listarPersonas(1, this.buscar, this.criterio );
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