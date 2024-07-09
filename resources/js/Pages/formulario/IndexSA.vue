
<script setup>
// const form = useForm({ })
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Breadcrumb from '@/Components/Breadcrumb.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SelectInput from '@/Components/SelectInput.vue';
import { reactive, watch, ref, watchEffect, onMounted, 
    onBeforeUnmount, computed } from 'vue';

import DangerButton from '@/Components/DangerButton.vue';
import pkg from 'lodash';
import { router, usePage, Link, useForm } from '@inertiajs/vue3';

import Pagination from '@/Components/Pagination.vue';
import { ChevronUpDownIcon, PencilIcon, TrashIcon } from '@heroicons/vue/24/solid';
// import { CursorArrowRippleIcon, ChevronUpDownIcon,QuestionMarkCircleIcon, EyeIcon, PencilIcon, TrashIcon, UserGroupIcon } from '@heroicons/vue/24/solid';

import Create from '@/Pages/formulario/Create.vue';
import Edit from '@/Pages/formulario/Edit.vue';
import Delete from '@/Pages/formulario/Delete.vue';

import Checkbox from '@/Components/Checkbox.vue';
import InfoButton from '@/Components/InfoButton.vue';

import {PrimerasPalabras, vectorSelect, formatDate, CalcularAvg, number_format, plata_format} from '@/global.ts';
import RadioButton from "@/Components/RadioButton.vue";

const { _, debounce, pickBy } = pkg
const props = defineProps({
    fromController: Object,
    total: Number,
    filters: Object,
    breadcrumbs: Object,
    perPage: Number,

    title: String,

    numberPermissions: Number,
    losSelect:Object,//normally used by headlessui
})

const data = reactive({
    params: {
        search: props.filters.search,
        searcLider: props.filters.searcLider,
        SoloEnviados: props.filters.SoloEnviados,
        SoloGuardados: props.filters.SoloGuardados,
        field: props.filters.field,
        order: props.filters.order,
        perPage: props.perPage,
    },
    frontp:{
        category: '',
        identity: '',
    },
    formularioo: null,
    selectedId: [],
    multipleSelect: false,
    createOpen: false,
    editOpen: false,
    deleteOpen: false,
    // deleteBulkOpen: false,
    dataSet: usePage().props.app.perpage,
    ElTotalFinal: 0,
    ElCantidadTotal: 0,
    claseSticky: 'sticky',
    hayCongelado: false,
    terminoelDeepCambio: false,
})
onMounted(() => {
    calcularTotal(props.fromController.data)

    window.addEventListener('scroll', updateScroll);

});

// function ActivarYDesactivarBooleans(){
//     if (data.params.SoloEnviados) {
//         data.params.SoloGuardados = false
//     }
//     if (data.params.SoloGuardados) {
//         data.params.SoloEnviados = false
//     }
//     console.log("=>(IndexSA.vue:71) data.params.SoloGuardados", data.params.SoloGuardados);
// }

watchEffect(() => {
//    
//     setTimeout((()=> ActivarYDesactivarBooleans()
//     ),500)
// 
},{deep:true})

// <!--<editor-fold desc="order, watchclone, select">-->
const order = (field) => {
    data.params.field = field
    data.params.order = data.params.order === "asc" ? "desc" : "asc"
}

let originalFromcontroller = _.cloneDeep(props.fromController) 
watch(() => _.cloneDeep(data.frontp), debounce(() => {
    // data.cargaFiltro
    let params = pickBy(data.frontp)

    const keysToCheck = ['category', 'identity', 'otherParam1', 'otherParam2'];
    const Validacion = keysToCheck.some(key => params[key])
    
    if (Validacion) {
        props.fromController.data = originalFromcontroller.data
        if (params.category) {
            props.fromController.data = props.fromController.data.filter(item =>{
                return item.Categori == params.category
            }
        )}
        if (params.identity) {
            props.fromController.data = props.fromController.data.filter(item => item.identificacion_user == params.identity)
        }
    } else {
        props.fromController.data = originalFromcontroller.data
    }
    
}),100)


