<?php

namespace App\Http\Controllers;

use App\helpers\Myhelp;
use App\helpers\MyModels;
use App\Models\Formulario;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Inertia\Inertia;

class IdeasController extends Controller
{

    public $thisAtributos, $FromController = 'Idea';
    private $PorcentajeSimilitud = 72;

    public function __construct()
    {
//        $this->thisAtributos = (new formulario())->getFillable(); //not using
    }

    function compareStrings($str1, $str2){
        if (is_numeric($str1) || is_numeric($str2)) {
            return false; // si alguno es numerico, quiere decir que la categoria es del seleccionable
        }
        if($str1 === $str2) return false;
        
        
        // Convertir ambos strings a minúsculas para comparación case-insensitive
        $str1 = strtolower($str1);
        $str2 = strtolower($str2);

        // Calcular el porcentaje de similitud usando similar_text
        $percent = 50;
        similar_text($str1, $str2, $percent);

        // Establecer un umbral de similitud, por ejemplo, 60%
        $thresholdPercent = $this->PorcentajeSimilitud;

        // Calcular la distancia de Levenshtein
        $levDistance = levenshtein($str1, $str2);

        // Establecer un umbral de distancia de Levenshtein, por ejemplo, 3
        $thresholdLevDistance = 3;

        // Verificar si los strings son suficientemente similares
        if ($percent >= $thresholdPercent || $levDistance <= $thresholdLevDistance) {
            return true;
        }

        return false;
    }
    public function index(Request $request)
    {
        $numberPermissions = MyModels::getPermissionToNumber(
            Myhelp::WriteAuthLog($this, ' --formularios index-- ')
        );

        $CategoriasTodas = Formulario::select('categoria')->distinct()->get();
        //compara las que se parescan
//        $fromController = new Collection();
        $fromController = collect();
        if ($request->has('search')) {
            $this->PorcentajeSimilitud = intval($request->search);
        }else{
            $this->PorcentajeSimilitud = 80;
        }
        foreach ($CategoriasTodas as $item) {
            foreach ($CategoriasTodas as $item2) {
                if ($this->compareStrings($item['categoria'], $item2['categoria'])) {
                    $fromController->push([$item['categoria'] => $item2['categoria']]);
                }
            }
        }
//        dd($fromController);
//        $this->Filtros($fromController, $request);
//        $losSelect = $this->Dependencias();

        $perPage = $request->has('perPage') ? $request->perPage : 10;
        $total = $fromController->count();
        $page = request('page', 1); // Current page number
        $fromController = new LengthAwarePaginator(
            $fromController->forPage($page, $perPage),
            $total,
            $perPage,
            $page,
            ['path' => request()->url()]
        );

        return Inertia::render($this->FromController . '/CategoriasSimilares', [
            'fromController' => $fromController,
            'total' => $fromController->count(),
            'title' => __('app.label.' . $this->FromController),
            'filters' => $request->all(['search', 'field', 'order']),
            'perPage' => (int)$perPage,
            'numberPermissions' => $numberPermissions,
//            'losSelect' => $losSelect,
        ]);
    }
}
