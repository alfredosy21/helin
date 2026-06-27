<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Models\ResourceType;
use App\Models\ResourceSpecialty;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ResourceController extends Controller
{
    /**
     * Filtrar recursos clínicos vía AJAX
     */
    public function filtrar(Request $request): JsonResponse
    {
        try {
            // Obtener parámetros de filtro
            $search = $request->get('search', '');
            $typeId = $request->get('type', '');
            $specialtyId = $request->get('specialty', '');
            $format = $request->get('format', []);
            $resourceTypes = $request->get('resource_type', []);
            $resourceSpecialties = $request->get('resource_specialty', []);
            $sortBy = $request->get('sort', 'position');
            $page = $request->get('page', 1);

            // Construir query
            $query = Resource::where('is_active', true);

            // Aplicar filtros
            if (!empty($search)) {
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', '%' . $search . '%')
                      ->orWhere('description', 'like', '%' . $search . '%');
                });
            }

            if (!empty($typeId)) {
                $query->where('resource_type_id', $typeId);
            }

            if (!empty($specialtyId)) {
                $query->where('resource_specialty_id', $specialtyId);
            }

            // Filtros de checkboxes (múltiples valores)
            if (!empty($resourceTypes)) {
                $query->whereIn('resource_type_id', $resourceTypes);
            }

            if (!empty($resourceSpecialties)) {
                $query->whereIn('resource_specialty_id', $resourceSpecialties);
            }

            if (!empty($format)) {
                if (is_array($format)) {
                    $query->whereIn('format', $format);
                } else {
                    $query->where('format', $format);
                }
            }

            // Aplicar ordenamiento
            switch($sortBy) {
                case 'recent':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'views':
                    $query->orderBy('views', 'desc');
                    break;
                case 'position':
                default:
                    $query->orderBy('position', 'asc');
                    break;
            }

            // Paginar resultados con relaciones cargadas
            $resources = $query->with(['resourceType', 'resourceSpecialty'])->paginate(12, ['*'], 'page', $page);

            // Generar HTML para los recursos
            $html = $this->generateResourcesHtml($resources);

            // Generar HTML para la paginación
            $paginationHtml = $resources->appends($request->query())->links('pagination::bootstrap-4')->toHtml();

            return response()->json([
                'success' => true,
                'html' => $html,
                'pagination' => $paginationHtml,
                'toolbar' => $this->generateToolbarHtml($resources),
                'total' => $resources->total(),
                'current_page' => $resources->currentPage(),
                'last_page' => $resources->lastPage(),
                'per_page' => $resources->perPage(),
                'from' => $resources->firstItem(),
                'to' => $resources->lastItem()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar los recursos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generar HTML para el grid de recursos
     */
    private function generateResourcesHtml($resources): string
    {
        if ($resources->count() === 0) {
            return view('web.partials.no-results')->render();
        }

        $html = '<div class="resource-grid" id="casos">';

        foreach ($resources as $resource) {
            $html .= $this->generateResourceCard($resource);
        }

        $html .= '</div>';

        // Agregar paginación
        $html .= '<div class="pagination-wrapper">';
        $html .= $resources->appends(request()->query())->links('pagination::bootstrap-4');
        $html .= '</div>';

        return $html;
    }

    /**
     * Generar HTML para una tarjeta de recurso
     */
    private function generateResourceCard($resource): string
    {
        // Mapeos para iconos y formatos
        $iconMap = [
            'case_study' => '→',
            'video' => '▶',
            'manual' => '↓',
            'technical_sheet' => '↓',
            'guide' => '↓',
            'article' => '→',
            'protocol' => '→'
        ];

        $formatMap = [
            'pdf' => '▣ PDF',
            'video' => '▶ Video',
            'article' => '▣ Artículo',
            'guide' => '↓ Guía',
            'manual' => '↓ Manual',
            'protocol' => '→ Protocolo'
        ];

        // Obtener datos relacionados
        $typeName = $resource->resourceType ? $resource->resourceType->name : 'General';
        $specialtyName = $resource->resourceSpecialty ? $resource->resourceSpecialty->name : null;

        // Procesar tags
        $tags = [];
        if ($resource->tags) {
            $tags = is_array($resource->tags) ? $resource->tags : json_decode($resource->tags, true) ?? [];
        }

        $tags = array_merge($tags, $specialtyName ? [$specialtyName] : []);

        // Generar HTML de la tarjeta
        $html = '<div class="resource-card">';
        $html .= '<div class="resource-header">';
        $html .= '<span class="resource-type">' . htmlspecialchars($typeName) . '</span>';
        $html .= '<span class="resource-play">' . ($iconMap[$resource->type] ?? '→') . '</span>';
        $html .= '</div>';

        $html .= '<div class="resource-body">';
        $html .= '<h3 class="resource-title">' . htmlspecialchars($resource->title) . '</h3>';
        $html .= '<p class="resource-description">' . htmlspecialchars($resource->description) . '</p>';

        if (!empty($tags)) {
            $html .= '<div class="resource-tags">';
            foreach ($tags as $tag) {
                $html .= '<span class="tag">' . htmlspecialchars($tag) . '</span>';
            }
            $html .= '</div>';
        }

        $html .= '</div>';

        $html .= '<div class="resource-footer">';
        $html .= '<span class="resource-format">' . ($formatMap[$resource->format] ?? '▣ Artículo') . '</span>';

        $linkText = 'Leer';
        if ($resource->type === 'video') {
            $linkText = 'Ver video';
        } elseif ($resource->format === 'pdf') {
            $linkText = 'Descargar';
        }

        $url = $resource->url ?: '#';
        $target = $resource->format === 'pdf' || str_starts_with($url, 'http') ? '_blank' : '_self';

        $html .= '<a href="' . htmlspecialchars($url) . '" class="resource-link" target="' . $target . '">' . htmlspecialchars($linkText) . '</a>';
        $html .= '</div>';

        $html .= '</div>';

        return $html;
    }

    /**
     * Generar HTML para la toolbar
     */
    private function generateToolbarHtml($resources): string
    {
        $from = $resources->firstItem() ?? 0;
        $to = $resources->lastItem() ?? 0;
        $total = $resources->total();

        $html = '<div class="toolbar">';
        $html .= '<p>Mostrando <strong>' . $from . '-' . $to . '</strong> de <strong>' . $total . '</strong> recursos clínicos</p>';

        // Select de ordenamiento
        $currentSort = request('sort', 'position');
        $html .= '<select class="sort-select" id="sortSelect">';
        $html .= '<option value="position" ' . ($currentSort === 'position' ? 'selected' : '') . '>Ordenar por defecto</option>';
        $html .= '<option value="recent" ' . ($currentSort === 'recent' ? 'selected' : '') . '>Más recientes</option>';
        $html .= '<option value="views" ' . ($currentSort === 'views' ? 'selected' : '') . '>Más consultados</option>';
        $html .= '</select>';

        $html .= '</div>';

        return $html;
    }
}
