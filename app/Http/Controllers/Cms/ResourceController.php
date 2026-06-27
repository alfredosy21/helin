<?php

namespace App\Http\Controllers\Cms;

use App\Models\Resource;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class ResourceController extends Component
{
    use WithPagination, WithFileUploads;

    public $showForm = false;
    public $editingId = null;

    // Form fields
    public $title;
    public $description;
    public $type;
    public $specialty;
    public $format;
    public $tags;
    public $file_path;
    public $url;
    public $thumbnail;
    public $current_thumbnail;
    public $is_active = true;
    public $views = 0;
    public $position = 0;
    public $featured = false;

    // Filters
    public $search = '';
    public $filterType = '';
    public $filterSpecialty = '';
    public $perPage = 10;

    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'type' => 'required|in:case_study,video,manual,technical_sheet,downloadable_guide,article',
        'specialty' => 'nullable|string|max:100',
        'format' => 'nullable|string|max:50',
        'tags' => 'nullable|string|max:500',
        'file_path' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,zip|max:10240',
        'url' => 'nullable|url|max:500',
        'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'is_active' => 'boolean',
        'views' => 'integer|min:0',
        'position' => 'integer|min:0',
        'featured' => 'boolean',
    ];

    public function mount()
    {
        $this->resetFilters();
    }

    public function render()
    {
        $query = Resource::query();

        // Apply search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhere('specialty', 'like', '%' . $this->search . '%');
            });
        }

        // Apply filters
        if ($this->filterType) {
            $query->where('type', $this->filterType);
        }

        if ($this->filterSpecialty) {
            $query->where('specialty', $this->filterSpecialty);
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
        $this->description = $resource->description;
        $this->type = $resource->type;
        $this->specialty = $resource->specialty;
        $this->format = $resource->format;
        $this->tags = $resource->tags ? implode(', ', json_decode($resource->tags)) : '';
        $this->url = $resource->url;
        $this->current_thumbnail = $resource->thumbnail;
        $this->is_active = $resource->is_active;
        $this->views = $resource->views;
        $this->position = $resource->position;
        $this->featured = $resource->featured;

        $this->showForm = true;
    }

    public function save()
    {
        $this->validate();

        // Process tags
        $tagsArray = [];
        if ($this->tags) {
            $tagsArray = array_map('trim', explode(',', $this->tags));
            $tagsArray = array_filter($tagsArray);
        }

        $data = [
            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type,
            'specialty' => $this->specialty,
            'format' => $this->format,
            'tags' => json_encode($tagsArray),
            'url' => $this->url,
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
            $this->dispatch('showToast', 'Recurso actualizado exitosamente', 'success');
        } else {
            Resource::create($data);
            $this->dispatch('showToast', 'Recurso creado exitosamente', 'success');
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
        $this->dispatch('showToast', 'Recurso eliminado exitosamente', 'success');
    }

    public function updateOrder($orderedIds)
    {
        foreach ($orderedIds as $index => $id) {
            Resource::where('id', $id)->update(['position' => $index]);
        }
        $this->dispatch('showToast', 'Orden actualizado exitosamente', 'success');
    }

    public function resetForm()
    {
        $this->reset([
            'title', 'description', 'type', 'specialty', 'format', 'tags',
            'file_path', 'url', 'thumbnail', 'current_thumbnail',
            'is_active', 'views', 'position', 'featured'
        ]);

        $this->is_active = true;
        $this->views = 0;
        $this->position = 0;
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
