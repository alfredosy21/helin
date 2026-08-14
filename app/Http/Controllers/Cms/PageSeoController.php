<?php

namespace App\Http\Controllers\Cms;

use App\Models\Activities;
use App\Models\Module;
use App\Models\PageSeo;
use App\Models\Submodule;
use App\Utils\CmsAccess;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Title('SEO de Páginas | Helin CMS')]
#[Layout('cms.layouts.dashboard')]
class PageSeoController extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $showForm = false;

    public $editingId = null;

    // Form fields
    public $page_slug;

    public $seo_title;

    public $seo_description;

    public $seo_keywords;

    public $og_image;

    public ?string $current_og_image = null;

    // Filters
    public $search = '';

    public $perPage = 10;

    protected $paginationTheme = 'tailwind';

    protected $rules = [
        'page_slug' => 'required|string|max:255|unique:page_seo,page_slug',
        'seo_title' => 'nullable|string|max:255',
        'seo_description' => 'nullable|string|max:1000',
        'seo_keywords' => 'nullable|string|max:1000',
        'og_image' => 'nullable|image|max:2048',
    ];

    public function mount()
    {
        CmsAccess::authorize(Module::SETTINGS, Submodule::PAGE_SEO, __('cms.abort.page_seo'));
        $this->resetFilters();
    }

    public function render()
    {
        $pageSeos = PageSeo::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('page_slug', 'like', '%'.$this->search.'%')
                        ->orWhere('seo_title', 'like', '%'.$this->search.'%');
                });
            })
            ->orderBy('updated_at', 'desc')
            ->paginate($this->perPage);

        return view('cms.page-seo.index', compact('pageSeos'));
    }

    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
        $this->editingId = null;
    }

    public function edit($id)
    {
        $pageSeo = PageSeo::findOrFail($id);

        $this->editingId = $id;
        $this->page_slug = $pageSeo->page_slug;
        $this->seo_title = $pageSeo->seo_title;
        $this->seo_description = $pageSeo->seo_description;
        $this->seo_keywords = $pageSeo->seo_keywords;
        $this->current_og_image = $pageSeo->og_image;

        $this->showForm = true;
    }

    public function save()
    {
        $rules = $this->rules;
        if ($this->editingId) {
            $rules['page_slug'] = 'required|string|max:255|unique:page_seo,page_slug,'.$this->editingId;
        }
        $this->validate($rules);

        $data = [
            'page_slug' => $this->page_slug,
            'seo_title' => $this->seo_title,
            'seo_description' => $this->seo_description,
            'seo_keywords' => $this->seo_keywords,
        ];

        if ($this->og_image) {
            $filename = time().'_'.$this->og_image->getClientOriginalName();
            $data['og_image'] = $this->og_image->storeAs('page-seo', $filename, 'public');
        } elseif ($this->editingId) {
            $data['og_image'] = $this->current_og_image;
        }

        try {
            if ($this->editingId) {
                $pageSeo = PageSeo::findOrFail($this->editingId);
                $pageSeo->update($data);
                Activities::saveActivity(__('cms.controllers.page_seo.activity_updated', [
                    'id' => $pageSeo->id,
                ]));
                $this->dispatch('toast', message: __('cms.controllers.page_seo.updated'), type: 'success');
            } else {
                PageSeo::create($data);
                Activities::saveActivity(__('cms.controllers.page_seo.activity_created'));
                $this->dispatch('toast', message: __('cms.controllers.page_seo.created'), type: 'success');
            }

            $this->cancel();
        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: __('cms.controllers.page_seo.process_error'), type: 'error');
        }
    }

    public function confirmDelete($id)
    {
        try {
            $pageSeo = PageSeo::findOrFail($id);
            $pageSeo->delete();

            Activities::saveActivity(__('cms.controllers.page_seo.activity_deleted', [
                'id' => $id,
            ]));

            $this->dispatch('toast', message: __('cms.controllers.page_seo.deleted'), type: 'success');
        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: __('cms.controllers.page_seo.delete_error'), type: 'error');
        }
    }

    public function cancel()
    {
        $this->showForm = false;
        $this->resetForm();
        $this->editingId = null;
    }

    public function resetForm()
    {
        $this->reset([
            'page_slug', 'seo_title', 'seo_description',
            'seo_keywords', 'og_image', 'current_og_image',
        ]);
    }

    public function resetFilters()
    {
        $this->reset(['search']);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }
}
