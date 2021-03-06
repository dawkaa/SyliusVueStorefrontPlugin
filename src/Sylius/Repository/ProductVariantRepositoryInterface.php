<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusVueStorefrontPlugin\Sylius\Repository;

use BitBag\SyliusVueStorefrontPlugin\Model\Request\Cart\CartItem\ConfigurableItemOption;
use Sylius\Component\Core\Model\ProductVariantInterface;

interface ProductVariantRepositoryInterface
{
    /** @param ConfigurableItemOption[] $configurableItemOptions */
    public function getVariantForOptionValuesBySku(string $sku, array $configurableItemOptions): ?ProductVariantInterface;
}
