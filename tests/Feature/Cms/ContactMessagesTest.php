<?php

namespace Tests\Feature\Cms;

use App\Http\Controllers\Cms\ContactMessagesController;
use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\Feature\Cms\Concerns\CreatesCmsUsers;
use Tests\TestCase;

class ContactMessagesTest extends TestCase
{
    use CreatesCmsUsers;
    use RefreshDatabase;

    private function message(): ContactMessage
    {
        return ContactMessage::create([
            'nombre' => 'María Rodríguez',
            'email' => 'maria@test.com',
            'telefono' => '04121234567',
            'asunto' => 'Consulta sobre implantes',
            'mensaje' => 'Me gustaría recibir información sobre los implantes dentales.',
        ]);
    }

    public function test_contact_message_marks_as_read_on_details_view(): void
    {
        $admin = $this->adminUser();
        $message = $this->message();

        Livewire::actingAs($admin)
            ->test(ContactMessagesController::class)
            ->call('viewDetails', $message->id)
            ->assertSet('showDetails', true)
            ->assertSet('selectedMessage.id', $message->id);

        $this->assertTrue($message->refresh()->is_read);
    }

    public function test_contact_message_toggle_and_delete(): void
    {
        $admin = $this->adminUser();
        $message = $this->message();

        Livewire::actingAs($admin)
            ->test(ContactMessagesController::class)
            ->call('toggleRead', $message->id)
            ->assertDispatched('toast');

        $this->assertTrue($message->refresh()->is_read);

        Livewire::actingAs($admin)
            ->test(ContactMessagesController::class)
            ->call('delete', $message->id);

        $this->assertDatabaseMissing('contact_messages', ['id' => $message->id]);
    }

    public function test_contact_form_persists_message_in_database(): void
    {
        Mail::fake();
        $this->defaultSettings();

        $response = $this->post('/contactanos/send', [
            'nombre' => 'Carlos Pérez',
            'email' => 'carlos@test.com',
            'telefono' => '04141234567',
            'asunto' => 'Cotización',
            'mensaje' => 'Quiero una cotización de materiales para mi clínica.',
        ]);

        $response->assertOk()->assertJson(['success' => true]);
        $this->assertDatabaseHas('contact_messages', [
            'nombre' => 'Carlos Pérez',
            'email' => 'carlos@test.com',
            'asunto' => 'Cotización',
            'is_read' => 0,
        ]);
    }

    public function test_contact_form_validates_required_fields(): void
    {
        $this->defaultSettings();

        $this->post('/contactanos/send', [
            'nombre' => '',
            'email' => 'invalid-email',
            'asunto' => '',
            'mensaje' => 'x',
        ])->assertStatus(422);

        $this->assertDatabaseCount('contact_messages', 0);
    }
}
