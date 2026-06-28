<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use Illuminate\Http\Request;

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
        $format    = $request->get('format[]', []);
        $resourceType    = $request->get('resource_type[]', []);
        $resourceSpecialty = $request->get('resource_specialty[]', []);
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

        $resources = $resourcesQuery->with(['resourceType', 'resourceSpecialty'])->paginate(12);

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
        ]);
    }
}
