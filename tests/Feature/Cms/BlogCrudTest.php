<?php

namespace Tests\Feature\Cms;

use App\Http\Controllers\Cms\BlogArticlesController;
use App\Http\Controllers\Cms\BlogCategoriesController;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\Feature\Cms\Concerns\CreatesCmsUsers;
use Tests\TestCase;

class BlogCrudTest extends TestCase
{
    use CreatesCmsUsers;
    use RefreshDatabase;

    public function test_blog_category_crud_flow(): void
    {
        $admin = $this->adminUser();

        Livewire::actingAs($admin)
            ->test(BlogCategoriesController::class)
            ->call('create')
            ->set('name', 'Odontología Restauradora')
            ->set('description', 'Artículos sobre restauraciones')
            ->call('save')
            ->assertHasNoErrors();

        $category = BlogCategory::where('name', 'Odontología Restauradora')->first();
        $this->assertNotNull($category);
        $this->assertSame('odontologia-restauradora', $category->slug);

        Livewire::actingAs($admin)
            ->test(BlogCategoriesController::class)
            ->call('edit', $category->id)
            ->set('name', 'Odontología Restauradora Avanzada')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame('Odontología Restauradora Avanzada', $category->refresh()->name);

        Livewire::actingAs($admin)
            ->test(BlogCategoriesController::class)
            ->call('confirmDelete', $category->id);

        $this->assertDatabaseMissing('blog_categories', ['id' => $category->id]);
    }

    public function test_blog_article_crud_flow(): void
    {
        $admin = $this->adminUser();
        $category = BlogCategory::forceCreate([
            'name' => 'Implantes',
            'slug' => 'implantes',
            'is_active' => 1,
        ]);

        Livewire::actingAs($admin)
            ->test(BlogArticlesController::class)
            ->call('create')
            ->set('title', 'Cuidados post-operatorios')
            ->set('content', '<p>Recomendaciones tras la cirugía.</p>')
            ->set('blog_category_id', $category->id)
            ->set('is_active', true)
            ->call('save')
            ->assertHasNoErrors();

        $article = Blog::where('title', 'Cuidados post-operatorios')->first();
        $this->assertNotNull($article);
        $this->assertSame('cuidados-post-operatorios', $article->slug);
        $this->assertSame($category->id, $article->blog_category_id);
        $this->assertTrue((bool) $article->is_active);
        $this->assertNotNull($article->published_at);

        Livewire::actingAs($admin)
            ->test(BlogArticlesController::class)
            ->call('edit', $article->id)
            ->set('title', 'Cuidados post-operatorios esenciales')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame('Cuidados post-operatorios esenciales', $article->refresh()->title);

        Livewire::actingAs($admin)
            ->test(BlogArticlesController::class)
            ->call('toggleStatus', $article->id);

        $this->assertFalse((bool) $article->refresh()->is_active);

        Livewire::actingAs($admin)
            ->test(BlogArticlesController::class)
            ->call('openDeleteModal', $article->id)
            ->call('delete');

        $this->assertDatabaseMissing('blogs', ['id' => $article->id]);
    }

    public function test_blog_article_requires_title_and_content(): void
    {
        $admin = $this->adminUser();

        Livewire::actingAs($admin)
            ->test(BlogArticlesController::class)
            ->call('create')
            ->call('save')
            ->assertHasErrors(['title', 'content']);

        $this->assertDatabaseCount('blogs', 0);
    }
}