watch(() => _.cloneDeep(data.params), debounce(() => {
    let params = pickBy(data.params)
    router.get(route("formularioSA"), params, {
        replace: true,
        preserveState: true,
        preserveScroll: true,
    })
    data.terminoelDeepCambio = true
    data.ElTotalFinal = 0
    setTimeout(() => {
        data.terminoelDeepCambio = false
        calcularTotal(props.fromController.data)
    }, 500)
}, 150))
watch(props.fromController, (newVal, oldVal) => {
    if (newVal !== oldVal) {
        setTimeout(() => {
            calcularTotal(props.fromController.data)
        }, 10)
    }
})

const selectAll = (event) => {
    if (event.target.checked === false) {
        data.selectedId = []
    } else {
        props.formularios?.data.forEach((formulario) => {
            data.selectedId.push(formulario.id)
        })
    }
}
const select = () => {
    if (props.fromController?.data.length === data.selectedId.length) {
        data.multipleSelect = true
    } else {
        data.multipleSelect = false
    }
}
// <!--</editor-fold>-->


function calcularTotal(fromCtroler){
    let value = 0
    data.ElTotalFinal = 0
        fromCtroler.forEach(element => {
            value = element.valor_total_solicitatdo_por_necesidad.replace(/\.0+$/, '');
    
            value = parseInt(value);
            data.ElTotalFinal += value
            
            data.ElCantidadTotal += parseInt(element.cantidad)
        });

}

// text // number // dinero // date // datetime // foreign
const titulos = [
    { order: 'numero_necesidad', label: '#', type: 'alterNumber' },
    { order: 'identificacion_user', label: 'cc', type: 'minitext' },
    {order: 'enviado', label: 'Enviado', type: 'boolEnviado'},
    { order: 'necesidad', label: 'necesidad', type: 'text' },
    { order: 'justificacion', label: 'justificacion', type: 'text' },
    { order: 'proceso_que_solicita_presupuesto', label: 'Proceso que solicita presupuesto', type: 'selectsMultiple',newName:'proceso_que_solicita_presupuest' },
    { order: 'actividad', label: 'Actividad', type: 'text' },
    // { order: 'categoria', label: 'Categoria', type: 'text' },
    { order: 'categoria', label: 'Categoria', type: 'selectsMultiple', newName: 'Categori'},
    { order: 'cantidad', label: 'Cantidad', type: 'number' },
    { order: 'unidad_de_medida', label: 'Unidad de medida', type: 'text' },
    { order: 'valor_unitario', label: 'Valor unitario', type: 'dinero' },
    { order: 'valor_total_solicitatdo_por_necesidad', label: 'Valor total solicitatdo por necesidad', type: 'dinero' },
    { order: 'periodo_de_inicio_de_ejecucion', label: 'Periodo de inicio de ejecucion', type: 'text' },
    { order: 'vigencias_anteriores', label: 'Vigencias anteriores', type: 'text' },
    { order: 'valor_asignado_en_la_vigencia_anterior', label: 'Valor asignado en la vigencia anterior', type: 'dinero' },
    
    { order: 'procesos_involucrados', label: 'Procesos involucrados', type: 'selectsMultiple',newName:'procesos_involucrado'},
    { order: 'plan_de_mejoramiento_al_que_apunta_la_necesidad', label: 'Plan de mejoramiento al que apunta la necesidad', type: 'selectsMultiple',newName:'plan_de_mejoramiento_al_que_apunta_la_necesida'},
    { order: 'linea_del_plan_desarrollo_al_que_apunta_la_necesidad', label: 'Linea del plan desarrollo al que apunta la necesidad', type: 'selectsMultiple',newName:'linea_del_plan_desarrollo_al_que_apunta_la_necesida'},
    
    { order: 'frecuencia_de_uso', label: 'Frecuencia de uso', type: 'text' },
    { order: 'mantenimientos_requeridos', label: 'Mantenimientos requeridos', type: 'text' },
    { order: 'capacidad_instalada', label: 'Capacidad instalada', type: 'text' },
    { order: 'riesgo_de_la_inversion', label: 'Riesgo de la inversion', type: 'text' },
    { order: 'valor_total_de_la_solicitud_actual', label: 'Valor total de la solicitud actual', type: 'dinero' },
  // { order: 'inventario', label: 'inventario', type: 'foreign',nameid:'nombre'},
  //   anexos
  //   user_id
];

