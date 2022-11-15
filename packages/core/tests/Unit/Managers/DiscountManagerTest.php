<?php

namespace Lunar\Tests\Unit\Managers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Lunar\Base\DataTransferObjects\CartDiscount;
use Lunar\Base\DiscountManagerInterface;
use Lunar\DiscountTypes\Coupon;
use Lunar\Managers\DiscountManager;
use Lunar\Models\CartLine;
use Lunar\Models\Discount;
use Lunar\Models\ProductVariant;
use Lunar\Tests\Stubs\TestDiscountType;
use Lunar\Tests\TestCase;

/**
 * @group getcandy.discounts
 */
class DiscountManagerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function can_instantiate_manager()
    {
        $manager = app(DiscountManagerInterface::class);
        $this->assertInstanceOf(DiscountManager::class, $manager);
    }

    /**
     * @test
     */
    public function can_fetch_discount_types()
    {
        $manager = app(DiscountManagerInterface::class);

        $this->assertInstanceOf(Collection::class, $manager->getTypes());
    }

    /**
     * @test
     */
    public function can_fetch_applied_discounts()
    {
        $manager = app(DiscountManagerInterface::class);

        $this->assertInstanceOf(Collection::class, $manager->getApplied());
        $this->assertCount(0, $manager->getApplied());
    }

    /**
     * @test
     */
    public function can_add_applied_discount()
    {
        $manager = app(DiscountManagerInterface::class);

        $this->assertInstanceOf(Collection::class, $manager->getApplied());

        $this->assertCount(0, $manager->getApplied());

        ProductVariant::factory()->create();

        $discount = Discount::factory()->create();
        $cartLine = CartLine::factory()->create();

        $discount = new CartDiscount(
            model: $cartLine,
            discount: $discount
        );

        $manager->addApplied($discount);

        $this->assertCount(1, $manager->getApplied());
    }

    /**
     * @test
     */
    public function can_add_new_types()
    {
        $manager = app(DiscountManagerInterface::class);

        $testType = $manager->getTypes()->first(function ($type) {
            return get_class($type) == TestDiscountType::class;
        });

        $this->assertNull($testType);

        $manager->addType(TestDiscountType::class);

        $testType = $manager->getTypes()->first(function ($type) {
            return get_class($type) == TestDiscountType::class;
        });

        $this->assertInstanceOf(TestDiscountType::class, $testType);
    }

    /** @test */
    public function can_validate_coupons()
    {
        $manager = app(DiscountManagerInterface::class);

        Discount::factory()->create([
            'type' => Coupon::class,
            'name' => 'Test Coupon',
            'data' => [
                'coupon' => '10OFF',
                'fixed_value' => false,
                'percentage' => 10,
            ],
        ]);

        $this->assertTrue(
            $manager->validateCoupon('10OFF')
        );

        $this->assertFalse(
            $manager->validateCoupon('20OFF')
        );
    }
}