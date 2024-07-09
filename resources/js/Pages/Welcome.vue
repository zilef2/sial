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
    mensajeYaHaSidoGuardado: '',
    tamanin: '',
    mensajes: '',
    showContent: false,
    nombreLider: '',
    identificacion_disbled: false,
    colorDisabled: '',

    //data selects multiples
    planmejoramientonecesidad: [
        {value: 1, label: 'PMM Institucional'},
        {value: 2, label: 'PMM Programas'},
        {value: 3, label: 'PMM Auditorías'},
        {value: 0, label: 'Ninguno'},
    ],
    lineadelplan: [
        {value: 1, label: '1. Academia Transformadora de Vidas'},
        {value: 2, label: '2. Intercambio de Saberes para la Transformación del Entorno Social, Productivo y Científico'},
        {value: 3, label: '3. Ecosistema Tecnológico Colmayor'},
        {value: 4, label: '4. Sostenibilidad y Gestión Humana Integral'},
    ],
    proceso_que_solicita_presupuesto: [
        {label: "Admisiones, Registro y Control", value: 1},
        {label: "Aseguramiento de la Calidad Académica", value: 2},
        {label: "Biblioteca", value: 3},
        {label: "Bienes y Servicios", value: 4},
        {label: "Bienestar Institucional", value: 5},
        {label: "Centro de Lenguas", value: 6},
        {label: "Comunicación y Mercadeo", value: 7},
        {label: "Contabilidad", value: 8},
        {label: "Control Interno", value: 9},
        {label: "Extensión Académica y Proyección Social", value: 10},
        {label: "Facultad de Administración", value: 11},
        {label: "Facultad de Arquitectura e Ingeniería", value: 12},
        {label: "Facultad de Ciencias de la Salud", value: 13},
        {label: "Facultad de Ciencias Sociales y Educación", value: 14},
        {label: "Formación para el Trabajo y el Desarrollo Humano", value: 15},
        {label: "Gestión Administrativa y Financiera", value: 16},
        {label: "Gestión Ambiental", value: 17},
        {label: "Gestión de Infraestructura Física", value: 18},
        {label: "Gestión de la Calidad", value: 19},
        {label: "Gestión de la Seguridad y Salud en el Trabajo", value: 20},
        {label: "Gestión de Tecnología y Medios Audiovisuales", value: 21},
        {label: "Gestión del Talento Humano", value: 22},
        {label: "Gestión Documental", value: 23},
        {label: "Gestión Jurídica", value: 24},
        {label: "Graduados", value: 25},
        {label: "Ingreso, Permanencia y Graduación", value: 26},
        {label: "Innovación, Emprendimiento y Transferencia Tecnológica", value: 27},
        {label: "Internacionalización", value: 28},
        {label: "Investigación", value: 29},
        {label: "Laboratorios Facultad Arquitectura e Ingeniería", value: 30},
        {label: "Laboratorios Facultad Ciencias de la Salud", value: 31},
        {label: "Laboratorios Facultad de Administración", value: 32},
        {label: "LACMA", value: 33},
        {label: "Planeación Institucional", value: 34},
        {label: "Presupuesto", value: 35},
        {label: "Presupuesto Participativo", value: 36},
        {label: "Rectoría", value: 37},
        {label: "Secretaria General", value: 38},
        {label: "Tesorería", value: 39},
        {label: "Vicerrectoría Académica", value: 40},
        {label: "Vicerrectoría de Investigación y Extensión", value: 41},
        {label: "Virtualidad", value: 42},
    ],
    
    //lista pendiente por integrar
    lista_pros_presupuestp: [
        {value:1,label:"Bienestar"},
        {value:2,label:"Bienes y Servicios"},
        {value:3,label:"Comunicaciones y Mercadeo"},
        {value:4,label:"Gestión Ambiental Seguridad y Salud en el Trabajo"},
        {value:5,label:"Infraestructura Física"},
        {value:6,label:"Sistema de Gestión Integral - SGI -"},
        {value:7,label:"Tecnología y Medios Audiovisuales"},
        {value:8,label:"Talento Humano"},
        {value:9,label:"Ninguno"}
    ],

    actividades: [
        {value: 1, label: 'Realizar apoyo técnico o profesional'},
        {value: 2, label: 'Realizar apoyo para administración software Investiga y Gestión de la información'},
        {
            value: 3,
            label: 'Realizar apoyo a la gestión estrategíca de grupos de invetigación y el mejoramiento en la categorización de investigadores ante MinCiencias, dirigir el proceso editorial de la revista SINERGIA y apoyar los trámites de certificación de productos de investigació'
        },
        {
            value: 4,
            label: 'Realizar apoyo en temas de protección de la propiedad intelectual y la transferencia tecnológica y de conocimiento de la Institución '
        },
        {value: 5, label: 'Realizar el apoyo administrativo, técnico y logístico al  proceso de Investigación'},
        {value: 6, label: 'Prestar servicios de apoyo educativo dentro del proceso de Investigación '},
        {
            value: 7,
            label: 'Realizar apoyo y acompañamiento en la gestión de los programas del Centro de Emprendimiento, Innovación y trasnferencia tecnologica '
        },
        {
            value: 8,
            label: 'Realizar apoyo para desarrollar la evaluación de Proyectos de investigación, productos, artículos revista y escalafonamiento docente'
        },
        {value: 9, label: 'Apoyar la generacion de productos derivados de proyectos de investigacion '},
        {value: 10, label: 'Prestar los servicios de preparación y presentación de impuestos y tasas para la protección de propiedad intelectual '},
        {
            value: 11,
            label: 'Realizar salidas académicas para presentación de resultados de investigación y  Actividades de trabajo de campo para recolección de datos, toma de muestras, Movilidad aerea'
        },
        {
            value: 12,
            label: 'Realizar salidas académicas para presentación de resultados de investigación y  Actividades de trabajo de campo para recolección de datos, toma de muestras, Movilidad terrestre'
        },
        {
            value: 13,
            label: 'Prestar servicio de hospedaje para las salidas academicas  y  Actividades de trabajo de campo para recolección de datos, toma de muestras'
        },
        {value: 14, label: 'Realizar la compra de equipos requeridos en proyectos de investigación'},
        {value: 15, label: 'Realizar compra de insumos para proyectos de investigación'},
        {value: 16, label: 'Realizar afiliaciones a redes académicas e investigación y pagos de membresía'},
        {value: 17, label: 'Realizar publicaciones y procesos editoriales.'},
        {value: 18, label: 'Realizar  soporte tecnico de los sistemas academicos al proceso de  Admisiones , Registro y Control '},
        {value: 19, label: 'Realizar  apoyo  y asesoria  a estudiantes en los procesos de de  Admisiones , Registro y Control '},
        {value: 20, label: 'Realizar la compra de insumos como diplomas, actas, portadiplomas  para ceremonias de grado de la institución'},
        {
            value: 21,
            label: 'Prestar servicios profesionales para apoyar el logro de las metas de programas e institucional, propuestas en el Proceso de Aseguramiento de la Calidad Académica'
        },
        {value: 22, label: 'Preservar los libros, revistas, colecciones , bases de datos de la biblioteca Institucinal '},
        {value: 23, label: 'Actualizar programas y herramientas digitales de la Biblioteca '},
        {value: 24, label: 'Realizar apoyo técnico y/o profesional al proceso Biblioteca '},
        {value: 25, label: 'Realizar Mantenimiento de equipos (Antenas, Equipos RFID y TAG).'},
        {value: 26, label: 'Desarrollar proyectos, organización y participación en eventos.'},
        {value: 27, label: 'Realizar afiliaciones a redes académicas y pagos de membresía'},
        {value: 28, label: 'Realizar apoyo tecnico, operativo y logistico al proceso de internacionalización'},
        {value: 29, label: 'Realizar movilidad saliente y entrante para estudiantes  que  particpen en  eventos academicos '},
        {value: 30, label: 'Realizar apoyo tecnico y/o profesional en educación artistica y cultural al proceso de Bienestar Institucional'},
        {value: 31, label: 'Realizar apoyo tecnico y/o profesional en la línea de deportes ofertada desde el proceso de Bienestar Institucional'},
        {
            value: 32,
            label: 'Brindar apoyo asistencial a los programas de promoción de la salud y y prevención desarrollados para la comunidad institucional'
        },
        {value: 33, label: 'Realizar apoyo tecnico y/o profesional en la parte administratova y logistica del proceso de Bienestar'},
        {value: 34, label: 'Implementar Programa de seguridad alimentaria'},
        {value: 35, label: 'Implementar servicios de bienestar institucional'},
        {value: 36, label: 'Realizar afiliación anual ASCUN, fortalecimiento de las líneas de desarrollo humano, deporte y cultura.'},
        {value: 37, label: 'Realizar mantenimiento a los implementos de las aulas de Bienestar Institucional'},
        {value: 38, label: 'Realizar traslados de los grupos de deporte, cultura y otras activiades '},
        {value: 39, label: 'Realizar Montaje, diseño y proyección de obras de arte'},
        {value: 40, label: 'Realizar adquisición y mantenimiento de instrumentos musicales '},
        {value: 41, label: 'Desarrollar actividades deportivas y recreativas '},
        {
            value: 42,
            label: 'Realizar envío masivo de correos, sms, IVR y recolección de leads e interesados, usada para comunicación interna y externa con los públicos de interés'
        },
        {
            value: 43,
            label: 'Realizar apoyo en la planeación y desarrollo de estrategias en marketing e imagen en el proceso de Comunicaciones_Bienestar'
        },
        {value: 44, label: 'Implementar el Plan de medios masivos y pauta digital, y fortalecimiento de la página web institucional'},
        {value: 45, label: 'Realizar productos audiovisuales para campañas internas y externas de la Institución.'},
        {value: 46, label: 'Realizar apoyo en diseño gráfico de material publicitario Institucional y diseño de campañas.'},
        {value: 47, label: 'Realizar apoyo en el desarrollo de estrategias de comunicaciones y mercadeo de la Institución'},
        {value: 48, label: 'Implementar la señalética que se debe actualizar en el campus institucional'},
        {value: 49, label: 'Adquirir souvenirs para estretagias institucionales y de comunicaciones'},
        {value: 50, label: 'Realizar compra de equipos para el fortalecimiento del material audiovisual del Proceso de Comunicación y Mercadeo'},
        {
            value: 51,
            label: 'Relizar apoyo profesionales de comunicación y diseño gráfico de los programas y servicios de bienestar a la comunidad institucional. '
        },
        {value: 52, label: 'Implementar la carnetización en la comunidad Institucional'},
        {value: 53, label: 'Realizar apoyo en el desarrollo de estrategias de comunicaciones y mercadeo de la Institución. Virtualidad - '},
        {value: 54, label: 'Implementar las estrategias del Plan de Comunicaciones y Mercadeo Institucional'},
        {
            value: 55,
            label: 'Realizar los  programas de promoción de la salud y y prevención desarrollados para la comunidad institucional por parte del proceso de Bienestar Institucional ( Universidades preventivas)'
        },
        {value: 56, label: 'Realizar apoyo al proceso de Vicerectoria Académica '},
        {value: 57, label: 'Realizar apoyo técnico y/o profesional al Centro de graduados '},
        {value: 58, label: 'Adquirir software para administración y gestión de la bolsa de empleo'},
        {value: 59, label: 'Adquirir placas para los estudiantes de práctica de la institución'},
        {value: 60, label: 'Realizar eventos académicos y de relacionamiento con graduados  y sector productivo '},
        {value: 61, label: 'Realizar pasantias empresariales  para estudiantes y docentes '},
        {value: 62, label: 'Realizar la gestión de la información del Observatorio de Permanencia y Calidad '},
        {value: 63, label: 'Realizar apoyo educativo en el proceso  de ingreso, permanencia y graduación '},
        {
            value: 64,
            label: 'Realizar  apoyo técnico y/o profesional en  producción audiovisual para la grabación, edición y posproduccion de videos y contenido audiovisual que acompañan los cursos '
        },
        {
            value: 65,
            label: 'Realizar apoyo técnico y/o profesional en  diseño gráfico, para la realización de piezas graficas y animaciones del  proceso de Virtualidad'
        },
        {value: 66, label: 'Realizar apoyo técnico y/o profesional al proceso de  Virtualidad de cara al ensamble y diseño instruccional '},
        {value: 67, label: 'Realizar apoyo Profesional  con experiencia en instalación y migración de sistemas o plataformas tipo Moodle'},
        {value: 68, label: 'Realizar apoyo logistico y administrativo en el Centro de Lenguas'},
        {value: 69, label: 'Participar de membresias organizaciones nacionales e internacionales .  Fac. Administración.'},
        {value: 70, label: 'Realizar acompañamiento al desarrollo de las estrategias académicas desarrolladas   de la Fac. Administación'},
        {value: 71, label: 'Realizar apoyo logistico y administrativo en la Facultad de Administración.'},
        {
            value: 72,
            label: 'Realizar la compra de materia prima como alimentos, granos, cereales, proteinas animales y vegetales y elementos de aseo para gastronomia.  Fac. Administración '
        },
        {
            value: 73,
            label: 'Realizar mantenimiento  (preventivo , correctivo)  de las aulas moviles, economato  y laboratorios de los programas de Gastronomia  Fac. Administración. '
        },
        {value: 74, label: 'Realizar salidas académicas regionales y nacionales. Facultad de Administración.'},
        {
            value: 75,
            label: 'Prestar el servicio de acompañamiento a los procesos administrativos y logisticos de la Facultad de Arquitectura e Ingenieria. '
        },
        {value: 76, label: 'Participar de redes y   membresias   Fac. Arquitectura e ingenieria.'},
        {value: 77, label: 'Realizar mantenimiento a los  laboratorios (preventivo , correctivo) , facultad de Arquitectura e Ingenieria'},
        {
            value: 78,
            label: 'Realizar salidas académicas  asociadas a los curriculos de los programas y visitas de  practicas   de la facultad de Arquitectura e Ingenieria'
        },
        {value: 79, label: 'Participar en membresias  u organizaciones académicas . Fac Salud'},
        {value: 80, label: 'Prestar los servicios como ingeniero biomédico para la Facultad de Ciencias de la Salud. '},
        {value: 81, label: 'Prestar los servicios como químico para la Facultad de Ciencias de la Salud. '},
        {
            value: 82,
            label: 'Realizar diagnóstico y asesoría en biotecnología, como parte de las actividades de extensión de la Facultad de  Ciencias de la Salud (biotecnología). '
        },
        {value: 83, label: 'Realizar apoyo operativo y administrativo en los laboratorios  en la  facultad de Ciencias de la Salud'},
        {value: 84, label: 'Realizar calibración de equipos. '},
        {value: 85, label: 'Realizar la compra de insumos, químicos, reactivos y kits de  diagnóstico'},
        {value: 86, label: 'Realizar mantenimiento y reparación de equipos '},
        {value: 87, label: 'Realizar salidas académicas regionales y nacionales. Facultad de Salud'},
        {
            value: 88,
            label: 'Prestar servicio de hospedaje para las salidas academicas  y  Actividades de trabajo de campo para recolección de datos, toma de muestras. Fac sociales'
        },
        {value: 89, label: 'Realizar apoyo técnico y/o profesional a la facultad de Ciencias Sociales y de educación'},
        {value: 90, label: 'Realizar afiliaciones a redes académicas. facultad de Ciencias Sociales y de educación '},
        {
            value: 91,
            label: 'Realizar apoyo al desarrollo de estrategias que favorezcan a los  procesos academicos  de la Facultad de Ciencias Sociales y de Educación'
        },
        {
            value: 92,
            label: 'Realizar movilidad académicas hacia diferentes experiencias representativas para los programas. facultad de Ciencias Sociales y de educación'
        },
        {
            value: 93,
            label: 'Realizar apoyo al desarrollo de estrategias que favorezcan a los procesos académicos de la institución. Vicerrectoría Académica'
        },
        {value: 94, label: 'Realizar mantenimiento y reparación de equipos'},
        {value: 95, label: 'Realizar calibración de equipos. LACMA'},
        {value: 96, label: 'Prestar servicios de LACMA en el Valle de Aburra y municipios cercanos al departamento de Antioquia.'},
        {value: 97, label: 'Participar en ensayos interlaboratorios de calidad externos'},
        {value: 98, label: 'Prestar servicios profesionales en el laboratorio LACMA'},
        {value: 99, label: 'Realizar el apoyo administrativo, técnico y logístico  en el proceso de Tecnologia y medios audiovisuales'},
        {value: 100, label: 'Realizar la dotación de infraestructrua tecnologica requerida'},
        {value: 101, label: 'Realizar mantenimiento de infraestructrua tecnologica requerida'},
        {value: 102, label: 'Dotar y actualizar aplicativos de software de uso institucional'},
        {value: 103, label: 'Realizar la Formulación del plan maestro integral de infraestructura física institucional'},
        {value: 104, label: 'Mejorar Infraestructura física de la Institución Universitaria Colegio Mayor de Antioquia'},
        {value: 105, label: 'Realizar mejoramiento  y adecuación de de las áreas de estudio de los bloques institucionales'},
        {value: 106, label: 'Realizar mejoramiento y recuperación de las zonas verdes de la institución'},
        {value: 107, label: 'Realizar el apoyo administrativo, técnico y logístico al  proceso de Infraestructura Fisica '},
        {value: 108, label: 'Realizar el apoyo profesional al proceso de Infraestructura Fisica '},
        {value: 109, label: 'Realizar la automatización de puertas principales.'},
        {value: 110, label: 'Reserva Realizar la Formulación del plan maestro integral de infraestructura física intitucional'},
        {value: 111, label: 'Realizar adecuaciones electricas para el fortalecimiento de la infraestructura'},
        {value: 112, label: 'Realizar montajes, instalaciones y las adecuaciones  requeridas dentro de la infraestructura física de la institución'},
    ],
    //selects
    categoria: [
        {label: "Area Protegida", value: 1},
        {label: "ARL", value: 2},
        {label: "Bases de Datos", value: 3},
        {label: "Calibraciones", value: 4},
        {label: "Caja menor", value: 5},
        {label: "Contratista", value: 6},
        {label: "Cuota de fiscalizacion", value: 7},
        {label: "Edictos", value: 8},
        {label: "Equipos e intrumentos", value: 9},
        {label: "Estimulos docentes", value: 10},
        {label: "Eventos", value: 11},
        {label: "Exemenes medicos", value: 12},
        {label: "Gastos de viaje alimentacion", value: 13},
        {label: "Gastos de viaje hospedaje", value: 14},
        {label: "Gastos de viaje trasnporte", value: 15},
        {label: "Gastos legales", value: 16},
        {label: "GMF", value: 17},
        {label: "Inscripciones,afiliaciones y renovaciones", value: 18},
        {label: "Insumos", value: 19},
        {label: "Instalaciones y reparaciones", value: 20},
        {label: "Licencias y membresias", value: 21},
        {label: "Mantenimiento", value: 22},
        {label: "Mobiliario", value: 23},
        {label: "Movilidad academica", value: 24},
        {label: "Patentes", value: 25},
        {label: "Plan de capacitacion", value: 26},
        {label: "Plan de comunicación y mercado y plan de medios", value: 27},
        {label: "Plataforma de envío masivo de correos, sms, ivr y recolección de leads e interesados", value: 28},
        {label: "Practicante", value: 29},
        {label: "Publicaciones y procesos editoriales", value: 30},
        {label: "Regionalizacion alimentacion", value: 31},
        {label: "Regionalizacion hospedaje", value: 32},
        {label: "Regionalizacion transporte", value: 33},
        {label: "Seguros y polizas", value: 34},
        {label: "Seguridad alimentaria", value: 35},
        {label: "Servicio de impresoras y de fotocopias", value: 36},
        {label: "Servicio de mensajeria", value: 37},
        {label: "Servicios de auditoria y acreditacion", value: 38},
        {label: "Servicios publicos", value: 39},
        {label: "Software", value: 40},
        {label: "Soporte", value: 41},
        {label: "Souvenirs", value: 42},
        {label: "Subvencion", value: 43},
        {label: "Suministro de comidad y refrigerios", value: 44},
        {label: "Telefoniacelular e internet", value: 45},
        {label: "Viaticos", value: 46},
        {label: "Otra", value: 47}, // data.OpcionOtra
    ],
    vigencias_anteriores: [
        {
            'label': "Si",
            'value': "Si"
        },
        {
            'value': "No",
            'label': "No",
        }
    ],
    desabilitar_vigencias_anteriores: [false],
    //resumen final

    ArrayNecesidad: [],
    ArrayValorTotal: [],
    ConteoCosas: 1,
    total_todo: 0,
    DataRecuperada: false,
    valor_total_solicitatdo_por_necesidad: [],
    Otras_unidad_de_medida: [false],
    Otras_capacidad_instalada: [false],
    Otras_categoria: [true],
    OpcionOtra: 47, //cambiar si aparecen mas opciones

    unidad_de_medida: [],
    //showopciones
    showanexos: [],

    //Way to present info
    classMini: "min-w-[100px] max-h-[110px] p-4",
    classOfTxtAreas: "min-w-[500px] max-h-[110px] p-4",
    classOfText: "min-w-[200px] max-h-[110px] p-4",
    classOfText2: "min-w-[320px] max-h-[110px] p-4",
    classOfTextLG: "min-w-[400px] max-h-[110px] p-4",
    classOfTextXL: "min-w-[600px] max-h-[110px] p-4",
    classOfText_checkbox: "min-w-[480px] max-h-[350px] overflow-y-scroll p-4",
    
    //el checkbox de ninguno
    checkdisabled:[[false,false,false,false]]
});

