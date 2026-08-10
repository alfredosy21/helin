<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResourceFilterController extends Controller
{
    /**
     * Filtrar recursos vía AJAX
     */
    public function filter(Request $request)
    {
        $search    = $request->get('search', '');
        $typeId    = $request->get('type', '');
        $specialtyId = $request->get('specialty', '');
        $format            = $request->get('format', []);
        $resourceType      = $request->get('resource_type', []);
        $resourceSpecialty = $request->get('resource_specialty', []);
        $sortBy    = $request->get('sort', 'position');

        $resourcesQuery = Resource::where('is_active', true);

        if ($search) {
            $resourcesQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        if ($typeId) {
            $resourcesQuery->where('resource_type_id', $typeId);
        }

        if ($specialtyId) {
            $resourcesQuery->where('resource_specialty_id', $specialtyId);
        }

        if (!empty($resourceType)) {
            $resourcesQuery->whereIn('resource_type_id', (array) $resourceType);
        }

        if (!empty($resourceSpecialty)) {
            $resourcesQuery->whereIn('resource_specialty_id', (array) $resourceSpecialty);
        }

        if (!empty($format)) {
            $resourcesQuery->whereIn('format', (array) $format);
        }

        switch ($sortBy) {
            case 'recent':
                $resourcesQuery->orderBy('created_at', 'desc');
                break;
            default:
                $resourcesQuery->orderBy('position');
        }

        $rawResources = $resourcesQuery->with(['resourceType', 'resourceSpecialty'])->get();

        $multiplier = 4;
        $duplicated = $rawResources->flatMap(fn($r) => array_fill(0, $multiplier, $r));
        $perPage = 12;
        $currentPage = (int) $request->get('page', 1);
        $resources = new \Illuminate\Pagination\LengthAwarePaginator(
            $duplicated->forPage($currentPage, $perPage)->values(),
            $duplicated->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Contadores dinámicos por grupo de filtros (excluyendo el propio grupo)
        $counts = [
            'resource_type' => $this->countByGroup($search, $typeId, $specialtyId, $format, $resourceType, $resourceSpecialty, 'resource_type'),
            'resource_specialty' => $this->countByGroup($search, $typeId, $specialtyId, $format, $resourceType, $resourceSpecialty, 'resource_specialty'),
            'format' => $this->countByGroup($search, $typeId, $specialtyId, $format, $resourceType, $resourceSpecialty, 'format'),
        ];

        $iconMap  = [
            'case_study'        => '→',
            'video'             => '▶',
            'manual'            => '↓',
            'technical_sheet'   => '↓',
            'guide'             => '→',
            'downloadable_guide'=> '→',
        ];
        $formatMap = [
            'article' => '▣ Artículo',
            'pdf'     => '▤ PDF',
            'video'   => '▶ Video',
        ];

        $html = view('web.partials.resource-results', compact(
            'resources', 'sortBy', 'iconMap', 'formatMap'
        ))->render();

        return response()->json([
            'success' => true,
            'html'    => $html,
            'count'   => $resources->total(),
            'counts'  => $counts,
        ]);
    }

    /**
     * Cuenta recursos por grupo excluyendo el filtro del propio grupo.
     */
    private function countByGroup(
        string $search,
        $typeId,
        $specialtyId,
        $format,
        $resourceType,
        $resourceSpecialty,
        string $group
    ): array {
        $query = Resource::where('is_active', true);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        if ($typeId && $group !== 'resource_type') {
            $query->where('resource_type_id', $typeId);
        }

        if ($specialtyId && $group !== 'resource_specialty') {
            $query->where('resource_specialty_id', $specialtyId);
        }

        if (!empty($format) && $group !== 'format') {
            $query->whereIn('format', (array) $format);
        }

        if (!empty($resourceType) && $group !== 'resource_type') {
            $query->whereIn('resource_type_id', (array) $resourceType);
        }

        if (!empty($resourceSpecialty) && $group !== 'resource_specialty') {
            $query->whereIn('resource_specialty_id', (array) $resourceSpecialty);
        }

        $column = match ($group) {
            'resource_type' => 'resource_type_id',
            'resource_specialty' => 'resource_specialty_id',
            'format' => 'format',
        };

        return $query->select($column, DB::raw('count(*) as total'))
            ->groupBy($column)
            ->pluck('total', $column)
            ->toArray();
    }
}