const scrollY = ref(0);

const updateScroll = () => {
    scrollY.value = window.scrollY;
    console.log("=>(IndexSA.vue:180) scrollY.value", scrollY.value);
};
onBeforeUnmount(() => {
    window.removeEventListener('scroll', updateScroll);
});

data.claseSticky = computed(() => (scrollY.value > 300 ? 'fixed' : 'sticky'));
data.hayCongelado = computed(() => (scrollY.value > 300));

</script>

<template>
    <Head :title="props.title" />

    <AuthenticatedLayout>
        <Breadcrumb :title="title" :breadcrumbs="breadcrumbs" class="capitalize text-xl font-bold"/>
        
        <div class="space-y-1">
<!--             {{ props.losSelect }} -->
            <div class="px-4 sm:px-0"><div class=" rounded-lg overflow-hidden w-fit"></div></div>
            <div class=" bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div
                     v-sticky="{ zIndex: 100 }"
                     class="flex justify-items-start p-2 gap-6 dark:bg-gray-900/50 text-left top-0 bg-white"
                     :class="data.claseSticky">
                    <div class="flex">
                        <SelectInput v-model="data.params.perPage" :dataSet="data.dataSet" />
                        <!-- <DangerButton @click="data.deleteBulkOpen = true"
                            v-show="data.selectedId.length != 0 && can(['delete formulario'])" class="px-3 py-1.5"
                            v-tooltip="lang().tooltip.delete_selected">
                            <TrashIcon class="w-5 h-5" />
                        </DangerButton> -->
                    </div>
                    <TextInput v-if="props.numberPermissions >= 0" v-model="data.params.search" type="text"
                        placeholder="Necesidad o justificacion"
                        class="block w-1/6 md:w-1/4 rounded-lg" />
                    <SelectInput v-model="data.frontp.category" :dataSet="props.losSelect.ListarCategoria"
                        class="block w-1/6 md:w-1/4 rounded-lg" />

<!--                    <TextInput v-if="props.numberPermissions >= 0" v-model="data.params.searcLider" type="number"-->
<!--                        placeholder="Cedula"-->
<!--                        class="block w-1/6 md:w-1/5 rounded-lg" />-->
                    <TextInput v-if="props.numberPermissions >= 0" v-model="data.frontp.identity" type="number"
                        placeholder="Cedula"
                        class="block w-1/6 md:w-1/5 rounded-lg" />
<!--                    <div class="grid grid-rows-2 my-auto mx-1">-->
<!--                        <div class="inline-flex my-auto mx-4 gap-4">-->
<!--                            <Checkbox v-model:checked="data.params.SoloEnviados" id="idEnviados" /><label for="idEnviados">Enviados</label>-->
<!--                        </div>-->
<!--                        <div class="inline-flex my-auto mx-4 gap-4">-->
<!--                            <Checkbox v-model:checked="data.params.SoloGuardados" id="idGuardados" /><label for="idGuardados">Guardados</label>-->
<!--                        </div>-->
<!--                    </div>-->
                    <div class="grid grid-rows-3 my-auto mx-1 h-12 z-20">
                            <div>
                                <input v-model="data.params.SoloEnviados" type="radio" name="booleanFilters" value="Todos" checked/>
                                <label for="guardados" class="mx-2 text-xs">Todos</label>
                            </div>
                            <div>
                                <input v-model="data.params.SoloEnviados" type="radio" name="booleanFilters" value="guardados"/>
                                <label for="guardados" class="mx-2 text-xs">Guardados</label>
                            </div>

                            <div>
                                <input v-model="data.params.SoloEnviados" type="radio" name="booleanFilters" value="enviados"/>
                                <label for="enviados" class="mx-2 text-xs">Enviados</label>
                            </div>
                    </div>
                </div>
                <div class="overflow-auto scrollbar-table max-h-[70vh]">
                    <table v-if="props.total > 0" class="min-w-full border-collapse overflow-y-scroll max-h-sm ">
                        <caption class="caption-top">
                            Tabla 1.0 Necesidades por cedula
                        </caption>
                        <thead class="sticky top-0 z-10 bg-white uppercase text-sm border-t border-gray-200 dark:border-gray-700">
                            <tr class="dark:bg-gray-900/50">
                                <!--                                <th class="px-2 py-4 text-center">-->