function borrarARchivo(n){
    setTimeout(() => {
        form.anexos[n] = ''
        data.showanexos[n] = ''
        document.getElementById('anexs' + n).value = ""
    }, 350)
}

const GuardarArchivo = ((n) => {
    if (form.anexos[n]) {
        // form.type = form.anexos.type
        if (form.anexos[n].type == "application/pdf"
            ||form.anexos[n].type == "application/vnd.openxmlformats-officedocument.wordprocessingml.document"
            || form.anexos[n].type == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
            || form.anexos[n].type == "officedocument.wordprocessingml.document"
            || form.anexos[n].type == "officedocument.spreadsheetml.sheet"
        ) {
            var formpeso = (Math.round(form.anexos[n].size / (1024 * 1024))) //MB
            if (formpeso > data.TamanoMAX) {
                alert('El peso del archivo supera las 5MB')
                borrarARchivo(n)
            }
        } else {
            alert('El archivo debe ser un WORD O EXCEL O PDF')
            borrarARchivo(n)
        }
    }else{
        // borrarARchivo(n)
    }
})


let container = ref(null);


// <!--<editor-fold desc="Toogle multipleselections">-->
function toggleSelection1(value, conteoi) {
    const selectedIndex = form.procesos_involucrados[conteoi].indexOf(value);
    if (selectedIndex === -1) {
        form.procesos_involucrados[conteoi].push(value);
    } else {
        form.procesos_involucrados[conteoi].splice(selectedIndex, 1);
    }
}

