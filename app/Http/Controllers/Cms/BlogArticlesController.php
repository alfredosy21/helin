<?php

declare(strict_types=1);

namespace App\Http\Controllers\Cms;

use App\Models\Blog;
use App\Models\Activities;
use App\Models\BlogCategory;
use App\Services\FileUploadService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;

/**
 * Class BlogArticlesController
 *
 * Manages blog articles within the Helin CMS content management system.
 * Handles blog content creation, SEO optimization, and publishing workflow.
 * Provides reactive interface for blog article lifecycle management.
 *
 * Features:
 * - Rich content management
 * - SEO optimization (meta tags, slugs)
 * - Publishing workflow
 * - Featured and pinned articles
 * - Category assignment
 * - Engagement tracking
 * - Bulk operations support
 *
 * @version 1.0.0
 * @package App\Http\Controllers\Cms
 */
#[Title('Gestión de Artículos del Blog | Helin CMS')]
#[Layout('cms.layouts.dashboard')]
class BlogArticlesController extends Component
{
    use WithPagination, WithFileUploads;

    /** @var string Article title */
    #[Validate('required|string|max:255')]
    public string $title = '';

    /** @var string|null SEO-friendly slug for URL generation */
    #[Validate('nullable|string|max:255')]
    public ?string $slug = '';

    /** @var string|null Article author name */
    #[Validate('nullable|string|max:255')]
    public ?string $author = '';

    /** @var string|null Article content body */
    #[Validate('required|string')]
    public ?string $content = '';

    /** @var string|null Article excerpt for preview */
    #[Validate('nullable|string|max:500')]
    public ?string $excerpt = '';

    /** @var mixed Temporary uploaded featured image file */
    public $featured_image;

    /** @var string|null Current featured image path stored in DB */
    public ?string $current_featured_image = null;

    /** @var string|null Meta title for SEO */
    #[Validate('nullable|string|max:255')]
    public ?string $meta_title = '';

    /** @var string|null Meta description for SEO */
    #[Validate('nullable|string|max:500')]
    public ?string $meta_description = '';

    /** @var string|null Meta keywords for SEO */
    #[Validate('nullable|string|max:255')]
    public ?string $meta_keywords = '';

    /** @var int|null Blog category ID */
    #[Validate('nullable|integer|exists:blog_categories,id')]
    public ?int $blog_category_id = null;

    /** @var bool Article active status */
    #[Validate('boolean')]
    public bool $is_active = false;

    /** @var bool Featured article status */
    #[Validate('boolean')]
    public bool $is_featured = false;

    /** @var bool Pinned article status */
    #[Validate('boolean')]
    public bool $is_pinned = false;

    /** @var int|null ID of the article being modified */
    public ?int $editingId = null;

    /** @var string Search query for real-time filtering */
    public string $search = '';

    /** @var int Pagination limit */
    public int $perPage = 20;

    /** @var bool Modal visibility state */
    public bool $showForm = false;
    public bool $showDeleteModal = false;
    public ?int $deleteId = null;

    /** @var bool Global loading indicator */
    public bool $isLoading = false;

    /** @var string Custom pagination theme */
    protected string $paginationTheme = 'tailwind';

    /**
     * Component Lifecycle: Authorization Check
     *
     * Validates user permissions to access blog article management.
     * Only administrators and content managers can access this module.
     *
     * @return void
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     */
    public function mount(): void
    {
        $user = Auth::user();
        if (!$user || ($user->rol_id !== 1 && $user->level !== 1)) {
            abort(403, __('cms.abort.blog_articles'));
        }
    }

    /**
     * Render the component with paginated and sorted blog articles
     *
     * Displays blog articles in a tabular format with search capabilities,
     * pagination, and ordering. Includes both active and inactive articles
     * for comprehensive management.
     *
     * @return View
     */
    public function render(): View
    {
        $articles = Blog::query()
            ->with('blogCategory')
            ->when($this->search, function ($query) {
                $query->where('title', 'like', "%{$this->search}%")
                    ->orWhere('slug', 'like', "%{$this->search}%")
                    ->orWhere('author', 'like', "%{$this->search}%")
                    ->orWhere('content', 'like', "%{$this->search}%");
            })
            ->orderBy('order', 'asc')
            ->orderBy('is_pinned', 'desc')
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('cms.blog_articles.index', [
            'articles'   => $articles,
            'categories' => BlogCategory::query()->orderBy('name')->get()
        ]);
    }

