<?php

namespace Tests\Feature\Cms;

use App\Http\Controllers\Cms\AttributesController;
use App\Http\Controllers\Cms\AttributeValuesController;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\Feature\Cms\Concerns\CreatesCmsUsers;
use Tests\TestCase;

class AttributesCrudTest extends TestCase
{
    use CreatesCmsUsers;
    use RefreshDatabase;

    public function test_attribute_crud_toggle_and_delete_flow(): void
    {
        $admin = $this->adminUser();

        Livewire::actingAs($admin)
            ->test(AttributesController::class)
            ->call('create')
            ->set('name', 'Diámetro')
            ->set('type', 'select')
            ->set('options', "3.3mm\n4.1mm\n4.8mm")
            ->call('save')
            ->assertHasNoErrors();

        $attribute = Attribute::where('name', 'Diámetro')->first();
        $this->assertNotNull($attribute);
        $this->assertSame('select', $attribute->type);
        $this->assertSame('diametro', $attribute->slug);

        Livewire::actingAs($admin)
            ->test(AttributesController::class)
            ->call('edit', $attribute->id)
            ->set('name', 'Diámetro del Implante')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame('Diámetro del Implante', $attribute->refresh()->name);

        Livewire::actingAs($admin)
            ->test(AttributesController::class)
            ->call('toggle', $attribute->id);

        $this->assertFalse((bool) $attribute->refresh()->is_active);

        Livewire::actingAs($admin)
            ->test(AttributesController::class)
            ->call('confirmDelete', $attribute->id);

        $this->assertDatabaseMissing('attributes', ['id' => $attribute->id]);
    }

    public function test_attribute_rejects_invalid_type(): void
    {
        $admin = $this->adminUser();

        Livewire::actingAs($admin)
            ->test(AttributesController::class)
            ->call('create')
            ->set('name', 'Material')
            ->set('type', 'invalid_type')
            ->call('save')
            ->assertHasErrors(['type']);

        $this->assertDatabaseCount('attributes', 0);
    }

    public function test_attribute_value_crud_flow(): void
    {
        $admin = $this->adminUser();
        $attribute = Attribute::forceCreate([
            'name' => 'Diámetro',
            'slug' => 'diametro',
            'type' => 'select',
            'is_active' => 1,
        ]);

        Livewire::actingAs($admin)
            ->test(AttributeValuesController::class)
            ->call('create')
            ->set('attribute_id', $attribute->id)
            ->set('value', '3.3')
            ->set('label', '3.3 mm')
            ->call('save')
            ->assertHasNoErrors();

        $value = AttributeValue::where('value', '3.3')->first();
        $this->assertNotNull($value);
        $this->assertSame($attribute->id, $value->attribute_id);
        $this->assertSame('3.3 mm', $value->label);

        Livewire::actingAs($admin)
            ->test(AttributeValuesController::class)
            ->call('edit', $value->id)
            ->set('label', '3.3 mm Estándar')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame('3.3 mm Estándar', $value->refresh()->label);

        Livewire::actingAs($admin)
            ->test(AttributeValuesController::class)
            ->call('toggle', $value->id);

        $this->assertFalse((bool) $value->refresh()->is_active);

        Livewire::actingAs($admin)
            ->test(AttributeValuesController::class)
            ->call('confirmDelete', $value->id);

        $this->assertDatabaseMissing('attribute_values', ['id' => $value->id]);
    }

    public function test_attribute_value_requires_value(): void
    {
        $admin = $this->adminUser();
        $attribute = Attribute::forceCreate([
            'name' => 'Diámetro',
            'slug' => 'diametro',
            'type' => 'select',
            'is_active' => 1,
        ]);

        Livewire::actingAs($admin)
            ->test(AttributeValuesController::class)
            ->call('create')
            ->set('attribute_id', $attribute->id)
            ->call('save')
            ->assertHasErrors(['value']);

        $this->assertDatabaseCount('attribute_values', 0);
    }
}