function isSelected1(value) {
    return form.procesos_involucrados.includes(value);
}

function modoNinguno(conteoi,desabilitar = true){
    data.checkdisabled[conteoi][0] = desabilitar
    data.checkdisabled[conteoi][1] = desabilitar
    data.checkdisabled[conteoi][2] = desabilitar
    data.checkdisabled[conteoi][3] = false
}

function toggleSelection2(value, conteoi) {
    let selectedIndex = form.plan_de_mejoramiento_al_que_apunta_la_necesidad[conteoi].indexOf(value);
    let modoCero = false
    let elcheckbox
    
    //validar que tenga ceros
    if (value === 0 || form.plan_de_mejoramiento_al_que_apunta_la_necesidad[conteoi].includes(0)) {
        modoCero = true
        modoNinguno(conteoi)
    }
    
    //pintar los checkboxes
    if (modoCero) {
        pintarTodoCero(conteoi)
    
        form.plan_de_mejoramiento_al_que_apunta_la_necesidad[conteoi] = [0]
        if (selectedIndex !== -1) {
            form.plan_de_mejoramiento_al_que_apunta_la_necesidad[conteoi].splice(selectedIndex, 1);
        }
    } else {
        elcheckbox = document.getElementById('0_b' + conteoi);
        elcheckbox.checked = false;
    
        if (selectedIndex === -1) {
            form.plan_de_mejoramiento_al_que_apunta_la_necesidad[conteoi].push(value);
        } else {
            form.plan_de_mejoramiento_al_que_apunta_la_necesidad[conteoi].splice(selectedIndex, 1);
        }
    }
    
    //3) desabilitar los otros
    if(!form.plan_de_mejoramiento_al_que_apunta_la_necesidad[conteoi].includes(0)){
        modoNinguno(conteoi,false) //habilitar
    }
}
function pintarTodoCero(conteoi) {
    let elcheckbox
    form.plan_de_mejoramiento_al_que_apunta_la_necesidad[conteoi].forEach((element) => {
        elcheckbox = document.getElementById(element + '_b' + conteoi);
        if (element !== 0)
            elcheckbox.checked = false;
    });
}
function isSelected2(value, conteoi) {
    // return setTimeout(() => {
    //     if (form.plan_de_mejoramiento_al_que_apunta_la_necesidad[conteoi])
    //         return form.plan_de_mejoramiento_al_que_apunta_la_necesidad[conteoi].includes(value)
    // }, 200)
    if (form.plan_de_mejoramiento_al_que_apunta_la_necesidad[conteoi])
            return form.plan_de_mejoramiento_al_que_apunta_la_necesidad[conteoi].includes(value)
}


function toggleSelection3(value, conteoi) {
    const selectedIndex = form.linea_del_plan_desarrollo_al_que_apunta_la_necesidad[conteoi].indexOf(value);
    if (selectedIndex === -1) {
        form.linea_del_plan_desarrollo_al_que_apunta_la_necesidad[conteoi].push(value);
    } else {
        form.linea_del_plan_desarrollo_al_que_apunta_la_necesidad[conteoi].splice(selectedIndex, 1);
    }
}
function isSelected3(value) {
    return form.linea_del_plan_desarrollo_al_que_apunta_la_necesidad.includes(value);
}


function isSelected_riesgo(value) {
    return form.riesgo_de_la_inversion.includes(value);
}
function toggleSelectio_riesgo(value, conteoi) {
    const selectedIndex = form.riesgo_de_la_inversion[conteoi].indexOf(value);
    if (selectedIndex === -1) {
        form.riesgo_de_la_inversion[conteoi].push(value);
    } else {
        form.riesgo_de_la_inversion[conteoi].splice(selectedIndex, 1);
    }
}

// <!--</editor-fold>-->


// <!--<editor-fold desc="on and of functions - localhost">-->
const onContent = () => {
    data.showContent = true
}
const offContent = () => {
    data.showContent = false
    form.identificacion_user = ''
    data.identificacion_disbled = false

}

function esLocalhost() {
    var hostname = window.location.hostname;
    return hostname === 'localhost' || hostname === '127.0.0.1';
}

// if (esLocalhost()) {
//   data.classOfTxtAreas = "min-w-[20px] max-h-[110px] p-4";
//   data.classOfText = "min-w-[110px] max-h-[110px] p-4";
//   data.classOfText2 = "min-w-[120px] max-h-[110px] p-4";
// }

// <!--</editor-fold>-->

function RecuperarInfoGuardada(Formulario) {
    data.DataRecuperada = true
    form.proceso_que_solicita_presupuesto = Formulario[0].proceso_que_solicita_presupuesto
    // form.enviado = 0
    form.valor_total_de_la_solicitud_actual = Formulario[0].valor_total_de_la_solicitud_actual
    data.ConteoCosas = Formulario.length

    Formulario.forEach((element, index) => {

        if (!data.checkdisabled[index]) {
            data.checkdisabled[index] = [false, false, false, false];
        }
    })
    
    setTimeout(() => recuperaform(Formulario), 200)
}

function recuperaform(Formulario) {
    Formulario.forEach((element, index) => {
        form.necesidad[index] = element.necesidad
        form.justificacion[index] = element.justificacion
        form.actividad[index] = element.actividad
        form.categoria[index] = element.categoria
        data.Otras_categoria[index] = !isNaN(element.categoria)
        form.unidad_de_medida[index] = element.unidad_de_medida
        form.cantidad[index] = (element.cantidad)
        form.valor_total_solicitatdo_por_necesidad[index] = element.valor_total_solicitatdo_por_necesidad
        form.periodo_de_inicio_de_ejecucion[index] = element.periodo_de_inicio_de_ejecucion
        form.vigencias_anteriores[index] = element.vigencias_anteriores

        //VALORES DINERO
        form.valor_unitario[index] = plata_format(parseInt(element.valor_unitario))
        form.valor_asignado_en_la_vigencia_anterior[index] = plata_format(parseInt(element.valor_asignado_en_la_vigencia_anterior))

        // <!--<editor-fold desc="multiples">-->
        let mento;
        let eleInteger;
        let checkboxNinguno
        // form.procesos_involucrados = []

        if (element.procesos_involucrados.length) {
            form.procesos_involucrados[index] = []
            element.procesos_involucrados.forEach(proceso => {
                eleInteger = parseInt(proceso)
                data.lista_pros_presupuestp.forEach((item, i) => {
                    if (item.value && item.value === eleInteger) {
                        form.procesos_involucrados[index].push(item.value)
                        checkboxNinguno = document.getElementById(item.value + '_a' + index);
                        if (checkboxNinguno) checkboxNinguno.checked = true;
                    }
                });
            });
        }

        
        if (!data.checkdisabled[index]) {
            data.checkdisabled[index] = [false,false,false,false];
        }
        if (element.plan_de_mejoramiento_al_que_apunta_la_necesidad.length) {
            form.plan_de_mejoramiento_al_que_apunta_la_necesidad[index] = []
            element.plan_de_mejoramiento_al_que_apunta_la_necesidad.forEach(generico => {
                eleInteger = parseInt(generico)
                data.planmejoramientonecesidad.forEach((item, i) => {
                    if (item.value === eleInteger) {//elninguno
                        form.plan_de_mejoramiento_al_que_apunta_la_necesidad[index].push(item.value)// = item.value
                        checkboxNinguno = document.getElementById(item.value + '_b' + index);
                        if (checkboxNinguno) checkboxNinguno.checked = true;
                    }
                });
            });
        }

        if (element.linea_del_plan_desarrollo_al_que_apunta_la_necesidad.length) {
            form.linea_del_plan_desarrollo_al_que_apunta_la_necesidad[index] = []
            element.linea_del_plan_desarrollo_al_que_apunta_la_necesidad.forEach(generico => {
                eleInteger = parseInt(generico)
                data.lineadelplan.forEach((item, i) => {
                    if (item.value === eleInteger) {
                        form.linea_del_plan_desarrollo_al_que_apunta_la_necesidad[index][i] = item.value
                        checkboxNinguno = document.getElementById(item.value + '_c' + index);
                        if (checkboxNinguno) checkboxNinguno.checked = true;
                    }
                });
                //form.linea_del_plan_desarrollo_al_que_apunta_la_necesidad[index].filter(item => item !== null);
            });
        }
       
        //riesgo
        let CheckRiesgo

        if (element.riesgo_de_la_inversion.length) {
            form.riesgo_de_la_inversion[index] = []
            element.riesgo_de_la_inversion.forEach(generico => {
                // eleInteger = parseInt(generico)
                props.losSelect.riesgo_de_la_inversion.forEach((item, inde) => {
                    if (item.value === generico) {
                        form.riesgo_de_la_inversion[index].push(item.value)
                        CheckRiesgo = document.getElementById(inde + '_d' + index);
                        if (CheckRiesgo) CheckRiesgo.checked = true;
                    }
                });
            });
        }

        // <!--</editor-fold>-->


        form.frecuencia_de_uso[index] = element.frecuencia_de_uso
        form.mantenimientos_requeridos[index] = element.mantenimientos_requeridos
        form.riesgo_de_la_inversion[index] = element.riesgo_de_la_inversion

        if (element.capacidad_instalada !== 'No') {
            data.Otras_capacidad_instalada[index] = true
        }
        form.capacidad_instalada[index] = element.capacidad_instalada
        
        if (element.unidad_de_medida !== 'Meses' && element.unidad_de_medida !== 'Unidades') {
            data.Otras_unidad_de_medida[index] = true
        }
        form.unidad_de_medida[index] = element.unidad_de_medida
        data.showanexos[index] = element.anexos
        console.log("=>(Welcome.vue:672) element.anexos", element.anexos);
    });
}

const metodoConThrottle = throttle(() => {
    guardarAutomaticamente();
}, 20000);//el throttle