    /**
     * Prepare the interface for a new article record
     *
     * Initializes form fields with default values and opens the modal for data entry.
     *
     * @return void
     */
    public function create(): void
    {
        $this->resetForm();
        $this->is_active = false;
        $this->showForm = true;
        $this->dispatch('open-form');
    }

    /**
     * Persist or synchronize blog article data
     *
     * Handles both creation and update operations with comprehensive validation.
     * Automatically generates slug if not provided. Updates activity log and
     * provides user feedback through toast notifications.
     *
     * @return void
     */
    public function save(FileUploadService $fileUpload): void
    {
        $this->isLoading = true;

        // Dynamic unique validation
        $this->validate([
            'title' => 'required|string|max:255|unique:blogs,title' . ($this->editingId ? ",{$this->editingId}" : ''),
            'slug' => 'nullable|string|max:255|unique:blogs,slug' . ($this->editingId ? ",{$this->editingId}" : ''),
            'author' => 'nullable|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'featured_image' => 'nullable|image|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'blog_category_id' => 'nullable|integer|exists:blog_categories,id',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_pinned' => 'boolean',
        ]);

        try {
            $data = [
                'title' => $this->title,
                'slug' => $this->slug ?: \Illuminate\Support\Str::slug($this->title),
                'author' => $this->author,
                'content' => $this->content,
                'excerpt' => $this->excerpt,
                'meta_title' => $this->meta_title,
                'meta_description' => $this->meta_description,
                'meta_keywords' => $this->meta_keywords,
                'blog_category_id' => $this->blog_category_id,
                'is_active' => $this->is_active,
                'is_featured' => $this->is_featured,
                'is_pinned' => $this->is_pinned,
                'published_at' => $this->is_active ? now() : null,
            ];

            if ($this->featured_image) {
                $upload = $fileUpload->save($this->featured_image, 'blog');
                $data['featured_image'] = $upload['path'];
            } elseif ($this->editingId) {
                $data['featured_image'] = $this->current_featured_image;
            }

            if ($this->editingId) {
                $article = Blog::findOrFail($this->editingId);
                $article->update($data);

                Activities::saveActivity(__('cms.controllers.blog_articles.activity_updated', ['id' => $article->id]));
                $this->dispatch('toast', message: __('cms.controllers.blog_articles.updated'), type: 'success');
            } else {
                Blog::query()->increment('order');
                $data['order'] = 1;

                $article = Blog::create($data);

                Activities::saveActivity(__('cms.controllers.blog_articles.activity_created', ['id' => $article->id]));
                $this->dispatch('toast', message: __('cms.controllers.blog_articles.created'), type: 'success');
            }

            $this->cancel();

        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: __('cms.controllers.blog_articles.process_error'), type: 'error');
        } finally {
            $this->isLoading = false;
        }
    }

    /**
     * Hydrate form with existing article data
     *
     * Loads all article properties into the form for editing.
     * Opens the modal and prepares the interface for modification.
     *
     * @param int $id The article identifier
     * @return void
     */
    public function edit(int $id): void
    {
        $article = Blog::findOrFail($id);

        $this->editingId = $id;
        $this->title = $article->title;
        $this->slug = $article->slug;
        $this->author = $article->author;
        $this->content = $article->content;
        $this->excerpt = $article->excerpt;
        $this->current_featured_image = $article->featured_image;
        $this->meta_title = $article->meta_title;
        $this->meta_description = $article->meta_description;
        $this->meta_keywords = $article->meta_keywords;
        $this->blog_category_id = $article->blog_category_id;
        $this->is_active = $article->is_active;
        $this->is_featured = $article->is_featured;
        $this->is_pinned = $article->is_pinned;

        $this->showForm = true;
        $this->dispatch('open-form');
    }

    public function openDeleteModal(int $id): void
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        if (!$this->deleteId) return;

        try {
            $article = Blog::findOrFail($this->deleteId);
            $articleTitle = $article->title;
            $article->delete();

            Activities::saveActivity(__('cms.controllers.blog_articles.activity_deleted', ['title' => $articleTitle]));
            $this->dispatch('toast', message: __('cms.controllers.blog_articles.deleted'), type: 'success');

        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: __('cms.controllers.blog_articles.delete_error'), type: 'error');
        } finally {
            $this->showDeleteModal = false;
            $this->deleteId = null;
        }
    }

    /**
     * Close form and reset internal state
     *
     * Clears all form data, hides the modal, and resets validation state.
     * Dispatches event to notify frontend components of state change.
     *
     * @return void
     */
    public function cancel(): void
    {
        $this->resetForm();
        $this->showForm = false;
        $this->dispatch('close-form');
    }

    /**
     * Clear all reactive form properties
     *
     * Resets all form fields to their default values and clears validation
     * errors. Used during form initialization and cleanup operations.
     *
     * @return void
     */
    protected function validationAttributes(): array
    {
        return [
            'title' => __('cms.validation_attributes.article_title'),
        ];
    }

    private function resetForm(): void
    {
        $this->reset([
            'title', 'slug', 'author', 'content', 'excerpt', 'featured_image',
            'current_featured_image', 'meta_title', 'meta_description', 'meta_keywords', 'blog_category_id',
            'is_active', 'is_featured', 'is_pinned', 'editingId'
        ]);
        $this->resetValidation();
    }

    /**
     * Lifecycle listener: Pagination reset on search
     *
     * Automatically resets pagination to first page when search query changes.
     * Ensures consistent user experience during search operations.
     *
     * @return void
     */
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Toggle article active status
     *
     * @param int $id The article identifier
     * @return void
     */
    public function toggleStatus(int $id): void
    {
        try {
            $article = Blog::findOrFail($id);
            $article->update([
                'is_active' => !$article->is_active,
                'published_at' => !$article->is_active ? now() : null
            ]);

            $toastMsg = $article->is_active
                ? __('cms.controllers.blog_articles.activated')
                : __('cms.controllers.blog_articles.deactivated');
            $status = $article->is_active ? 'activado' : 'desactivado';
            Activities::saveActivity(__('cms.controllers.blog_articles.activity_status', ['status' => $status, 'id' => $article->id]));
            $this->dispatch('toast', message: $toastMsg, type: 'success');

        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: __('cms.controllers.blog_articles.status_error'), type: 'error');
        }
    }

    /**
     * Toggle featured status
     *
     * @param int $id The article identifier
     * @return void
     */
    public function toggleFeatured(int $id): void
    {
        try {
            $article = Blog::findOrFail($id);
            $article->update(['is_featured' => !$article->is_featured]);

            $toastMsg = $article->is_featured
                ? __('cms.controllers.blog_articles.featured_on')
                : __('cms.controllers.blog_articles.featured_off');
            $status = $article->is_featured ? 'marcado como destacado' : 'desmarcado como destacado';
            Activities::saveActivity(__('cms.controllers.blog_articles.activity_featured', ['status' => $status, 'id' => $article->id]));
            $this->dispatch('toast', message: $toastMsg, type: 'success');

        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: __('cms.controllers.blog_articles.featured_error'), type: 'error');
        }
    }

    /**
     * Reorder the display sequence of blog articles via drag & drop data.
     *
     * @param array $orderedIds Array of IDs in the new order
     * @return void
     */
    public function updateOrder(array $orderedIds): void
    {
        try {
            foreach ($orderedIds as $index => $id) {
                Blog::query()->where('id', $id)->update(['order' => $index + 1]);
            }

            Activities::saveActivity(__('cms.controllers.blog_articles.activity_reordered', ['user_id' => Auth::id()]));
            $this->dispatch('toast', message: __('cms.controllers.blog_articles.order_updated'), type: 'success');
        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: __('cms.controllers.blog_articles.order_error'), type: 'error');
        }
    }
}
