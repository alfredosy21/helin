<?php

namespace Tests\Feature\Cms;

use App\Http\Controllers\Cms\CustomerTypesController;
use App\Http\Controllers\Cms\DeliveryMethodsController;
use App\Http\Controllers\Cms\MenuController;
use App\Http\Controllers\Cms\PaymentMethodController;
use App\Http\Controllers\Cms\SectionController;
use App\Http\Controllers\Cms\WhatsAppNumbersController;
use App\Models\CustomerType;
use App\Models\DeliveryMethod;
use App\Models\Menus;
use App\Models\PaymentMethod;
use App\Models\Sections;
use App\Models\State;
use App\Models\WhatsAppNumber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\Feature\Cms\Concerns\CreatesCmsUsers;
use Tests\TestCase;

class ConfigCrudTest extends TestCase
{
    use CreatesCmsUsers;
    use RefreshDatabase;

    public function test_section_edit_update_and_delete_flow(): void
    {
        $admin = $this->adminUser();

        $section = Sections::forceCreate([
            'title' => 'Hero Home',
            'content' => 'Bienvenidos a Helin',
            'layout_type' => 'hero_badges',
            'icon_style' => 'emoji',
            'status' => 1,
            'status_content' => 1,
        ]);

        Livewire::actingAs($admin)
            ->test(SectionController::class)
            ->call('edit', $section->id)
            ->assertSet('title', 'Hero Home')
            ->set('title', 'Hero Home Actualizado')
            ->set('subtitle', 'Nuevo subtítulo')
            ->call('update')
            ->assertHasNoErrors();

        $this->assertSame('Hero Home Actualizado', $section->refresh()->title);
        $this->assertSame('Nuevo subtítulo', $section->subtitle);

        Livewire::actingAs($admin)
            ->test(SectionController::class)
            ->call('confirmDelete', $section->id);

        $this->assertDatabaseMissing('sections', ['id' => $section->id]);
    }

    public function test_payment_method_crud_flow(): void
    {
        $admin = $this->adminUser();

        Livewire::actingAs($admin)
            ->test(PaymentMethodController::class)
            ->call('create')
            ->set('name', 'Transferencia Bancaria')
            ->set('description', 'Pago por transferencia')
            ->call('save')
            ->assertHasNoErrors();

        $method = PaymentMethod::where('name', 'Transferencia Bancaria')->first();
        $this->assertNotNull($method);

        Livewire::actingAs($admin)
            ->test(PaymentMethodController::class)
            ->call('edit', $method->id)
            ->set('name', 'Transferencia Bancaria Nacional')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame('Transferencia Bancaria Nacional', $method->refresh()->name);

        Livewire::actingAs($admin)
            ->test(PaymentMethodController::class)
            ->call('confirmDelete', $method->id);

        $this->assertDatabaseMissing('payment_methods', ['id' => $method->id]);
    }

    public function test_customer_type_crud_flow(): void
    {
        $admin = $this->adminUser();

        Livewire::actingAs($admin)
            ->test(CustomerTypesController::class)
            ->call('create')
            ->set('name', 'Clínica Dental')
            ->set('description', 'Clientes clínicos')
            ->call('save')
            ->assertHasNoErrors();

        $type = CustomerType::where('name', 'Clínica Dental')->first();
        $this->assertNotNull($type);
        $this->assertSame('clinica-dental', $type->slug);

        Livewire::actingAs($admin)
            ->test(CustomerTypesController::class)
            ->call('edit', $type->id)
            ->set('name', 'Clínica Dental Privada')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame('Clínica Dental Privada', $type->refresh()->name);

        Livewire::actingAs($admin)
            ->test(CustomerTypesController::class)
            ->call('confirmDelete', $type->id);

        $this->assertDatabaseMissing('customer_types', ['id' => $type->id]);
    }

    public function test_delivery_method_crud_flow(): void
    {
        $admin = $this->adminUser();

        Livewire::actingAs($admin)
            ->test(DeliveryMethodsController::class)
            ->call('create')
            ->set('name', 'Envío MRW')
            ->call('save')
            ->assertHasNoErrors();

        $method = DeliveryMethod::where('name', 'Envío MRW')->first();
        $this->assertNotNull($method);
        $this->assertSame('envio-mrw', $method->slug);

        Livewire::actingAs($admin)
            ->test(DeliveryMethodsController::class)
            ->call('edit', $method->id)
            ->set('name', 'Envío MRW Express')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame('Envío MRW Express', $method->refresh()->name);

        Livewire::actingAs($admin)
            ->test(DeliveryMethodsController::class)
            ->call('confirmDelete', $method->id);

        $this->assertDatabaseMissing('delivery_methods', ['id' => $method->id]);
    }

    public function test_whatsapp_number_crud_and_toggle_flow(): void
    {
        $admin = $this->adminUser();
        $state = State::create(['name' => 'Distrito Capital', 'code' => 'DC']);

        Livewire::actingAs($admin)
            ->test(WhatsAppNumbersController::class)
            ->call('create')
            ->set('phone_number', '584244669150')
            ->set('executive_name', 'Sofía Martínez')
            ->set('state_id', $state->id)
            ->call('save')
            ->assertHasNoErrors();

        $number = WhatsAppNumber::where('phone_number', '584244669150')->first();
        $this->assertNotNull($number);
        $this->assertSame($state->id, $number->state_id);

        Livewire::actingAs($admin)
            ->test(WhatsAppNumbersController::class)
            ->call('toggle', $number->id);

        $this->assertFalse((bool) $number->refresh()->is_active);

        Livewire::actingAs($admin)
            ->test(WhatsAppNumbersController::class)
            ->call('edit', $number->id)
            ->set('executive_name', 'Sofía Martínez Gómez')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame('Sofía Martínez Gómez', $number->refresh()->executive_name);

        Livewire::actingAs($admin)
            ->test(WhatsAppNumbersController::class)
            ->call('confirmDelete', $number->id);

        $this->assertDatabaseMissing('whatsapp_numbers', ['id' => $number->id]);
    }

    public function test_menu_crud_toggle_and_delete_flow(): void
    {
        $admin = $this->adminUser();

        Livewire::actingAs($admin)
            ->test(MenuController::class)
            ->call('create')
            ->set('title', 'Productos')
            ->set('url', '/catalog')
            ->set('type', 1)
            ->call('save')
            ->assertHasNoErrors();

        $menu = Menus::where('title', 'Productos')->first();
        $this->assertNotNull($menu);

        Livewire::actingAs($admin)
            ->test(MenuController::class)
            ->call('edit', $menu->id)
            ->set('title', 'Catálogo Completo')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame('Catálogo Completo', $menu->refresh()->title);

        Livewire::actingAs($admin)
            ->test(MenuController::class)
            ->call('toggleStatus', $menu->id);

        $this->assertFalse((bool) $menu->refresh()->status);

        Livewire::actingAs($admin)
            ->test(MenuController::class)
            ->call('delete', $menu->id);

        $this->assertDatabaseMissing('menus', ['id' => $menu->id]);
    }
}