const AumentarMasUno = () => {
    let tamanoActual = form.necesidad.length;
    let ene = tamanoActual - 1;

    let variables = {
        'necesidad': !!(form.necesidad[ene]),
        'justificacion': !!(form.justificacion[ene]),
        'actividad': !!(form.actividad[ene]),
        'categoria': !!(form.categoria[ene]),
        'unidad de medida': !!(form.unidad_de_medida[ene]),
        'cantidad': !!(form.cantidad[ene]),
        'valor_unitario': !!(form.valor_unitario[ene]) && form.valor_unitario[ene] != "0" && form.valor_unitario[ene] != "$0",
        'periodo de inicio de ejecucion': !!(form.periodo_de_inicio_de_ejecucion[ene]),

        "procesos involucrados" : form.procesos_involucrados[ene].length > 0,
        "plan de mejoramiento" : form.plan_de_mejoramiento_al_que_apunta_la_necesidad[ene].length > 0,
        "linea del plan desarrollo" : form.linea_del_plan_desarrollo_al_que_apunta_la_necesidad[ene].length > 0,
        
        'frecuencia de uso': !!(form.frecuencia_de_uso[ene]),
        'mantenimientos requeridos': !!(form.mantenimientos_requeridos[ene]),
        'capacidad instalada': !!(form.capacidad_instalada[ene]),
        'riesgo de la inversion':!!(form.riesgo_de_la_inversion[ene]),
    }
    let Bandera = false
    for (let nombre in variables) {
        if (!variables[nombre]) {
            alert('Antes de agregar una fila, complete el siguiente campo: '+ nombre)
            Bandera = true
            break;
        }
    }
    
    if(Bandera) return 

    let Bandera2;
    if (!!form.vigencias_anteriores[ene]) {
        if (form.vigencias_anteriores[ene] === 'No') {
            Bandera2 = true //aquiii
        } else {
            Bandera2 = !!(form.valor_asignado_en_la_vigencia_anterior[ene])
        }
    }
    // console.log("=>(Welcome.vue:724) Bandera2", Bandera2);
    
    // let Bandera3;
    // if (!!form.categoria[ene]) {
    //     if (form.categoria[ene] === ) {
    //         Bandera3 = true
    //     } else {
    //         Bandera3 = !!(form.valor_asignado_en_la_vigencia_anterior[ene])
    //     }
    // }

    if(Bandera2){
        metodoConThrottle();
        if (!data.checkdisabled[tamanoActual]) {
            data.checkdisabled[tamanoActual] = [];
        }
        modoNinguno(tamanoActual,false)//habilitar

        AumentarForm(form)
        data.ConteoCosas++
        data.Otras_unidad_de_medida[tamanoActual] = false;
        data.Otras_capacidad_instalada[tamanoActual] = false;
        data.Otras_categoria[tamanoActual] = true;
        data.desabilitar_vigencias_anteriores[tamanoActual] = false;

    }else{
        alert('Falta el valor asignado en la vigencia anterior')
    }
}


function borrarFila(conteoi) {
    if (conteoi !== 0) {

        let EstaSegurBorrar = false
        EstaSegurBorrar = confirm('¿Esta seguro?');
        if (EstaSegurBorrar) {
            BorraFila(conteoi)
        }
    } else {
        alert('No se puede borrar la final inicial')
    }
}

function BorraFila(index) {
    form.necesidad.splice(index,1)
    form.justificacion.splice(index,1)
    form.actividad.splice(index,1)
    form.categoria.splice(index,1)
    form.unidad_de_medida.splice(index,1)
    form.cantidad.splice(index,1)
    form.valor_unitario.splice(index,1)
    form.valor_total_solicitatdo_por_necesidad.splice(index,1)
    form.periodo_de_inicio_de_ejecucion.splice(index,1)
    form.vigencias_anteriores.splice(index,1)
    form.valor_asignado_en_la_vigencia_anterior.splice(index,1)
    //valoresmultiples
    form.procesos_involucrados.splice(index,1)
    form.plan_de_mejoramiento_al_que_apunta_la_necesidad.splice(index,1)
    form.linea_del_plan_desarrollo_al_que_apunta_la_necesidad.splice(index,1)
    form.frecuencia_de_uso.splice(index,1)
    form.mantenimientos_requeridos.splice(index,1)
    form.riesgo_de_la_inversion.splice(index,1)
    data.Otras_capacidad_instalada.splice(index,1)
    form.capacidad_instalada.splice(index,1)
    form.unidad_de_medida.splice(index,1)

    data.checkdisabled.splice(index,1)
    

    data.ConteoCosas--
}


// <!--<editor-fold desc="watches and handles">-->
watchEffect(() => {
    if (data.ConteoCosas > 101) data.ConteoCosas = 100
    //calcular la suma de los valores unitarios
    if (form.valor_unitario) {

        if (form.cantidad && form.cantidad.length > 0 && form.valor_unitario.length > 0) {
            data.total_todo = 0
            form.cantidad.forEach(function (canti, index) {
                try {
                    let INTvalunitario = parseInt(form.valor_unitario[index].toString().replace(/\$|\./g, ''))
                    let INTcanti = parseFloat(canti)
                    if (!isNaN(INTcanti) && !isNaN(INTvalunitario)) {
                        form.valor_total_solicitatdo_por_necesidad[index] = INTvalunitario * INTcanti
                        data.valor_total_solicitatdo_por_necesidad[index] = number_format(form.valor_total_solicitatdo_por_necesidad[index], 0, 1)

                    } else {
                        form.valor_total_solicitatdo_por_necesidad[index] = 0
                        data.valor_total_solicitatdo_por_necesidad[index] = 0
                    }

                    
                } catch (exceptionVar) {
                    form.valor_total_solicitatdo_por_necesidad[index] = 0
                    data.valor_total_solicitatdo_por_necesidad[index] = 0
                } finally {
                    data.total_todo += form.valor_total_solicitatdo_por_necesidad[index]
                }
            });
        }
    }
})


function handleCantidad(conteoi) {
    let conteoString = form.cantidad[conteoi]+''
    let canti = (conteoString.replace(/[$,]/g, ''))
    if((canti.match(/\./g) || []).length > 1){
        form.cantidad[conteoi] = 0
    }
    canti = parseFloat(canti)
    
    if (canti > 9_999_999 || canti < 0) form.cantidad[conteoi] = 0
    if(isNaN(canti) && (canti > 9000000 || canti < 0)) form.cantidad[conteoi] = 0

}

function tieneCaracteresNoNumericos(valor) {
    const regex = /^[0-9.$]+$/;
    return !regex.test(valor);
}

function tieneCerosIzq(valor) {
    const regex = /^\$?([1-9][0-9]*|[1-9][0-9]*(\.[0-9]+)*)$/;
    return !regex.test(valor);
}

function handledinero(conteoi) {
    if (tieneCaracteresNoNumericos(form.valor_unitario[conteoi])) form.valor_unitario[conteoi] = 0;
    // if (tieneCerosIzq(form.valor_unitario[conteoi])) form.valor_unitario[conteoi].replace(/^$0+/, '$');

    let value = form.valor_unitario[conteoi].toString().replace(/[$,.]/g, '');
    let dinerou = parseInt(value)
    if (dinerou > 9_999_999_999_999 || dinerou < 0) form.valor_unitario[conteoi] = 0
    
    form.valor_unitario[conteoi] = plata_format(form.valor_unitario[conteoi])
}

function handledinerVigAnt(conteoi) {
    let value = form.valor_asignado_en_la_vigencia_anterior[conteoi].toString().replace(/\$|\./g, '');
    let dinerou = parseInt(value)
    if (dinerou > 9_999_999_999_999 || dinerou < 0) form.valor_asignado_en_la_vigencia_anterior[conteoi] = 0
    
    form.valor_asignado_en_la_vigencia_anterior[conteoi] = plata_format(form.valor_asignado_en_la_vigencia_anterior[conteoi])
}

watch(() => form.vigencias_anteriores, (NuevaVig) => {
    NuevaVig.forEach((element, index) => {
        if (element === 'No' || element === 'no') {
            
            data.desabilitar_vigencias_anteriores[index] = true
            form.valor_asignado_en_la_vigencia_anterior[index] = 0
        } else {
            data.desabilitar_vigencias_anteriores[index] = false
        }
    })
}, {deep: true});

watch(() => form.actividad, (nuevaActi) => {
    let valorMaxActividad = 1200
    nuevaActi.forEach((element, index) => {
        if (element.length > valorMaxActividad) {
            form.actividad[index] = form.actividad[index].substring(0,valorMaxActividad)
        } 
    })
}, {deep: true});

watch(() => form.capacidad_instalada, (nuevoVal) => {
    let valorMax = 1200
    nuevoVal.forEach((element, index) => {
        if (element.length > valorMax) {
            form.capacidad_instalada[index] = form.capacidad_instalada[index].substring(0, valorMax)
        }
    })
}, {deep: true});


function buscarEnProps(cedulaBuscada, elprops) {
    for (let cedula in elprops) {
        cedula = parseInt(cedula)
        if (cedula === cedulaBuscada) {
            return true;
        }
    }
    return false;
}

watch(() => form.unidad_de_medida, (nuevaUnidaddeMedidad) => {
    watchunidad(nuevaUnidaddeMedidad)
}, {deep: true});

const watchunidad = (nuevaUnidaddeMedidad) => {
    nuevaUnidaddeMedidad.forEach((element, index) => {
        if (element === 'Otras') {
            data.Otras_unidad_de_medida[index] = true
            form.unidad_de_medida[index] = ''
        }
    });
}

watch(() => form.capacidad_instalada, (nuevx) => {
    nuevx.forEach((element, index) => {
        if (element === 'Si, ¿Cual?') {
            data.Otras_capacidad_instalada[index] = true
            form.capacidad_instalada[index] = ''
        }else{
            if(element === 'No' || element === 'no'){
                form.capacidad_instalada[index] = 'No'
                data.Otras_capacidad_instalada[index] = false
            }
        }
    });
}, {deep: true});

watch(() => form.categoria, (nuevx) => {
    
    nuevx.forEach((element, index) => {
        if (!isNaN(element) && element == data.OpcionOtra) {
            data.Otras_categoria[index] = false
            form.categoria[index] = ''
        }else{
            if(element === 'No' || element === 'no'){
                // form.categoria[index] = 'No'
                data.Otras_categoria[index] = true
            }
        }
    });
}, {deep: true});

watch(() => data.ConteoCosas, (nuev) => {
    let tamanoActual = form.necesidad.length;
    if (nuev > tamanoActual) {
        for (let i = tamanoActual; i < nuev; i++) {
            AumentarForm(form)
        }
    } else if (nuev < tamanoActual) {
        DisminuirForm(form, nuev)
    }
    //fin cambio de tamano


    for (let i = tamanoActual; i <= nuev; i++) {
        data.Otras_unidad_de_medida[i] = false;
        data.Otras_capacidad_instalada[i] = false;
        data.desabilitar_vigencias_anteriores[i] = false;
    }
});

// <!--</editor-fold>-->

function llamarString() {
    if (form.identificacion_user > 1000) {
        router.reload({
            data: {identUser: form.identificacion_user},
            only: ['cedLideres', 'cedLideresGuardados'],
            onSuccess: () => {
                setTimeout(() => {
                    handleEnterCedula2()
                }, 600)
            },
            onError: (error) => {
                console.log('%cError en la coneccion al servidor:' + error, 'color: red; font-size: 33px; font-weight: bold;');
            }
        });
    }
}

const handleEnterCedula = () => {
    llamarString()
}

