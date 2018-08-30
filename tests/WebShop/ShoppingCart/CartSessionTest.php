<?php

namespace WebSummerCamp\Tests\WebShop\ShoppingCart;

use Money\Currency;
use Money\Money;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use WebSummerCamp\EventSourcing\EventStore;
use WebSummerCamp\EventSourcing\FilesystemEventStorage;
use WebSummerCamp\EventSourcing\InMemoryEventStorage;
use WebSummerCamp\WebShop\ShoppingCart\CartSession;
use WebSummerCamp\WebShop\ShoppingCart\CartSessionId;
use WebSummerCamp\WebShop\PhysicalProduct;
use WebSummerCamp\WebShop\Product;

class CartSessionTest extends TestCase
{
    public function testRecordEvents(): void
    {
        $product1 = $this->createProduct('ed2a0d4a-a987-4c74-8521-7336991056e7', 'Nintendo Switch', 34900);
        $product2 = $this->createProduct('ccd110b4-28cd-4c2a-98f2-3cdb95c3c101', 'Nintendo Game Pad', 7900);
        $product3 = $this->createProduct('4723fdfd-ca2a-449e-a704-c5c4ecfe391d', 'Memory Card', 2900);

        $sessionId = CartSessionId::fromString('CartSession_de04316c-d3d4-4708-b4a7-9a74d78b5ee5');

        $session = CartSession::initiate($sessionId);

        $session->add($product1, 1);
        $session->add($product2, 2);
        $session->add($product3, 5);
        $session->empty();

        $this->assertCount(5, $session->getRecordedEvents());

        //$store = new EventStore(new FilesystemEventStorage('/tmp/event-store'));
        $store = new EventStore(new InMemoryEventStorage());
        $store->store($session->getRecordedEvents());

        $newSession = CartSession::fromEventStream($sessionId, $store->getStream($sessionId));

        $this->assertTrue($newSession->initiated);
        $this->assertArrayHasKey('ccd110b4-28cd-4c2a-98f2-3cdb95c3c101', $newSession->content);
    }

    private function createProduct(string $sku, string $name, int $amount): Product
    {
        return new PhysicalProduct(
            Uuid::fromString($sku),
            new Money($amount, new Currency('EUR')),
            $name
        );
    }
}