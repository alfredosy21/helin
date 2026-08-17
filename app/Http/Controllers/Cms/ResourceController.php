<?php

namespace App\Http\Controllers\Cms;

use App\Models\Module;
use App\Models\Resource;
use App\Models\ResourceSpecialty;
use App\Models\ResourceType;
use App\Models\Submodule;
use App\Utils\CmsAccess;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Title('Gestión de Recursos Clínicos | Helin CMS')]
#[Layout('cms.layouts.dashboard')]
class ResourceController extends Component
{
    use WithFileUploads, WithPagination;

    public $showForm = false;

    public $editingId = null;

    // Form fields
    public $title;

    public $slug;

    public $description;

    public $type;

    public $format;

    public $file_path;

    public $url;

    public $thumbnail;

    public $current_thumbnail;

    public $resource_type_id;

    public $resource_specialty_id;

    public $is_active = true;

    public $position = 0;

    public $featured = false;

    // Content fields
    public $content;

    public $diagnosis;

    public $video_url;

    public $materials;

    public $results;

    public $gallery = [];

    public ?array $current_gallery = [];

    // Filters
    public $search = '';

    public $filterType = '';

    public $filterSpecialty = '';

    public $perPage = 10;

    protected $paginationTheme = 'tailwind';

    protected $rules = [
        'title' => 'required|string|max:255',
        'slug' => 'required|string|max:255|unique:resources,slug',
        'description' => 'required|string',
        'type' => 'required|in:case_study,video,manual,technical_sheet,downloadable_guide,article',
        'format' => 'nullable|string|max:50',
        'file_path' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,zip|max:10240',
        'url' => 'nullable|url|max:500',
        'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'resource_type_id' => 'nullable|integer|exists:resource_types,id',
        'resource_specialty_id' => 'nullable|integer|exists:resource_specialties,id',
        'is_active' => 'boolean',
        'position' => 'integer|min:0',
        'featured' => 'boolean',
        'content' => 'nullable|string',
        'diagnosis' => 'nullable|string',
        'video_url' => 'nullable|url|max:500',
        'materials' => 'nullable|string',
        'results' => 'nullable|string',
        'gallery' => 'nullable|array',
        'gallery.*' => 'nullable|image|max:2048',
    ];

    public function mount()
    {
        CmsAccess::authorize(Module::CONTENT, Submodule::CLINICAL_RESOURCES, __('cms.abort.resources'));
        $this->resetFilters();
    }

