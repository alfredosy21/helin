<?php

namespace App\Http\Controllers\Cms;

use App\Models\Resource;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Str;

#[Title('Gestión de Recursos Clínicos | Helin CMS')]
#[Layout('cms.layouts.dashboard')]
class ResourceController extends Component
{
    use WithPagination, WithFileUploads;

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
    public $views = 0;
    public $position = 0;
    public $featured = false;

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
        'views' => 'integer|min:0',
        'position' => 'integer|min:0',
        'featured' => 'boolean',
    ];

    public function mount()
    {
        $user = Auth::user();
        if (!$user || ($user->rol_id !== 1 && $user->level !== 1)) {
            abort(403, __('cms.abort.resources'));
        }
        $this->resetFilters();
    }

    public function render()
    {
        $query = Resource::query();

        // Apply search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
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

        return view('cms.resources.index', compact('resources'));
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
        $this->views = $resource->views;
        $this->position = $resource->position;
        $this->featured = $resource->featured;

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
            $rules['slug'] = 'required|string|max:255|unique:resources,slug,' . $this->editingId;
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
            'views' => $this->views,
            'position' => $this->position,
            'featured' => $this->featured,
        ];

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
            'is_active', 'featured'
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
