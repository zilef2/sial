<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import SwitchDarkMode from '@/Components/SwitchDarkMode.vue';
import {Head, router} from '@inertiajs/vue3'; //Link
import {reactive, ref, watch, watchEffect} from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {form} from './form';
import InputError from "@/Components/InputError.vue";
import SelectInput from "@/Components/SelectInput.vue";
import {number_format, plata_format} from "@/global";
import Toast from "@/Components/Toast.vue";
import {AumentarForm, DisminuirForm} from "@/Pages/formFunctions";
import {throttle} from 'lodash';
import MasyMenos from "@/Pages/MasyMenos.vue";
import TablaResumenEnviado from "@/Pages/TablaResumenEnviado.vue";


const props = defineProps({
    cedLideres: Object,
    losSelect: Object,
    cedLideresGuardados: Object,
    cedLideresDiligenciados: Object,
    TodosDiligenciados: Boolean,
    infoEnviada: Object,
})

let anioActual = new Date().getFullYear();

const data = reactive({
    // modoCero:false,
    TamanoMAX: 5, //5MB
});
</script>

<template>
    <div>
        <Head :title="lang().label.welcome"/>

        <div class="relative flex justify-center min-h-screen bg-gray-300 dark:bg-gray-900 text-gray-900 dark:text-gray-100 items-center sm:pt-0">
            <section class="items-center w-full m-10 lg:mx-20 xl:mx-40 sm:px-6 lg:px-8 2xl:px-auto">
                <article class="flex pt-8 px-4 sm:px-0 sm:pt-0">
                    <div class="flex mx-auto text-center items-center">
                        <ApplicationLogo class="h-32 w-auto text-primary fill-current"/>
                        <p class="text-3xl 2xl:text-4xl sm:text-3xl mx-auto text-[#499884] font-medium">Necesidades económicas anuales por dependencia
                            {{ anioActual + 1 }}</p>
                    </div>
                    <div class="flex items-center">
                        <!-- <SwitchLangNavbar /> -->
                        <SwitchDarkMode/>
                    </div>
                </article>

                <!-- component -->
            </section>
        </div>
        
    </div>
</template>
<style>
::-webkit-scrollbar {
    height: 14px; /* Altura del scrollbar vertical */
    width: 4px; /* Ancho del scrollbar horizontal */
    border-radius: 2px;
}

/* Estilo del thumb del scrollbar */
::-webkit-scrollbar-thumb {
    background-color: #00004f; /* Color del thumb */
    border-radius: 1px; /* Radio de borde del thumb */
}

</style>