<!--                                    <Checkbox v-model:checked="data.multipleSelect" @change="selectAll" />-->
<!--                                </th>-->
<!--                                <th v-if="numberPermissions > 1" class="px-2 py-4">Accion</th>-->

<!--                                <th class="px-2 py-4 text-center">#</th>-->
                                <th v-for="titulo in titulos" class="px-2 py-4 cursor-pointer text-center w-64"
                                    v-on:click="order(titulo['order'])">
                                    <div class="items-center text-center">
                                        <span class="text-center underline">{{ titulo['label'] }}</span>
<!--                                        <span>{{ lang().label[titulo['label']] }}</span>-->
<!--                                        <ChevronUpDownIcon class="w-4 h-4" />-->
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(claseFromController, indexu) in props.fromController.data" :key="indexu"
                                class="border-t border-gray-200 dark:border-gray-700 hover:bg-sky-100 hover:dark:bg-gray-900/20 even:bg-gray-300">
                                <td v-for="titulo in titulos" class=" text-center py-4 px-2 sm:py-3 w-64">
                                    <span v-if="titulo['type'] === 'alterNumber'" class="max-w-[120px]"> {{ parseInt(claseFromController[titulo['order']])+1 }} </span>
                                    <p v-if="titulo['type'] === 'text'" class="mx-auto text-center text-sm min-w-[200px]"> {{ PrimerasPalabras(claseFromController[titulo['order']], 18) }} </p>
                                    <p v-if="titulo['type'] === 'minitext'" class="mx-auto text-center text-sm max-w-[120px]"> {{ PrimerasPalabras(claseFromController[titulo['order']], 10) }} </p>
                                    <span v-if="titulo['type'] === 'number'"> {{ number_format(claseFromController[titulo['order']], 0, false) }} </span>
                                    <span v-if="titulo['type'] === 'dinero'"> {{ number_format(claseFromController[titulo['order']], 0, true) }} </span>
                                    <span v-if="titulo['type'] === 'date'"> {{ formatDate(claseFromController[titulo['order']], false) }} </span>
                                    <span v-if="titulo['type'] === 'datetime'"> {{ formatDate(claseFromController[titulo['order']], true) }} </span>
                                    <span v-if="titulo['type'] === 'foreign'" class="min-w-[400px]"> {{ claseformularioa[titulo['order']][titulo['nameid']] }} </span>
                                    <span v-if="titulo['type'] === 'boolEnviado'" class="max-w-[90px]"> {{ (claseFromController[titulo['order']] ? '✅' : 'Guardado') }} </span>
                                    <p v-if="titulo['type'] === 'selectsMultiple'" class="min-w-[200px]"> {{ (claseFromController[titulo['newName']]) }} </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <h2 v-else class="text-center text-xl my-8">Sin Registros</h2>
                </div>
                <div v-if="data.ElTotalFinal === 0" class="border border-blue-300 shadow rounded-md p-4 max-w-sm w-full mx-auto">
                    <div class="animate-pulse flex space-x-4">
                        <div class="rounded-full bg-slate-700 h-10 w-10"></div>
                        <div class="flex-1 space-y-6 py-1">
                            <div class="h-2 bg-slate-700 rounded"></div>
                            <div class="space-y-3">
                                <div class="grid grid-cols-3 gap-4">
                                    <div class="h-2 bg-slate-700 rounded col-span-2"></div>
                                    <div class="h-2 bg-slate-700 rounded col-span-1"></div>
                                </div>
                                <div class="h-2 bg-slate-700 rounded"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <p v-else class="whitespace-nowrap py-4 px-2 sm:py-3 text-center font-bold text-lg">Total: {{ plata_format(data.ElTotalFinal) }}</p>

                <!--                    v-if="props.total"-->
                <div class="flex justify-between items-center p-2 border-t border-gray-200 dark:border-gray-700">
                    <Pagination :links="props.fromController" :filters="data.params" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<style scoped>
.inamovible {
    position: fixed;
    top: 0;
    left: 250px;
    z-index: 100;
}
/* Añade estilos adicionales según tus necesidades */
</style>