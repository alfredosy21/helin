<?php

namespace Tests\Feature\Cms;

use App\Http\Controllers\Cms\CommercialRequestsController;
use App\Models\City;
use App\Models\CommercialRequest;
use App\Models\CustomerType;
use App\Models\DeliveryMethod;
use App\Models\PaymentMethod;
use App\Models\State;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\Feature\Cms\Concerns\CreatesCmsUsers;
use Tests\TestCase;

class CommercialRequestsTest extends TestCase
{
    use CreatesCmsUsers;
    use RefreshDatabase;

    private function request(): CommercialRequest
    {
        $state = State::create(['name' => 'Miranda', 'code' => 'MI']);
        $city = City::create(['name' => 'Los Teques', 'state_id' => $state->id]);
        $customerType = CustomerType::forceCreate(['name' => 'Clínica Dental', 'slug' => 'clinica-dental']);
        $deliveryMethod = DeliveryMethod::forceCreate(['name' => 'MRW', 'slug' => 'mrw']);
        $paymentMethod = PaymentMethod::forceCreate(['name' => 'Transferencia']);

        return CommercialRequest::create([
            'customer_type_id' => $customerType->id,
            'first_name' => 'Gabriel',
            'last_name' => 'Montes',
            'phone' => '04121234567',
            'email' => 'gabriel@test.com',
            'state_id' => $state->id,
            'city_id' => $city->id,
            'address' => 'Av. Bolívar',
            'delivery_method_id' => $deliveryMethod->id,
            'payment_method_id' => $paymentMethod->id,
            'cart_data' => [],
            'status' => 'pending',
        ]);
    }

    public function test_requests_can_change_status(): void
    {
        $admin = $this->adminUser();
        $request = $this->request();

        Livewire::actingAs($admin)
            ->test(CommercialRequestsController::class)
            ->call('updateStatus', $request->id, 'completed');

        $this->assertSame('completed', $request->refresh()->status);
    }

    public function test_requests_can_be_deleted(): void
    {
        $admin = $this->adminUser();
        $request = $this->request();

        Livewire::actingAs($admin)
            ->test(CommercialRequestsController::class)
            ->call('delete', $request->id);

        $this->assertSoftDeleted('commercial_requests', ['id' => $request->id]);
    }

    public function test_requests_can_open_and_close_details(): void
    {
        $admin = $this->adminUser();
        $request = $this->request();

        Livewire::actingAs($admin)
            ->test(CommercialRequestsController::class)
            ->call('viewDetails', $request->id)
            ->assertSet('selectedRequest.id', $request->id)
            ->assertSet('showDetails', true)
            ->call('closeDetails')
            ->assertSet('showDetails', false);
    }
}
