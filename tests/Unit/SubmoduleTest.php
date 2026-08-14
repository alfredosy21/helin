<?php

namespace Tests\Unit;

use App\Models\Submodule;
use PHPUnit\Framework\TestCase;

class SubmoduleTest extends TestCase
{
    public function test_critical_submodule_constants_match_database_ids(): void
    {
        $this->assertSame(1, Submodule::USERS);
        $this->assertSame(2, Submodule::ROLES);
        $this->assertSame(3, Submodule::GENERAL_SETTINGS);
        $this->assertSame(8, Submodule::PRODUCT_FAMILIES);
        $this->assertSame(11, Submodule::SYSTEM_PRODUCTS);
        $this->assertSame(12, Submodule::PRODUCT_PLATFORMS);
        $this->assertSame(16, Submodule::CLINICAL_RESOURCES);
        $this->assertSame(17, Submodule::RESOURCE_TYPES);
        $this->assertSame(18, Submodule::RESOURCE_SPECIALTIES);
        $this->assertSame(24, Submodule::WHATSAPP_NUMBERS);
        $this->assertSame(27, Submodule::COMMERCIAL_REQUESTS);
        $this->assertSame(28, Submodule::PAGE_SEO);
    }

    public function test_submodule_constants_are_unique(): void
    {
        $reflection = new \ReflectionClass(Submodule::class);
        $ids = array_values($reflection->getConstants());

        $this->assertSame(count($ids), count(array_unique($ids)), 'There are duplicated submodule IDs.');
        $this->assertNotEmpty($ids);
    }
}