const handleEnterCedula2 = () => {
    if (!data.identificacion_disbled) {
        if (buscarEnProps(form.identificacion_user, props.cedLideres)) {
            data.nombreLider = props.cedLideres[form.identificacion_user]
            if (data.nombreLider) {
                onContent(); //emitir mensaje y mostrar demas del form
                data.identificacion_disbled = true
                data.colorDisabled = 'bg-zinc-400 text-white dark:bg-black'
            }
        } else {

            if (buscarEnProps(form.identificacion_user, props.cedLideresGuardados)) {
                data.nombreLider = props.cedLideresGuardados[form.identificacion_user]
                data.identificacion_disbled = true
                onContent(); //emitir mensaje y mostrar demas del form

                setTimeout(() => {
                    RecuperarInfoGuardada(props.cedLideresGuardados[form.identificacion_user]['Formulario'])
                }, 400)
            } else {
                if (buscarEnProps(form.identificacion_user, props.cedLideresDiligenciados)) {
                    data.mensajeYaHaSidoGuardado = "Si requiere realizar algún tipo de modificación o corrección por favor comunicarse con el proceso de Presupuesto o Planeación."
                    // alert(data.mensajeYaHaSidoGuardado)
                    data.identificacion_disbled = true
                } else {
                    offContent();
                    data.identificacion_disbled = false //desbloquea la escritura en la cedula
                }
            }
        }
    }

}


// <!--<editor-fold desc="gguardados">-->
const guardarAutomaticamente = () => {
    form.enviado = 0
    form.valor_total_de_la_solicitud_actual = data.total_todo

    if (form.valor_total_de_la_solicitud_actual)
        if (data.ConteoCosas > 0) {
            form.post(route('formulario.store'), {
                preserveScroll: true,
                forceFormData: true,
                onSuccess: () => null,
                onError: () => {
                    alert('Hay errores en algunos campos')
                },
                onFinish: () => null,
            })
        } else {
            alert('¡Faltan datos!');
        }
}

const guardar = () => {
    form.enviado = 0
    form.valor_total_de_la_solicitud_actual = data.total_todo
    if (data.ConteoCosas > 0) {
        form.post(route('formulario.store'), {
            preserveScroll: true,
            forceFormData: true,
            onSuccess: () => {
                form.reset()
                offContent()
                setTimeout(() => {
                    window.location.reload()
                }, 2000)
            },
            onError: () => {
                alert('Hay campos faltantes!')
            },
            onFinish: () => null,
        })
    } else {
        alert('¡Faltan datos!');
    }
}

const create = (validator, second) => {
    if (validator && second) {
        form.enviado = 1
        let EstaSeguro, HayElementosVacios = true
        EstaSeguro = confirm('¿Esta seguro? Después de enviar no podrá hacer más ajustes');

        form.necesidad.forEach((element) => {
            if (!element || element === '' || element === ' ') {
                HayElementosVacios = false
            }
        });
        
        form.valor_total_de_la_solicitud_actual = data.total_todo
        if (EstaSeguro) {
            if (HayElementosVacios) {
                if (data.ConteoCosas > 0 && form.valor_total_de_la_solicitud_actual > 0) {
                    form.post(route('EnviarFormulario'), {
                        preserveScroll: true,
                        forceFormData: true,
                        onSuccess: () => {
                            form.reset()
                            offContent()
                            setTimeout(() => {
                                window.location.reload()
                            }, 2000)
                        },
                        onError: () => {
                            alert('¡Hay campos faltantes!')
                        },
                        onFinish: () => null,
                    })
                } else {
                    alert('¡Faltan datos!');
                }
            }else{
                alert('¡Hay muchos elementos vacios!');
            }
        }
    }
}
// <!--</editor-fold>-->

</script>

<template>
    <div>
        <Head :title="lang().label.welcome"/>

        <div class="relative flex justify-center min-h-screen bg-gray-300 dark:bg-gray-900 text-gray-900 dark:text-gray-100 items-center sm:pt-0">
            <section class="items-center w-full m-10 lg:mx-20 xl:mx-40 sm:px-6 lg:px-8 2xl:px-auto">
        <Toast :flash="$page.props.flash" class=""/>
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
                <form v-if="!TodosDiligenciados" @submit.prevent="create" method="POST" enctype="multipart/form-data"
                      class="py-4">
                    <div class="mx-auto container max-w-[2100px] rounded-2xl">
                        <section class="bg-gradient-to-r from-gray-300 to-gray-400 dark:from-gray-600 dark:to-gray-900 space-y-6 rounded-2xl">
                            <div class="md:inline-flex space-y-4 md:space-y-0 w-full p-4 text-gray-700 dark:text-gray-200 items-center">
                                <h2 v-show="!data.showContent" class="md:w-1/2 max-w-sm mx-auto dark:text-gray-100 2xl:text-xl">Por favor, digite el
                                    numero de documento para empezar:
                                </h2>
                                <div class="md:w-2/3 max-w-lg mx-auto">
                                    <label class="text-md text-gray-400"></label>
                                    <div v-show="!data.showContent" class="w-full inline-flex my-6">
                                        <div class="pt-2 w-1/12 bg-gray-100 bg-opacity-50 rounded-l-lg">
                                            <svg fill="none" class="w-6 text-gray-400 mx-auto dark:text-black" viewBox="0 0 24 24"
                                                 stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>
                                        <input v-model="form.identificacion_user" type="number" min="0"
                                               max="999999999999"
                                               :disabled="data.identificacion_disbled"
                                               :class="data.colorDisabled"
                                               @keyup.enter="handleEnterCedula"
                                               @keydown.enter.prevent="create"
                                               class="dark:text-white w-11/12 focus:text-gray-700 p-2 border-0 dark:bg-gray-700 bg-gray-100 bg-opacity-50 rounded-r-lg
                                 focus:border-blue-400 placeholder-gray-500 text-gray-700 focus:placeholder-gray-400 dark:focus:placeholder-gray-100
                                 focus:bg-blue-50 focus:shadow-outline"
                                               placeholder="Escriba aqui su identificacion"
                                        />
                                        <InputError class="mt-2" :message="form.errors.identificacion_user"/>
                                    </div>
                                    <div v-if="data.showContent" class="w-full grid grid-cols-1 gap-1 my-1 rounded-lg">
                                        <!--                                        <div class="px-4 py-1 w-full rounded-x-lg">-->
                                        <!--                                          <div class="px-4 py-2 w-full bg-transparent rounded-x-lg">-->
                                        <!--                                              ¿Cuántas Necesidades?-->
                                        <!--                                          </div>-->
                                        <!--                                          <input v-model="data.ConteoCosas" type="number" min="0" max="100"-->
                                        <!--                                                 class="dark:bg-black w-full mx-1 bg-zinc-200 text-black dark:text-white font-mono ring-1 ring-zinc-400 focus:ring-1 focus:ring-sky-300 outline-none duration-300 placeholder:text-black placeholder:opacity-50 rounded-md px-4 py-2 shadow-md focus:shadow-lg focus:shadow-sky-200 dark:shadow-md dark:shadow-purple-500"-->
                                        <!--                                                 placeholder="Escriba aqui su identificacion"-->
                                        <!--                                          />-->
                                        <!--                                        </div>-->
                                        <div class="col-span-2 px-4 py-2 w-full bg-transparent rounded-x-lg mt-9">
                                            <p class="ml-8 font-bold my-2 text-lg">Proceso que solicita el Presupuesto</p>
                                            <SelectInput v-model="form.proceso_que_solicita_presupuesto"
                                                         :dataSet="data.proceso_que_solicita_presupuesto"
                                                         class="w-full mx-1 bg-zinc-200 text-black dark:text-white font-mono ring-1 ring-zinc-400 focus:ring-1 focus:ring-sky-300 outline-none duration-300 placeholder:text-black placeholder:opacity-50 rounded-md px-4 py-2 shadow-md focus:shadow-lg focus:shadow-sky-200 dark:shadow-md dark:shadow-purple-500"
                                                         autocomplete="off" placeholder="Especifique"/>
                                            <InputError class="mt-2" :message="form.errors.proceso_que_solicita_presupuesto"/>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div v-if="data.showContent" class="mx-auto text-center">
                                <div
                                    class="bg-my-auto mb-4 md:inline-flex max-w-md w-full space-y-2 py-4 px-8 text-gray-700 dark:text-white items-center">
                                    <h2 class="w-full mx-auto font-bold">Nombre del líder</h2>
                                    <div class="w-full md:pl-9 max-w-md mx-auto space-y-5 md:inline-flex pl-2">
                                        <div class="w-full inline-flex border-sky-600 border-b-2">
                                            <label> {{ data.nombreLider['name'] }} </label>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="bg-my-auto mb-4 md:inline-flex max-w-md w-full space-y-2 py-4 px-8 text-gray-700 dark:text-white items-center">
                                    <h2 class="max-w-lg mx-auto font-bold">Cédula</h2>
                                    <div class="w-full md:pl-9 max-w-md mx-auto space-y-5 md:inline-flex pl-2">
                                        <div class="w-full inline-flex border-sky-600 border-b-2">
                                            <label> {{ data.nombreLider['email'] }} </label>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <hr class="mb-4"/>
                            <div v-for="(indexFila,conteoi) in data.ConteoCosas" v-show="data.showContent" name="fade">
                                <div>
                                    <div class="md:inline-flex  space-y-2 md:space-y-0 w-full p-4 text-gray-700 items-center">
                                        <div ref="container"
                                            class="flex gap-8 w-full mx-auto overflow-x-scroll min-h-[370px] bg-gray-300 dark:bg-gray-600 shadow-lg rounded-2xl"
                                            :class="{'border-t border-red-600' : indexFila === data.ConteoCosas}"
                                        >