    public function render()
    {
        $query = Resource::query();

        // Apply search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%'.$this->search.'%')
                    ->orWhere('description', 'like', '%'.$this->search.'%');
            });
        }

        // Apply filters
        if ($this->filterType) {
            $query->where('type', $this->filterType);
        }

        if ($this->filterSpecialty) {
            $query->where('resource_specialty_id', $this->filterSpecialty);
        }

        // Order by position
        $resources = $query->orderBy('position')->orderBy('updated_at', 'desc')
            ->paginate($this->perPage);

        return view('cms.resources.index', compact('resources'))
            ->with([
                'cmsResourceTypes' => ResourceType::orderBy('position')->get(),
                'cmsResourceSpecialties' => ResourceSpecialty::orderBy('position')->get(),
            ]);
    }

    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
        $this->editingId = null;
    }

    public function edit($id)
    {
        $resource = Resource::findOrFail($id);

        $this->editingId = $id;
        $this->title = $resource->title;
        $this->slug = $resource->slug;
        $this->description = $resource->description;
        $this->type = $resource->type;
        $this->format = $resource->format;
        $this->url = $resource->url;
        $this->current_thumbnail = $resource->thumbnail;
        $this->resource_type_id = $resource->resource_type_id;
        $this->resource_specialty_id = $resource->resource_specialty_id;
        $this->is_active = $resource->is_active;
        $this->position = $resource->position;
        $this->featured = $resource->featured;
        $this->content = $resource->content;
        $this->diagnosis = $resource->diagnosis;
        $this->video_url = $resource->video_url;
        $this->materials = $resource->materials;
        $this->results = $resource->results;
        $this->current_gallery = $resource->gallery ?? [];

        $this->showForm = true;
    }

    public function save()
    {
        // Auto-generar slug desde el título si no se proporciona
        if (empty($this->slug)) {
            $this->slug = Str::slug($this->title);
        }

        // Update validation for slug to ignore current record when editing
        $rules = $this->rules;
        if ($this->editingId) {
            $rules['slug'] = 'required|string|max:255|unique:resources,slug,'.$this->editingId;
        }
        $this->validate($rules);

        $data = [
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'type' => $this->type,
            'format' => $this->format,
            'url' => $this->url,
            'resource_type_id' => $this->resource_type_id,
            'resource_specialty_id' => $this->resource_specialty_id,
            'is_active' => $this->is_active,
            'position' => $this->position,
            'featured' => $this->featured,
            'content' => $this->content,
            'diagnosis' => $this->diagnosis,
            'video_url' => $this->video_url,
            'materials' => $this->materials,
            'results' => $this->results,
        ];

        // Handle gallery upload
        if (! empty($this->gallery)) {
            $galleryPaths = [];
            foreach ($this->gallery as $gImage) {
                $filename = time().'_'.uniqid().'.'.$gImage->getClientOriginalExtension();
                $galleryPaths[] = $gImage->storeAs('resources/gallery', $filename, 'public');
            }
            $data['gallery'] = $galleryPaths;
        } elseif ($this->editingId) {
            $data['gallery'] = $this->current_gallery;
        }

        // Handle file upload
        if ($this->file_path) {
            $filePath = $this->file_path->store('resources/files', 'public');
            $data['file_path'] = $filePath;
        }

        // Handle thumbnail upload
        if ($this->thumbnail) {
            $thumbnailPath = $this->thumbnail->store('resources/thumbnails', 'public');
            $data['thumbnail'] = $thumbnailPath;
        } elseif ($this->current_thumbnail) {
            $data['thumbnail'] = $this->current_thumbnail;
        }

        if ($this->editingId) {
            $resource = Resource::findOrFail($this->editingId);
            $resource->update($data);
            $this->dispatch('toast', message: 'Recurso actualizado exitosamente', type: 'success');
        } else {
            Resource::create($data);
            $this->dispatch('toast', message: 'Recurso creado exitosamente', type: 'success');
        }

        $this->cancel();
    }

    public function cancel()
    {
        $this->showForm = false;
        $this->resetForm();
        $this->editingId = null;
    }

    public function confirmDelete($id)
    {
        $resource = Resource::findOrFail($id);
        $resource->delete();
        $this->dispatch('toast', message: 'Recurso eliminado exitosamente', type: 'success');
    }

    public function updateOrder($orderedIds)
    {
        foreach ($orderedIds as $index => $id) {
            Resource::where('id', $id)->update(['position' => $index]);
        }
        $this->dispatch('toast', message: 'Orden actualizado exitosamente', type: 'success');
    }

    public function resetForm()
    {
        $this->reset([
            'title', 'slug', 'description', 'type', 'format',
            'file_path', 'url', 'thumbnail', 'current_thumbnail',
            'resource_type_id', 'resource_specialty_id',
            'is_active', 'featured', 'content', 'diagnosis',
            'video_url', 'materials', 'results', 'gallery', 'current_gallery',
        ]);

        $this->is_active = true;
        $this->featured = false;
    }

    public function resetFilters()
    {
        $this->reset(['search', 'filterType', 'filterSpecialty']);
    }

    public function getTypeLabel($type)
    {
        $labels = [
            'case_study' => 'Caso clínico',
            'video' => 'Video',
            'manual' => 'Manual',
            'technical_sheet' => 'Ficha técnica',
            'downloadable_guide' => 'Guía descargable',
            'article' => 'Artículo',
        ];

        return $labels[$type] ?? $type;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedTitle()
    {
        if (empty($this->slug)) {
            $this->slug = Str::slug($this->title);
        }
    }

    public function updatingFilterType()
    {
        $this->resetPage();
    }

    public function updatingFilterSpecialty()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }
}
