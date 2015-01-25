<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Bundle\Test\Constraint;

use Magento\Bundle\Test\Fixture\BundleProduct;
use Magento\Catalog\Test\Fixture\Category;
use Magento\Catalog\Test\Page\Category\CatalogCategoryView;
use Magento\Cms\Test\Page\CmsIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Check bundle product on the category page.
 */
class AssertBundleInCategory extends AbstractConstraint
{
    /* tags */
    const SEVERITY = 'low';
    /* end tags */

    /**
     * Check bundle product on the category page.
     *
     * @param CatalogCategoryView $catalogCategoryView
     * @param CmsIndex $cmsIndex
     * @param BundleProduct $product
     * @param Category $category
     * @return void
     */
    public function processAssert(
        CatalogCategoryView $catalogCategoryView,
        CmsIndex $cmsIndex,
        BundleProduct $product,
        Category $category
    ) {
        //Open category view page
        $cmsIndex->open();
        $cmsIndex->getTopmenu()->selectCategoryByName($category->getName());

        //Process asserts
        $this->assertPrice($product, $catalogCategoryView);
    }

    /**
     * Verify product price on category view page.
     *
     * @param BundleProduct $bundle
     * @param CatalogCategoryView $catalogCategoryView
     * @return void
     */
    protected function assertPrice(BundleProduct $bundle, CatalogCategoryView $catalogCategoryView)
    {
        $priceData = $bundle->getDataFieldConfig('price')['source']->getPreset();
        //Price from/to verification
        $priceBlock = $catalogCategoryView->getListProductBlock()->getProductPriceBlock($bundle->getName());

        if ($bundle->hasData('special_price') || $bundle->hasData('group_price')) {
            $priceLow = $priceBlock->getFinalPrice();
        } else {
            $priceLow = ($bundle->getPriceView() == 'Price Range')
                ? $priceBlock->getPriceFrom()
                : $priceBlock->getRegularPrice();
        }

        \PHPUnit_Framework_Assert::assertEquals(
            $priceData['price_from'],
            $priceLow,
            'Bundle price From on category page is not correct.'
        );
        if ($bundle->getPriceView() == 'Price Range') {
            \PHPUnit_Framework_Assert::assertEquals(
                $priceData['price_to'],
                $priceBlock->getPriceTo(),
                'Bundle price To on category page is not correct.'
            );
        }
    }

    /**
     * Text of Visible in category assert.
     *
     * @return string
     */
    public function toString()
    {
        return 'Bundle price on category page is not correct.';
    }
}
