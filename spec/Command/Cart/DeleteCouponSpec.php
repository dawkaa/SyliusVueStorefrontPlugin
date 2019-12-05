<?php

declare(strict_types=1);

namespace spec\BitBag\SyliusVueStorefrontPlugin\Command\Cart;

use BitBag\SyliusVueStorefrontPlugin\Command\Cart\DeleteCoupon;
use PhpSpec\ObjectBehavior;

class DeleteCouponSpec extends ObjectBehavior
{
    private const TOKEN = 'token';
    private const CART_ID = "delete-coupon-spec";

    public function let(): void
    {
        $this->beConstructedWith(
            self::TOKEN,
            self::CART_ID
        );
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(DeleteCoupon::class);
    }

    public function it_allows_access_via_properties(): void
    {
        $this->token()->shouldReturn(self::TOKEN);
        $this->cartId()->shouldReturn(self::CART_ID);
    }
}
