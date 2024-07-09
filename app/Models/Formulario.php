<?php

namespace App\Models;

use App\helpers\MyConst;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property mixed $categoria
 */
class Formulario extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'identificacion_user',
        'numero_necesidad',
        'valor_total_de_la_solicitud_actual',
        'valor_total_asignado_en_vigencia_anterior',

        'proceso_que_solicita_presupuesto',//1. sel unica
        'necesidad',//2 Párrafo
        'justificacion', //3 Párrafo
        'actividad',// sel unica
        'categoria',// sel unica
        'unidad_de_medida',// sel unica
        'cantidad',
        'valor_unitario',//8 pesos
        'valor_total_solicitatdo_por_necesidad',// 8.5 automatico
        'periodo_de_inicio_de_ejecucion',//sel unica
        'vigencias_anteriores',//10 //sel unica
        'valor_asignado_en_la_vigencia_anterior', //11 pesos
        'procesos_involucrados',//sel multiple
        'plan_de_mejoramiento_al_que_apunta_la_necesidad',//sel multiple
        'linea_del_plan_desarrollo_al_que_apunta_la_necesidad',//sel multiple
        'frecuencia_de_uso',//sel unica
        'mantenimientos_requeridos',//sel unica
        'capacidad_instalada',//sel unica
        'riesgo_de_la_inversion',//sel unica
        'anexos',//19
        'enviado',
        'user_id',
    ];

    public function user(){return $this->belongsTo(User::class);}
    public function userName(){return $this->user->name;}
    public function proceso_que_solicita_presupuesto(){
        if($this->proceso_que_solicita_presupuesto)
            return MyConst::proceso_que_solicita_presupuesto()[intval($this->proceso_que_solicita_presupuesto)];
        return '';
    }
    
    public function BDToString($nameArray){
//        $this->procesos_involucrados = MyConst::procesos_involucrados();
//        $this->plan_de_mejoramiento_al_que_apunta_la_necesidad = MyConst::plan_de_mejoramiento_al_que_apunta_la_necesidad();
//        $this->linea_del_plan_desarrollo_al_que_apunta_la_necesidad = MyConst::linea_del_plan_desarrollo_al_que_apunta_la_necesidad();
        $$nameArray = MyConst::{$nameArray}();
        
//        return MyConst::procesos_involucrados()[$this->procesos_involucrados];
            if ($this->{$nameArray} || $this->{$nameArray} == '0') {
                $explodedArray = explode(',', $this->{$nameArray});
                $StringArray = [];
                foreach ($explodedArray as $index => $item) {
                    $itemNum = intval($item);
                    $StringArray[] = ($$nameArray[$itemNum]);
                }
                return implode(',', $StringArray);
            }
            return 'Formulario sin diligenciar';
    }
    public function plan_de_mejoramiento_al_que_apunta_la_necesidad(){return MyConst::plan_de_mejoramiento_al_que_apunta_la_necesidad()[$this->plan_de_mejoramiento_al_que_apunta_la_necesidad];}
    public function linea_del_plan_desarrollo_al_que_apunta_la_necesidad(){return MyConst::linea_del_plan_desarrollo_al_que_apunta_la_necesidad()[$this->linea_del_plan_desarrollo_al_que_apunta_la_necesidad];}
    
    public function categoria(){
        if(isset(MyConst::categoria()[$this->categoria])){
            return MyConst::categoria()[$this->categoria];
        }
        return $this->categoria;
    }
    public function ListarCategoria(){
        $catUnicas = Formulario::select('categoria')->distinct()->get();

        $todasLasCat = [[
            'label' => 'Sin categoria',
            'value' => ''
        ]];
        foreach ($catUnicas as $index => $LasCategoria) {
            $nombre = $LasCategoria->categoria();
            
            $todasLasCat[] = [
                'label' => $nombre,
                'value' => $nombre
            ];
        }
        
        return $todasLasCat;
    }
}