<!--                                            <div class="min-w-[260px] max-h-[110px] p-4">-->
<!--                                                <button @click="scrollToEnd" class="bg-blue-400">Desplazar a la derecha</button>-->
<!--                                            </div>-->

                                            
                                            <div :class="data.classOfTxtAreas">
                                                    <!--                          {{ conteoi + 1 }}-->
                                                 <label class="text-md text-gray-900 font-extrabold"> Necesidad </label>
                                                <p class="text-md text-gray-700 mb-12">
                                                    Describir de manera detallada la necesidad a solicitar<br>
                                                    Ejemplo: Se requiere contratista de apoyo con experiencia en atención al cliente
                                                </p>
                                                <div class="w-full inline-flex">
                                                    <textarea cols="100" rows="7" v-model="form.necesidad[conteoi]" @blur="metodoConThrottle"
                                                              type="text" @keydown.enter.prevent="create"
                                                              class="w-full bg-zinc-200 text-black dark:text-white dark:bg-black font-mono ring-1 ring-zinc-400 focus:ring-1 focus:ring-sky-300 outline-none duration-300 placeholder:text-black placeholder:opacity-50 rounded-md px-4 py-2 shadow-md focus:shadow-lg focus:shadow-sky-200 dark:shadow-md dark:shadow-purple-500"
                                                              autocomplete="off"/>
                                                </div>
                                                <InputError class="mt-2" :message="form.errors.necesidad"/>
                                            </div>
                                            <div :class="data.classOfTxtAreas">
                                                 <label class="text-md text-gray-900 font-bold">Justificación (especificar de manera concreta la respuesta)</label>
                                                <p class="text-md text-gray-700">
                                                    Tener en cuenta: ¿Qué impactos concretos tendrá? y ¿Qué pasaría si no se hace?.<br>
                                                    Si es talento humano, detallar sus actividades contractuales
                                                </p>
                                                <div class="w-full inline-flex">
                                          <textarea cols="100" rows="7" v-model="form.justificacion[conteoi]" @blur="metodoConThrottle"
                                                    type="text" @keydown.enter.prevent="create"
                                                    class="w-full bg-zinc-200 text-black dark:text-white dark:bg-black font-mono ring-1 ring-zinc-400 focus:ring-1 focus:ring-sky-300 outline-none duration-300 placeholder:text-black placeholder:opacity-50 rounded-md px-4 py-2 shadow-md focus:shadow-lg focus:shadow-sky-200 dark:shadow-md dark:shadow-purple-500"
                                                    autocomplete="off"/>
                                                    <InputError class="mt-2" :message="form.errors.justificacion"/>
                                                </div>
                                            </div>
                                            <div :class="data.classOfTextXL">
                                                 <label class="text-md text-gray-900 font-bold">Actividad</label>
                                                <p class="text-md text-gray-700">
                                                    Relacionar en este campo la actividad a desarrollar.<br>
                                                    Tener en cuenta el <b>archivo adjunto</b> donde encontrará el listado de las actividades de
                                                    inversión y funcionamiento con su código; copiar y pegar en este campo la actividad
                                                    seleccionada acorde a la necesidad.<br>
                                                    En caso de no encontrar una actividad relacionada a su necesidad, escribirla de manera
                                                    concreta
                                                </p>
                                                <div class="w-full inline-flex">
                                                    <!--                                                  <SelectInput v-model="form.actividad[conteoi]"-->
                                                    <!--                                                           :dataSet="data.actividades"-->
                                                    <!--                                                           class="w-full bg-zinc-200 text-black dark:text-white font-mono ring-1 ring-zinc-400 focus:ring-1 focus:ring-sky-300 outline-none duration-300 placeholder:text-black placeholder:opacity-50 rounded-md px-4 py-2 shadow-md focus:shadow-lg focus:shadow-sky-200 dark:shadow-md dark:shadow-purple-500"-->
                                                    <!--                                                           />-->
                                                    <textarea cols="100" rows="7" v-model="form.actividad[conteoi]" @keydown.enter.prevent="create" @blur="metodoConThrottle"
                                                           type="text"
                                                           class="w-full bg-zinc-200 text-black dark:text-white dark:bg-black font-mono ring-1 ring-zinc-400 focus:ring-1 focus:ring-sky-300 outline-none duration-300 placeholder:text-black placeholder:opacity-50 rounded-md px-4 py-2 shadow-md focus:shadow-lg focus:shadow-sky-200 dark:shadow-md dark:shadow-purple-500"
                                                           autocomplete="off"/>
                                                    <InputError class="mt-2" :message="form.errors.actividad"/>
                                                </div>
                                            </div>
                                            <div :class="data.classOfTextLG">
                                                 <label class="text-md text-gray-900 font-bold capitalize">categoría</label>
                                                <p class="text-md text-gray-700">
                                                    Seleccionar la categoría acorde a la necesidad
                                                </p>
                                                <div v-if="data.Otras_categoria[conteoi]" class="w-full inline-flex">
                                                    <SelectInput v-model="form.categoria[conteoi]" @keydown.enter.prevent="create"
                                                                 :dataSet="data.categoria"
                                                                 class="w-full bg-zinc-200 text-black dark:text-white font-mono ring-1 ring-zinc-400 focus:ring-1 focus:ring-sky-300 outline-none duration-300 placeholder:text-black placeholder:opacity-50 rounded-md px-4 py-2 shadow-md focus:shadow-lg focus:shadow-sky-200 dark:shadow-md dark:shadow-purple-500"
                                                                 autocomplete="off"/>
                                                    <InputError class="mt-2" :message="form.errors.categoria"/>
                                                </div>
                                                <div v-else class="w-full inline-flex">
                                                    <input
                                                        v-model="form.categoria[conteoi]" @keydown.enter.prevent="create"
                                                        @blur="metodoConThrottle"
                                                        type="text"
                                                        class="w-full bg-zinc-200 text-black dark:text-white dark:bg-black font-mono ring-1 ring-zinc-400 focus:ring-1 focus:ring-sky-300 outline-none duration-300 placeholder:text-black placeholder:opacity-50 rounded-md px-4 py-2 shadow-md focus:shadow-lg focus:shadow-sky-200 dark:shadow-md dark:shadow-purple-500"
                                                        />
                                                    <InputError class="mt-2" :message="form.errors.categoria"/>
                                                </div>
                                            </div>
                                            <div :class="data.classOfText">
                                                 <label class="text-md text-gray-900 font-bold capitalize">unidad de medida</label>
                                                <p class="text-md text-gray-700">
                                                    Seleccionar la forma de cuantificar el recurso solicitado
                                                </p>
                                                <div class="w-full inline-flex">
                                                    <SelectInput v-model="form.unidad_de_medida[conteoi]" @keydown.enter.prevent="create"
                                                                 @blur="metodoConThrottle"
                                                                 :dataSet="props.losSelect.unidad_de_medida"
                                                                 class="w-full bg-zinc-200 text-black dark:text-white font-mono ring-1 ring-zinc-400 focus:ring-1 focus:ring-sky-300 outline-none duration-300 placeholder:text-black placeholder:opacity-50 rounded-md px-4 py-2 shadow-md focus:shadow-lg focus:shadow-sky-200 dark:shadow-md dark:shadow-purple-500"
                                                                 autocomplete="off"/>

                                                    <InputError class="mt-2" :message="form.errors.unidad_de_medida"/>
                                                </div>
                                            </div>
                                            <div :class="data.classOfText">
                                                 <label class="text-md text-gray-900 font-bold capitalize">cantidad</label>
                                                <p class="text-md text-gray-700">Registrar la cantidad en coherencia con la
                                                    unidad de medida
                                                    seleccionada (utilize punto para cifras decimales)</p>
                                                <div class="w-full inline-flex">
                                                    <input v-model="form.cantidad[conteoi]"
                                                           @keydown.enter.prevent="create" @blur="metodoConThrottle"
                                                           type="text" 
                                                           onkeypress="return (event.charCode >= 48 && event.charCode <= 57 || event.charCode === 46)"
                                                           step="0.1"
                                                           @input="handleCantidad(conteoi)"
                                                           class="w-full bg-zinc-200 text-black dark:text-white dark:bg-black font-mono ring-1 ring-zinc-400 focus:ring-1 focus:ring-sky-300 outline-none duration-300 placeholder:text-black placeholder:opacity-50 rounded-md px-4 py-2 shadow-md focus:shadow-lg focus:shadow-sky-200 dark:shadow-md dark:shadow-purple-500"
                                                           autocomplete="off"/>
                                                    <InputError class="mt-2" :message="form.errors.cantidad"/>
                                                </div>
                                            </div>
                                            <div class="min-w-[360px] max-h-[110px] p-4">
                                                 <label class="text-md text-gray-900 font-bold capitalize">valor unitario</label>
                                                <p class="text-md text-gray-700">
                                                    Registrar el valor unitario.<br>
                                                    En caso de ser en dólares, usar el valor proyectado de la TRM según la fecha estimada de
                                                    compra
                                                </p>
                                                <div class="w-full inline-flex">
                                                    <input v-model="form.valor_unitario[conteoi]" @keydown.enter.prevent="create"
                                                           @blur="metodoConThrottle"
                                                           @input="handledinero(conteoi)"
                                                           onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"
                                                           type="text"
                                                           class="w-full bg-zinc-200 text-black dark:text-white dark:bg-black font-mono ring-1 ring-zinc-400 focus:ring-1 focus:ring-sky-300 outline-none duration-300 placeholder:text-black placeholder:opacity-50 rounded-md px-4 py-2 shadow-md focus:shadow-lg focus:shadow-sky-200 dark:shadow-md dark:shadow-purple-500"
                                                           autocomplete="off"
                                                    />
                                                    <InputError class="mt-2" :message="form.errors.valor_unitario"/>
                                                </div>
                                            </div>
                                            <div class="min-w-[360px] max-h-[110px] p-4">
                                                 <label class="text-md text-gray-900 font-bold capitalize">valor total</label>
                                                <p class="text-md text-gray-700">Valor generado automáticamente</p>
                                                <div class="w-full inline-flex">
                                                    <input disabled @keydown.enter.prevent="create" @blur="metodoConThrottle"
                                                           v-model="data.valor_total_solicitatdo_por_necesidad[conteoi]"
                                                           class="w-full bg-zinc-400 text-black dark:text-white dark:bg-black font-mono ring-1 ring-zinc-400 focus:ring-1 focus:ring-sky-300 outline-none duration-300 placeholder:text-black placeholder:opacity-50 rounded-md px-4 py-2 shadow-md focus:shadow-lg focus:shadow-sky-200 dark:shadow-md dark:shadow-purple-500"
                                                           autocomplete="off"/>
                                                </div>
                                            </div>

                                            <div :class="data.classOfText2">
                                                 <label class="text-md text-gray-900 font-bold">Período de inicio de ejecución</label>
                                                <p class="text-md text-gray-700">Seleccionar el cuatrimestre en el que iniciará la ejecución</p>
                                                <div class="w-full inline-flex">
                                                    <SelectInput @keydown.enter.prevent="create"
                                                                 :dataSet="props.losSelect.periodo_de_inicio_de_ejecucion" @blur="metodoConThrottle"
                                                                 v-model="form.periodo_de_inicio_de_ejecucion[conteoi]"
                                                                 type="text"
                                                                 class="w-full bg-zinc-200 text-black dark:text-white font-mono ring-1 ring-zinc-400 focus:ring-1 focus:ring-sky-300 outline-none duration-300 placeholder:text-black placeholder:opacity-50 rounded-md px-4 py-2 shadow-md focus:shadow-lg focus:shadow-sky-200 dark:shadow-md dark:shadow-purple-500"
                                                                 autocomplete="off"/>
                                                    <InputError class="mt-2"
                                                                :message="form.errors.periodo_de_inicio_de_ejecucion"/>
                                                </div>
                                            </div>
                                            <div :class="data.classOfText">
                                                 <label class="text-md text-gray-900 font-bold capitalize">vigencia anterior</label>
                                                <p class="text-md text-gray-700">Seleccionar si esta necesidad fue financiada en
                                                    la vigencia anterior</p>
                                                <div class="w-full inline-flex">
                                                    <SelectInput :dataSet="data.vigencias_anteriores" @blur="metodoConThrottle"
                                                                 v-model="form.vigencias_anteriores[conteoi]" @keydown.enter.prevent="create"
                                                                 type="text"
                                                                 class="w-full bg-zinc-200 text-black dark:text-white font-mono ring-1 ring-zinc-400 focus:ring-1 focus:ring-sky-300 outline-none duration-300 placeholder:text-black placeholder:opacity-50 rounded-md px-4 py-2 shadow-md focus:shadow-lg focus:shadow-sky-200 dark:shadow-md dark:shadow-purple-500"
                                                    />
                                                    <InputError class="mt-2"
                                                                :message="form.errors.vigencias_anteriores"/>
                                                </div>
                                            </div>
                                            <div :class="data.classOfText2">
                                                 <label class="text-md text-gray-900 font-bold">Valor asignado en la vigencia anterior</label>
                                                <p class="text-md text-gray-700">Si la necesidad fue financiada en la vigencia
                                                    anterior, especificar el
                                                    valor en pesos que le fue asignado</p>
                                                <div class="w-full inline-flex">
                                                    <input
                                                        v-model="form.valor_asignado_en_la_vigencia_anterior[conteoi]" @keydown.enter.prevent="create"
                                                        @blur="metodoConThrottle"
                                                        @input="handledinerVigAnt(conteoi)"

                                                        type="text"
                                                        class="w-full bg-zinc-200 text-black dark:text-white dark:bg-black font-mono ring-1 ring-zinc-400 focus:ring-1 focus:ring-sky-300 outline-none duration-300 placeholder:text-black placeholder:opacity-50 rounded-md px-4 py-2 shadow-md focus:shadow-lg focus:shadow-sky-200 dark:shadow-md dark:shadow-purple-500"
                                                        :disabled="data.desabilitar_vigencias_anteriores[conteoi]"/>
                                                    <InputError class="mt-2" :message="form.errors.valor_asignado_en_la_vigencia_anterior"/>
                                                </div>
                                            </div>

                                            <!--                                        selectmultipledos1-->
                                            <div :class="data.classOfText_checkbox">
                                                <label for="multi-select" class="mb-4 text-md text-gray-900 font-bold">Procesos articulados</label>
                                                <p class="text-md text-gray-700">Seleccionar el(los) proceso(s) de los cuales se
                                                    requiere apoyo,
                                                    información, asesoría,
                                                    recursos o aprobaciones para la asignación de recursos</p>
                                                <div v-for="(option,inde) in data.lista_pros_presupuestp"
                                                     :key="inde" class="mt-3">
                                                    <input type="checkbox" :id="option.value+'_a'+conteoi"
                                                           :value="option.value" @keydown.enter.prevent="create"
                                                           @change="toggleSelection1(option.value,conteoi)"
                                                           :checked="isSelected1(option.value)"
                                                           @blur="metodoConThrottle"
                                                           class="p-1 w-7 h-7 ring-1 ring-zinc-400"
                                                    />
                                                    <label :for="option.value" class="mx-2">{{ option.label }}</label>
                                                </div>
                                                <InputError class="mt-2" :message="form.errors.procesos_involucrados"/>
                                            </div>
                                            <div class="min-w-[450px] max-h-[250px] p-4 border-x-2 border-zinc-400">
                                                <label for="multi-select" class="mb-4  text-md text-blue-900 font-bold">Procesos seleccionados</label>
                                                <p v-if="form.procesos_involucrados[conteoi]"
                                                   v-for="itemsito in form.procesos_involucrados[conteoi]"
                                                   class="font-bold mt-1">
                                                    - {{ data.lista_pros_presupuestp.find((item) => item.value === itemsito)?.label }}
                                                </p>
                                            </div>


                                            <!--                                            selectmultipledos2-->


                                            <div class="min-w-[450px] max-h-[250px] p-4">
                                                <label class="text-md text-gray-900 font-bold">Plan de Mejoramiento y Mantenimiento -PMM- al que
                                                    apunta la
                                                    necesidad</label>
                                                <p class="text-md text-gray-700">
                                                    Seleccionar el PMM
                                                </p>
                                                <div v-for="(option,inde) in data.planmejoramientonecesidad"
                                                     :key="option.value" class="mt-3">
                                                    <input type="checkbox" :id="option.value+'_b'+conteoi"
                                                           :value="option.value"
                                                           @blur="metodoConThrottle" @keydown.enter.prevent="create"
                                                           @change="toggleSelection2(option.value,conteoi)"
                                                           :checked="isSelected2(option.value,conteoi)"
                                                           :disabled="data.checkdisabled[conteoi][inde]"
                                                           class="p-1 w-7 h-7 ring-1 ring-zinc-400"
                                                    />
                                                    <label :for="option.value+'_'+conteoi" class="mx-1">{{ option.label }}</label>
                                                </div>
                                                <InputError class="mt-2" :message="form.errors.plan_de_mejoramiento_al_que_apunta_la_necesidad"/>
                                            </div>
                                            <div class="min-w-[250px] max-h-[250px] p-4 border-x-2 border-zinc-400">
                                                <label for="multi-select" class="mb-4  text-md text-blue-900 font-bold">Planes seleccionados</label>
                                                <p v-if="form.plan_de_mejoramiento_al_que_apunta_la_necesidad"
                                                   v-for="itemsito2 in form.plan_de_mejoramiento_al_que_apunta_la_necesidad[conteoi]"
                                                   class="font-bold mt-1">
                                                    - {{ data.planmejoramientonecesidad.find((item) => item.value === itemsito2)?.label }}</p>
                                            </div>


                                            <!--                                            selectmultipledos3-->
                                            <div class="min-w-[450px] max-h-[250px] p-4 ring-0 ring-zinc-100">
                                                 <label class="text-md text-gray-900 font-bold">
                                                    Líneas del Plan de Desarrollo Institucional 2024-2028 al que apunta la necesidad
                                                </label>
                                                <p class="text-md text-gray-700">
                                                    Seleccionar la(s) línea(s) con que se vincula la necesidad
                                                </p>
                                                <div v-for="option in data.lineadelplan"
                                                     :key="option.value" class="mt-3">
                                                    <input type="checkbox" :id="option.value+'_c'+conteoi"
                                                           :value="option.value" @keydown.enter.prevent="create"
                                                           @blur="metodoConThrottle"
                                                           @change="toggleSelection3(option.value,conteoi)"
                                                           :checked="isSelected3(option.value)"
                                                           class="p-1 w-7 h-7 ring-1 ring-zinc-400"
                                                    />
                                                    <label :for="option.value" class="mx-1">{{ option.label }}</label>
                                                </div>
                                                <InputError class="mt-2" :message="form.errors.linea_del_plan_desarrollo_al_que_apunta_la_necesidad"/>
                                            </div>
                                            <div class="min-w-[350px] max-h-[250px] p-4 border-x-2 border-zinc-400">
                                                <label for="multi-select" class="mb-4 text-md text-blue-900 font-bold">Líneas seleccionadas</label>
                                                <div v-if="form.linea_del_plan_desarrollo_al_que_apunta_la_necesidad"
                                                     v-for="itemsito3 in form.linea_del_plan_desarrollo_al_que_apunta_la_necesidad[conteoi]"
                                                     class="font-bold mt-1">
                                                    <p v-if="data.lineadelplan.find((item) => item.value === itemsito3)" class="text-md">
                                                        - {{ data.lineadelplan.find((item) => item.value === itemsito3)?.label }}
                                                    </p>
                                                </div>
                                            </div>

                                            <!-- fin selectmultipledo-->
                                            <div class="min-w-[450px] max-h-[110px] p-4">
                                                 <label class="text-md text-gray-900 font-bold">Frecuencia de uso</label>
                                                <p class="text-md text-gray-700">
                                                    Relacionar la periodicidad de uso
                                                </p>
                                                <div class="w-full inline-flex">
                                                    <SelectInput :dataSet="props.losSelect.frecuencia_de_uso" @blur="metodoConThrottle"
                                                                 v-model="form.frecuencia_de_uso[conteoi]" type="text" @keydown.enter.prevent="create"
                                                                 class="w-full bg-zinc-200 text-black dark:text-white font-mono ring-1 ring-zinc-400 focus:ring-1 focus:ring-sky-300 outline-none duration-300 placeholder:text-black placeholder:opacity-50 rounded-md px-4 py-2 shadow-md focus:shadow-lg focus:shadow-sky-200 dark:shadow-md dark:shadow-purple-500"
                                                                 autocomplete="off"/>
                                                    <InputError class="mt-2" :message="form.errors.frecuencia_de_uso"/>
                                                </div>
                                            </div>
                                            <div class="min-w-[460px] max-h-[110px] p-4">
                                                 <label class="text-md text-gray-900 font-bold">Mantenimientos Requeridos</label>
                                                <p class="text-md text-gray-700">
                                                    Relacionar la periodicidad de los mantenimientos requeridos
                                                </p>
                                                <div class="w-full inline-flex">
                                                    <SelectInput :dataSet="props.losSelect.mantenimientos_requeridos" @blur="metodoConThrottle"
                                                                 v-model="form.mantenimientos_requeridos[conteoi]" @keydown.enter.prevent="create"
                                                                 type="text"
                                                                 class="w-full bg-zinc-200 text-black dark:text-white font-mono ring-1 ring-zinc-400 focus:ring-1 focus:ring-sky-300 outline-none duration-300 placeholder:text-black placeholder:opacity-50 rounded-md px-4 py-2 shadow-md focus:shadow-lg focus:shadow-sky-200 dark:shadow-md dark:shadow-purple-500"
                                                                 autocomplete="off"/>
                                                    <InputError class="mt-2"
                                                                :message="form.errors.mantenimientos_requeridos"/>
                                                </div>
                                            </div>
                                            <div class="min-w-[460px] max-h-[110px] p-4">
                                                 <label class="text-md text-gray-900 font-bold">Capacidad Instalada</label>
                                                <p class="text-md text-gray-700">
                                                    Describir si en la actualidad la institución cuenta con el bien o servicio solicitado o algo
                                                    similar que cubra la necesidad y posiblemente requiera algún ajuste
                                                </p>
                                                <div v-if="data.Otras_capacidad_instalada[conteoi] === false"
                                                     class="w-full inline-flex">
                                                    <SelectInput :dataSet="props.losSelect.capacidad_instalada" @blur="metodoConThrottle"
                                                                 v-model="form.capacidad_instalada[conteoi]" type="text"
                                                                 @keydown.enter.prevent="create"
                                                                 class="w-full bg-zinc-200 text-black dark:text-white font-mono ring-1 ring-zinc-400 focus:ring-1 focus:ring-sky-300 outline-none duration-300 placeholder:text-black placeholder:opacity-50 rounded-md px-4 py-2 shadow-md focus:shadow-lg focus:shadow-sky-200 dark:shadow-md dark:shadow-purple-500"
                                                                 autocomplete="off"/>
                                                    <InputError class="mt-2" :message="form.errors.capacidad_instalada"/>
                                                </div>
                                                <div v-else class="w-full inline-flex">
                                                    <textarea cols="100" rows="7" v-model="form.capacidad_instalada[conteoi]" type="text" @keydown.enter.prevent="create"
                                                           @blur="metodoConThrottle"
                                                           class="w-full bg-zinc-200 text-black dark:text-white dark:bg-black font-mono ring-1 ring-zinc-400 focus:ring-1 focus:ring-sky-300 outline-none duration-300 placeholder:text-black placeholder:opacity-50 rounded-md px-4 py-2 shadow-md focus:shadow-lg focus:shadow-sky-200 dark:shadow-md dark:shadow-purple-500"
                                                           autocomplete="off"/>
                                                    <InputError class="mt-2" :message="form.errors.capacidad_instalada"/>
                                                </div>
                                            </div>
