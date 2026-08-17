<?php

namespace Tests\Feature\Cms;

use App\Http\Controllers\Cms\ResourceController;
use App\Http\Controllers\Cms\ResourceSpecialtyController;
use App\Http\Controllers\Cms\ResourceTypeController;
use App\Models\Resource;
use App\Models\ResourceSpecialty;
use App\Models\ResourceType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\Feature\Cms\Concerns\CreatesCmsUsers;
use Tests\TestCase;

class ResourcesCrudTest extends TestCase
{
    use CreatesCmsUsers;
    use RefreshDatabase;

    public function test_resource_type_crud_flow(): void
    {
        $admin = $this->adminUser();

        Livewire::actingAs($admin)
            ->test(ResourceTypeController::class)
            ->call('create')
            ->set('name', 'Videos')
            ->set('description', 'Videos clínicos')
            ->set('banner_title', 'Banner videos')
            ->call('save')
            ->assertHasNoErrors();

        $type = ResourceType::where('name', 'Videos')->first();
        $this->assertNotNull($type);
        $this->assertSame('Banner videos', $type->banner_title);

        Livewire::actingAs($admin)
            ->test(ResourceTypeController::class)
            ->call('edit', $type->id)
            ->set('name', 'Videos Clínicos')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame('Videos Clínicos', $type->refresh()->name);

        Livewire::actingAs($admin)
            ->test(ResourceTypeController::class)
            ->call('confirmDelete', $type->id);

        $this->assertDatabaseMissing('resource_types', ['id' => $type->id]);
    }

    public function test_resource_specialty_crud_flow(): void
    {
        $admin = $this->adminUser();

        Livewire::actingAs($admin)
            ->test(ResourceSpecialtyController::class)
            ->call('create')
            ->set('name', 'Periodoncia')
            ->set('banner_description', 'Banner periodoncia')
            ->call('save')
            ->assertHasNoErrors();

        $specialty = ResourceSpecialty::where('name', 'Periodoncia')->first();
        $this->assertNotNull($specialty);
        $this->assertSame('Banner periodoncia', $specialty->banner_description);

        Livewire::actingAs($admin)
            ->test(ResourceSpecialtyController::class)
            ->call('edit', $specialty->id)
            ->set('name', 'Periodoncia Avanzada')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame('Periodoncia Avanzada', $specialty->refresh()->name);

        Livewire::actingAs($admin)
            ->test(ResourceSpecialtyController::class)
            ->call('confirmDelete', $specialty->id);

        $this->assertDatabaseMissing('resource_specialties', ['id' => $specialty->id]);
    }

    public function test_resource_crud_flow_with_content_fields(): void
    {
        $admin = $this->adminUser();

        $type = ResourceType::forceCreate(['name' => 'Videos', 'description' => null, 'is_active' => true, 'position' => 1]);
        $specialty = ResourceSpecialty::forceCreate(['name' => 'Periodoncia', 'description' => null, 'is_active' => true, 'position' => 1]);

        Livewire::actingAs($admin)
            ->test(ResourceController::class)
            ->call('create')
            ->set('title', 'Caso clínico GBR')
            ->set('slug', 'caso-clinico-gbr')
            ->set('description', 'Descripción del caso')
            ->set('type', 'case_study')
            ->set('resource_type_id', $type->id)
            ->set('resource_specialty_id', $specialty->id)
            ->set('featured', true)
            ->set('content', 'Contenido detallado del caso')
            ->set('diagnosis', 'Diagnóstico inicial')
            ->set('materials', 'Membrana, injerto')
            ->set('results', 'Resultados favorables')
            ->set('video_url', 'https://www.youtube.com/watch?v=abc')
            ->call('save')
            ->assertHasNoErrors();

        $resource = Resource::where('slug', 'caso-clinico-gbr')->first();
        $this->assertNotNull($resource);
        $this->assertSame('Contenido detallado del caso', $resource->content);
        $this->assertSame('Diagnóstico inicial', $resource->diagnosis);
        $this->assertSame('Membrana, injerto', $resource->materials);
        $this->assertSame('Resultados favorables', $resource->results);
        $this->assertTrue((bool) $resource->featured);
        $this->assertSame($type->id, $resource->resource_type_id);

        Livewire::actingAs($admin)
            ->test(ResourceController::class)
            ->call('edit', $resource->id)
            ->set('title', 'Caso clínico GBR Avanzado')
            ->set('content', 'Contenido actualizado')
            ->call('save')
            ->assertHasNoErrors();

        $resource->refresh();
        $this->assertSame('Caso clínico GBR Avanzado', $resource->title);
        $this->assertSame('Contenido actualizado', $resource->content);

        Livewire::actingAs($admin)
            ->test(ResourceController::class)
            ->call('confirmDelete', $resource->id);

        $this->assertDatabaseMissing('resources', ['id' => $resource->id]);
    }
}
