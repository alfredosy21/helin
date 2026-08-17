<?php

namespace Tests\Feature\Cms;

use App\Http\Controllers\Cms\TestimonialsController;
use App\Models\Testimonial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\Feature\Cms\Concerns\CreatesCmsUsers;
use Tests\TestCase;

class TestimonialsCrudTest extends TestCase
{
    use CreatesCmsUsers;
    use RefreshDatabase;

    public function test_testimonial_crud_flow(): void
    {
        $admin = $this->adminUser();

        Livewire::actingAs($admin)
            ->test(TestimonialsController::class)
            ->call('create')
            ->set('name', 'Dra. Ana Pérez')
            ->set('specialty', 'Odontóloga')
            ->set('content', 'Excelente servicio y productos de calidad.')
            ->call('save')
            ->assertHasNoErrors();

        $testimonial = Testimonial::where('name', 'Dra. Ana Pérez')->first();
        $this->assertNotNull($testimonial);
        $this->assertSame('Odontóloga', $testimonial->specialty);
        $this->assertSame(1, (int) $testimonial->position);

        Livewire::actingAs($admin)
            ->test(TestimonialsController::class)
            ->call('edit', $testimonial->id)
            ->set('content', 'Excelente servicio, productos y soporte.')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame('Excelente servicio, productos y soporte.', $testimonial->refresh()->content);

        Livewire::actingAs($admin)
            ->test(TestimonialsController::class)
            ->call('confirmDelete', $testimonial->id);

        $this->assertDatabaseMissing('testimonials', ['id' => $testimonial->id]);
    }

    public function test_testimonial_requires_all_fields(): void
    {
        $admin = $this->adminUser();

        Livewire::actingAs($admin)
            ->test(TestimonialsController::class)
            ->call('create')
            ->call('save')
            ->assertHasErrors(['name', 'specialty', 'content']);

        $this->assertDatabaseCount('testimonials', 0);
    }
}