<!--                                            <div class="min-w-[300px] max-h-[110px] p-4">-->
<!--                                                 <label class="text-md text-gray-900 font-bold">Riesgo</label>-->
<!--                                                    <p class="text-md text-gray-700">-->
<!--                                                        Seleccionar ¿Cuáles son los posibles riesgos involucrados en actividad?-->
<!--                                                    </p>-->
<!--                                                <div class="w-full inline-flex">-->
<!--                                                    <SelectInput :dataSet="props.losSelect.riesgo_de_la_inversion" @keydown.enter.prevent="create"-->
<!--                                                                 v-model="form.riesgo_de_la_inversion[conteoi]"-->
<!--                                                                 type="text"-->
<!--                                                                 class="w-full bg-zinc-200 text-black dark:text-white font-mono ring-1 ring-zinc-400 focus:ring-1 focus:ring-sky-300 outline-none duration-300 placeholder:text-black placeholder:opacity-50 rounded-md px-4 py-2 shadow-md focus:shadow-lg focus:shadow-sky-200 dark:shadow-md dark:shadow-purple-500"-->
<!--                                                                 autocomplete="off"/>-->
<!--                                                    <InputError class="mt-2" :message="form.errors.riesgo_de_la_inversion"/>-->
<!--                                                </div>-->
<!--                                            </div>-->
                                            <div :class="data.classOfText_checkbox">
                                                <label for="multi-select" class="mb-4 text-md text-gray-900 font-bold">Riesgos</label>
                                                <p class="text-md text-gray-700">Seleccionar ¿Cuáles son los posibles riesgos involucrados en actividad?</p>
                                                <div v-for="(option,inde) in props.losSelect.riesgo_de_la_inversion" :key="inde" class="mt-3">
                                                    <input type="checkbox" :id="inde+'_d'+conteoi"
                                                           :value="option.value" 
                                                           :checked="isSelected_riesgo(option.value)"
                                                           @change="toggleSelectio_riesgo(option.value,conteoi)"
                                                           @keydown.enter.prevent="create"
                                                           @blur="metodoConThrottle"
                                                           class="p-1 w-7 h-7 ring-1 ring-zinc-400"
                                                    />
                                                    <label :for="option.value" class="mx-2">{{ option.label }}</label>
                                                </div>
                                                <InputError class="mt-2" :message="form.errors.procesos_involucrados"/>
                                            </div>
                                            <div class="min-w-[450px] max-h-[250px] p-4 border-x-2 border-zinc-400">
                                                <label for="multi-select" class="mb-4  text-md text-blue-900 font-bold">Riesgos seleccionados</label>
                                                <p v-if="form.riesgo_de_la_inversion[conteoi]"
                                                   v-for="itemsito in form.riesgo_de_la_inversion[conteoi]"
                                                   class="font-bold mt-1">
                                                    - {{ props.losSelect.riesgo_de_la_inversion.find((item) => item.value === itemsito)?.label }}
                                                </p> 
                                            </div>


                                            <div class="min-w-[500px] max-h-[110px] p-4">
                                                <label class="text-md text-gray-900 font-bold capitalize">anexos (opcional)</label>
                                                <p class="text-md text-gray-700 my-2">
                                                    Relacionar los anexos que hacen parte del ítem de la inversión, compra o reposición, donde
                                                    se enuncien las especificaciones técnicas. Si la solicitud es una reposición, adjuntar el
                                                    informe técnico con código de inventario para asegurar la baja posterior por parte del
                                                    solicitante. Formatos Permitidos: Word, Excel, PDF
                                                </p>
                                                <div class="w-full flex">
                                                    <input :id="'anexs'+conteoi" type="file"
                                                           @keydown.enter.prevent="create"
                                                           @input="form.anexos[conteoi] = $event.target.files[0]"
                                                           @change="GuardarArchivo(conteoi)"
                                                           accept="application/pdf,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                                                           class="w-full bg-zinc-200 text-black dark:text-white dark:bg-black font-mono ring-1 ring-zinc-400 focus:ring-1 focus:ring-sky-300 outline-none duration-300 placeholder:text-black placeholder:opacity-50 rounded-md px-4 py-2 shadow-md focus:shadow-lg focus:shadow-sky-200 dark:shadow-md dark:shadow-purple-500"
                                                    />
                                                    <p v-if="form.anexos[conteoi]"
                                                       class="w-full my-2 mx-6 text-green-700 underline">
                                                        {{ form.anexos[conteoi].name }}
                                                    </p>
                                                    <p v-else-if="data.showanexos[conteoi]"
                                                       class="w-full my-2 mx-6 text-green-700 underline">
                                                        {{ data.showanexos[conteoi] }}
                                                    </p>
                                                </div>
                                            </div>
                                            <InputError class="mt-2" :message="form.errors.anexos"/>
                                            <MasyMenos :conteoi="conteoi" @aumentar="AumentarMasUno" @disminuir="borrarFila"/>
                                        </div>
                                    </div>
                                    <!--   elfin-->

                                    <!-- total-->
                                </div>
                            </div>


                            <section v-if="data.showContent">
                                <div class="px-1 flex py-2 text-gray-700 items-center text-center bg-transparent p-0">
                                    <div class="mx-auto relative overflow-x-auto shadow-md rounded-2xl">
                                        <table class="w-full text-xl text-center rtl:text-right sm:rounded-lg text-gray-700 dark:text-gray-400">
                                            <thead class="text-lg text-gray-700 uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
                                            <tr>
                                                <th scope="col" class="p-6">#</th>
                                                <th scope="col" class="col-span-2 p-6">Necesidad</th>
                                                <th scope="col" class="p-6">Categoria</th>
                                                <th scope="col" class="p-6">Unidad de medida</th>
                                                <th scope="col" class="p-6">Cantidad</th>
                                                <th scope="col" class="p-6">Valor unitario</th>
                                                <th scope="col" class="p-6">Subtotal</th>
                                            </tr>
                                            </thead>
                                            <tbody class="items-center">
                                            <tr v-for="(element, indexNecesidad) in form.necesidad"
                                                class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                                                <td v-if="form.justificacion[indexNecesidad] !== 'Elemento_Borrado'"
                                                    class="w-12 text-center py-2 -px-2">{{
                                                        indexNecesidad + 1
                                                    }}
                                                </td>
                                                <td v-if="form.justificacion[indexNecesidad] !== 'Elemento_Borrado' && element && element.length > 100"
                                                    class="w-full text-center py-2 col-span-2">
                                                    {{ element.slice(0, 100) }} ...
                                                </td>
                                                <td v-else-if="form.justificacion[indexNecesidad] !== 'Elemento_Borrado'"
                                                    class="w-full text-center py-2 col-span-2">
                                                    {{ element }}
                                                </td>

                                                <td v-if="form.justificacion[indexNecesidad] !== 'Elemento_Borrado' && !isNaN(form.categoria[indexNecesidad])"
                                                    class="w-full text-center py-2">
                                                    {{ data.categoria.find((item) => item.value == form.categoria[indexNecesidad])?.label }}
                                                </td>
                                                <td v-else-if="form.justificacion[indexNecesidad] !== 'Elemento_Borrado'"
                                                    class="w-full text-center py-2">{{ (form.categoria[indexNecesidad]) }}
                                                </td>

                                                <td v-if="form.justificacion[indexNecesidad] !== 'Elemento_Borrado'" class="w-full text-center py-2">
                                                    {{ (form.unidad_de_medida[indexNecesidad]) }}
                                                </td>
                                                <td v-if="form.justificacion[indexNecesidad] !== 'Elemento_Borrado'" class="w-full text-center py-2">
                                                    {{
                                                        (form.cantidad[indexNecesidad])
                                                    }}
                                                </td>
                                                <td v-if="form.justificacion[indexNecesidad] !== 'Elemento_Borrado'" class="w-full text-center py-2">
                                                    {{
                                                        form.valor_unitario[indexNecesidad]
                                                    }}
                                                </td>
                                                <td v-if="form.justificacion[indexNecesidad] !== 'Elemento_Borrado'" class="w-full text-center py-2">
                                                    {{ number_format(form.valor_total_solicitatdo_por_necesidad[indexNecesidad], 0, 1) }}
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="w-full text-center md:pl-9 max-w-sm mx-auto my-5 pl-2">
                                    <div class="w-full text-center mx-auto">
                                        <label class="text-xl text-center"> Total final:<small class="px-3 font-bold text-2xl">{{ number_format(data.total_todo, 0, 1) }}</small> </label>
                                    </div>
                                </div>
                                <hr/>
                            </section>
                            <!-- botones de guardado    -->
                            <div v-if="data.showContent"
                                 class="md:inline-flex w-full my-2 md:space-y-0 p-8 text-gray-700 items-center">
                                <div v-if="Object.keys(form.errors).length">
                                    <p class="text-xl text-red-800">Por favor, Corrija los siguientes errores</p>
                                    <ul>
                                        <li v-for="(error, field) in form.errors" :key="field"
                                            class="text-lg text-red-800">
                                            {{
                                                error.replace(/_/g, " ").replace(/(\w+\.)(\d+)/g, (match, p1, p2) => {
                                                    let nuevoNumero = parseInt(p2, 10) + 1;
                                                    return `${p1}${nuevoNumero}`;
                                                })
                                            }}
                                        </li>
                                    </ul>
                                </div>
                                <div class="grid grid-cols-2 gap-8 mx-auto text-center md:pl-6">
                                    <PrimaryButton
                                        class="text-white w-full mx-auto max-w-sm rounded-md text-center bg-indigo-400 py-2 px-4 inline-flex items-center focus:outline-none md:float-right"
                                        :class="{ 'opacity-25': form.processing }"
                                        :disabled="form.processing"
                                        @click="guardar()"
                                    >
                                        Guardar sin enviar
                                    </PrimaryButton>
                                    <PrimaryButton
                                        class="text-white w-full mx-auto max-w-sm rounded-md text-center 
                                         py-2 px-4 inline-flex items-center focus:outline-none md:float-right"
                                        :class="{ 'opacity-25': form.processing }"
                                        :disabled="form.processing"
                                        @click="create(true,true)"
                                    >
                                        <svg fill="none" class="w-4 text-white mr-2" viewBox="0 0 24 24"
                                             stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                        </svg>
                                        Enviar Definitivamente
                                    </PrimaryButton>
                                </div>
                            </div>
                        </section>
                    </div>
                </form>


                <div class="grid mx-auto mt-12 text-gray-700 text-center">
                    <div v-if="data.mensajeYaHaSidoGuardado === ''" 
                         class="grid mx-auto sm:mx-0 text-center">
                        <p class="text-lg text-black dark:text-white">{{ $page.props.app.name }} ©️</p>
                    </div>
                    <div v-else class="grid text-[#499884] font-extrabold text-xl mx-auto flex-row justify-center text-center">
                        <p class="mx-auto text-center">{{ data.mensajeYaHaSidoGuardado }}</p>
                    </div>
                </div>
<!--                qwe {{ props.infoEnviada}}-->
                
                <div v-if="data.mensajeYaHaSidoGuardado !== ''" class="my-8">
                    <TablaResumenEnviado
                        :identificacion_user = form.identificacion_user
                        :infoEnviada = props.infoEnviada
                        :lista_pros_presupuestp = data.lista_pros_presupuestp
                    />
                </div>
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
