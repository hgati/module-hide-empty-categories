<?php
declare(strict_types=1);

namespace Hgati\HideEmptyCategories\Observer;

use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\ResourceModel\Category\Collection;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class CatalogCategoryCollectionLoadAfter implements ObserverInterface
{
    const CONFIG_PATH = 'hgati_hide_empty_categories/general/enable';

    protected $scopeConfig;

    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @inheritDoc
     */
    public function execute(Observer $observer)
    {
        $isEnabled = $this->getConfigValue();
        if(!$isEnabled) return;

        $visibleCategories = [];

        /** @var Collection $categoryCollection */
        $categoryCollection = $observer->getDataByKey('category_collection');

        /** @var Category $category */
        foreach ($categoryCollection as $category) {
            if ($this->showCategory($category)) {
                $categoryId = $category->getId();
                $visibleCategories[$categoryId] = $categoryId;

                foreach ($category->getParentIds() as $parentId) {
                    $visibleCategories[$parentId] = $parentId;
                }
            }
        }

        foreach ($categoryCollection as $category) {
            if (!array_key_exists($category->getId(), $visibleCategories)) {
                $categoryCollection->removeItemByKey($category->getId());
            }
        }
    }

    /**
     * @param Category $category
     * @return bool
     */
    public function showCategory(Category $category): bool
    {
        return $category->getProductCollection()->count() > 0;
    }

    public function getConfigValue()
    {
        return empty($this->scopeConfig->getValue(self::CONFIG_PATH))?false:true;
    }    
}
