<?php

namespace WebSummerCamp\Tests\WebShop;

use Money\Currency;
use Money\Money;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use WebSummerCamp\WebShop\ComboProduct;
use WebSummerCamp\WebShop\PhysicalProduct;

class ComboProductTest extends TestCase
{
    public function testComplexComboProductWithoutCustomPrice(): void
    {
        $products = [
            new PhysicalProduct(
                Uuid::uuid4(),
                new Money(12000, new Currency('EUR')),
                'WebSummerCamp'
            ),
            new ComboProduct(Uuid::uuid4(), 'Nested Combo', [
                new PhysicalProduct(
                    Uuid::uuid4(),
                    new Money(9000, new Currency('EUR')),
                    'WebSummerCamp'
                ),
                new PhysicalProduct(
                    Uuid::uuid4(),
                    new Money(8000, new Currency('EUR')),
                    'WebSummerCamp'
                )
            ])
        ];

        $combo = new ComboProduct(
            Uuid::uuid4(),
            'Test',
            $products
        );

        $this->assertEquals(
            new Money(29000, new Currency('EUR')),
            $combo->getUnitPrice()
        );
    }

    public function testComboProductWithCustomPrice(): void
    {
        $products = [
            new PhysicalProduct(
                Uuid::uuid4(),
                new Money(12000, new Currency('EUR')),
                'WebSummerCamp'
            ),
            new PhysicalProduct(
                Uuid::uuid4(),
                new Money(9000, new Currency('EUR')),
                'WebSummerCamp'
            )
        ];

        $combo = new ComboProduct(
            Uuid::uuid4(),
            'Test',
            $products,
            new Money(14500, new Currency('EUR'))
        );

        $this->assertEquals(
            new Money(14500, new Currency('EUR')),
            $combo->getUnitPrice()
        );
    }

    /**
     * @expectedException \Assert\AssertionFailedException
     */
    public function testInvalidComboProduct(): void
    {
        new ComboProduct(Uuid::uuid4(), 'Test', [
            new PhysicalProduct(
                Uuid::uuid4(),
                new Money(12000, new Currency('EUR')),
                'WebSummerCamp'
            )
        ]);
    }

    public function testSinglePhysicalProduct(): void
    {
        $product = new PhysicalProduct(
            Uuid::fromString('de04316c-d3d4-4708-b4a7-9a74d78b5ee5'),
            new Money(12000, new Currency('EUR')),
            'WebSummerCamp'
        );

        $this->assertEquals(
            Uuid::fromString('de04316c-d3d4-4708-b4a7-9a74d78b5ee5'),
            $product->getSku()
        );

        $this->assertEquals(
            new Money(12000, new Currency('EUR')),
            $product->getUnitPrice()
        );

        $this->assertSame('WebSummerCamp', $product->getName());
    }
}