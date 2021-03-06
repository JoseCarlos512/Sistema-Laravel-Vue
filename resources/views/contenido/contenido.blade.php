@extends('principal')    
    @section('contenido')

    @if(Auth::check())
            @if (Auth::user()->idrol == 1)
            <template v-if="menu==0">
                <dashboard/>
            </template>

            <template v-if="menu==1"> 
                <categoria/>
            </template>

            <template v-if="menu==2"> 
                <articulo/>
            </template>

            <template v-if="menu==3"> 
                <ingreso/>
            </template>

            <template v-if="menu==4"> 
                <proveedor/>
            </template>

            <template v-if="menu==5"> 
                <venta/>
            </template>

            <template v-if="menu==6"> 
                <cliente/>
            </template>

            <template v-if="menu==7"> 
                <user/>
            </template>

            <template v-if="menu==8"> 
                <rol/>
            </template>

            <template v-if="menu==9"> 
                <h1>Reportes de ingresos</h1>
            </template>
            
            <template v-if="menu==10"> 
                <h1>Reporte de ventas</h1>
            </template>

            <template v-if="menu==11"> 
                <h1>Ayuda</h1>
            </template>

            <template v-if="menu==12"> 
                <h1>Acerca de</h1>
            </template>



            @elseif (Auth::user()->idrol == 2)
            <template v-if="menu==0">
                <dashboard/>
            </template>
            <template v-if="menu==5"> 
                <h1>Ventas</h1>
            </template>

            <template v-if="menu==6"> 
                <cliente/>
            </template>

            <template v-if="menu==10"> 
                <h1>Reporte de ventas</h1>
            </template>

            <template v-if="menu==11"> 
                <h1>Ayuda</h1>
            </template>

            <template v-if="menu==12"> 
                <h1>Acerca de</h1>
            </template>




            @elseif (Auth::user()->idrol == 3)
            <template v-if="menu==0">
                <dashboard/>
            </template>
            <template v-if="menu==1"> 
                <categoria/>
            </template>

            <template v-if="menu==2"> 
                <articulo/>
            </template>

            <template v-if="menu==3"> 
                <ingreso/>
            </template>

            <template v-if="menu==4"> 
                    <proveedor/>
            </template>

            <template v-if="menu==9"> 
                <h1>Reportes de ingresos</h1>
            </template>

            <template v-if="menu==11"> 
                <h1>Ayuda</h1>
            </template>

            <template v-if="menu==12"> 
                <h1>Acerca de</h1>
            </template>
            @else

            @endif

        @endif
        
        
    @endsection