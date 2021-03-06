<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace Tests\BitBag\SyliusVueStorefrontPlugin\Controller\Cart;

use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tests\BitBag\SyliusVueStorefrontPlugin\Controller\JsonApiTestCase;
use Tests\BitBag\SyliusVueStorefrontPlugin\Controller\Utils\UserLoginTrait;

final class DeleteCartActionTest extends JsonApiTestCase
{
    use UserLoginTrait;

    public function test_deleting_cart_item(): void
    {
        $this->loadFixturesFromFiles([
            'channel.yaml',
            'customer.yaml',
            'order.yaml',
            'order_item.yaml',
            'coupon_based_promotion.yaml',
            'product_with_attributes.yaml',
        ]);

        $this->authenticateUser('test@example.com', 'MegaSafePassword');

        $uri = sprintf(
            '/vsbridge/cart/delete?token=%s&cartId=%s',
            $this->token,
            12345
        );

        $orderRepository = $this->client->getContainer()->get('sylius.repository.order');
        $orderItemRepository = $this->client->getContainer()->get('sylius.repository.order_item');

        /** @var OrderInterface $order */
        $order = $orderRepository->findOneBy(['tokenValue' => '12345']);

        /** @var OrderItemInterface $orderItem */
        $orderItem = $orderItemRepository->findOneByOrder($order);

        $itemId = $orderItem->getId();

        $requestBody =
<<<JSON
        { 
            "cartItem": 
                { 
                    "sku": "RANDOM_JACKET_CODE",
                    "qty": 1,
                    "item_id": $itemId,
                    "quoteId": "12345" 
                }
        }
JSON;

        $this->request(Request::METHOD_POST, $uri, self::JSON_REQUEST_HEADERS, $requestBody);

        $response = $this->client->getResponse();

        $this->assertResponse($response, 'Controller/Cart/delete_cart_item_successful');
    }

    public function test_deleting_cart_item_for_blank_item(): void
    {
        $this->loadFixturesFromFiles(['channel.yaml', 'customer.yaml', 'order.yaml', 'coupon_based_promotion.yaml']);

        $this->authenticateUser('test@example.com', 'MegaSafePassword');

        $uri = sprintf(
            '/vsbridge/cart/delete?token=%s&cartId=%s',
            $this->token,
            12345
        );

        $this->request(Request::METHOD_POST, $uri, self::JSON_REQUEST_HEADERS);

        $response = $this->client->getResponse();

        $this->assertResponse($response, 'Controller/Cart/delete_cart_item_blank_item', Response::HTTP_BAD_REQUEST);
    }

    public function test_deleting_cart_item_for_non_existent_item(): void
    {
        $this->loadFixturesFromFiles(['channel.yaml', 'customer.yaml', 'order.yaml', 'coupon_based_promotion.yaml']);

        $this->authenticateUser('test@example.com', 'MegaSafePassword');

        $uri = sprintf(
            '/vsbridge/cart/delete?token=%s&cartId=%s',
            $this->token,
            12345
        );

        $requestBody =
<<<JSON
        { 
            "cartItem": 
                { 
                    "sku": "Non-existent item",
                    "qty": 2,
                    "quoteId": "12345" 
                }
        }
JSON;

        $this->request(Request::METHOD_POST, $uri, self::JSON_REQUEST_HEADERS, $requestBody);

        $response = $this->client->getResponse();

        $this->assertResponse($response, 'Controller/Cart/delete_cart_item_non_existent_item', Response::HTTP_BAD_REQUEST);
    }

    public function test_deleting_cart_item_for_invalid_token(): void
    {
        $this->loadFixturesFromFiles(['channel.yaml', 'customer.yaml', 'order.yaml', 'coupon_based_promotion.yaml']);

        $uri = sprintf(
            '/vsbridge/cart/delete?token=%s&cartId=%s',
            12345,
            12345
        );

        $this->request(Request::METHOD_POST, $uri, self::JSON_REQUEST_HEADERS);

        $response = $this->client->getResponse();

        $this->assertResponse($response, 'Controller/Cart/Common/invalid_token', Response::HTTP_UNAUTHORIZED);
    }

    public function test_deleting_cart_item_for_invalid_cart(): void
    {
        $this->loadFixturesFromFiles(['channel.yaml', 'customer.yaml', 'order.yaml', 'coupon_based_promotion.yaml']);

        $this->authenticateUser('test@example.com', 'MegaSafePassword');

        $uri = sprintf(
            '/vsbridge/cart/delete?token=%s&cartId=%s',
            $this->token,
            123
        );

        $requestBody =
<<<JSON
        { 
            "cartItem": 
                { 
                    "sku": "Non-existent item",
                    "qty": 2,
                    "quoteId": "123" 
                }
        }
JSON;

        $this->request(Request::METHOD_POST, $uri, self::JSON_REQUEST_HEADERS, $requestBody);

        $response = $this->client->getResponse();

        $this->assertResponse($response, 'Controller/Cart/Common/invalid_cart', Response::HTTP_BAD_REQUEST);
    }
}
