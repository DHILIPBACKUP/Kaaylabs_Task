<?php

namespace Kaaylabs\ProductAttribute\Setup;

use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Framework\Validator\ValidateException;

class InstallData implements InstallDataInterface
{
    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     * @throws LocalizedException
     * @throws ValidateException
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        $attributeSetId = $eavSetup->getAttributeSetId(Product::ENTITY, 'Default'); // You may need to change the attribute set name

        $eavSetup->addAttributeGroup(
            Product::ENTITY,
            $attributeSetId,
            'Gift', // Customize this group name
            50 // Set your preferred sort order
        );

        // Add custom attributes
        $attributes = [
            'gift_for_him',
            'gift_for_her',
            'gift_for_kids',
            'gift_for_home',
            'gift_for_mother',
            'gift_for_father',
        ];

        foreach ($attributes as $attributeCode) {
            $eavSetup->addAttribute(
                Product::ENTITY,
                $attributeCode,
                [
                    'type' => 'int',
                    'label' => ucwords(str_replace('_', ' ', $attributeCode)),
                    'input' => 'boolean',
                    'required' => false,
                    'user_defined' => true,
                    'sort_order' => 8,
                    'group' => 'Gift', // Reference to the custom group name
                    'used_in_product_listing' => true,
                    'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                ]
            );
        }

        $eavSetup->addAttribute(
            Product::ENTITY,
            'product_category_filter',
            [
                'type' => 'varchar',
                'label' => 'Product Category Filter',
                'input' => 'select',
                'required' => false,
                'sort_order' => 27,
                'user_defined' => true,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Gift',
                'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Table',
                'option' => [
                    'values' => [
                        'Personal Care',
                        'Food and Beverages',
                        'Stationery'
                    ],
                ],
            ]
        );

    }
}
