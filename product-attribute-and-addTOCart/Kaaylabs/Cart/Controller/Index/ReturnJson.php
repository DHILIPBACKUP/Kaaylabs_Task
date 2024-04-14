<?php

namespace Kaaylabs\Cart\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Checkout\Model\Cart;

class ReturnJson extends Action
{
    /**
     * @var JsonFactory
     */
    protected $jsonFactory;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var Cart
     */
    protected $cart;

    /**
     * @param Context $context
     * @param JsonFactory $jsonFactory
     * @param ProductRepositoryInterface $productRepository
     * @param Cart $cart
     */
    public function __construct(
        Context                    $context,
        JsonFactory                $jsonFactory,
        ProductRepositoryInterface $productRepository,
        Cart                       $cart
    )
    {
        parent::__construct($context);
        $this->jsonFactory = $jsonFactory;
        $this->productRepository = $productRepository;
        $this->cart = $cart;
    }

    /**
     * @return Json
     */
    public function execute(): Json
    {
        $productId = $this->getRequest()->getParam('product_id');

        try {
            $product = $this->productRepository->getById($productId);
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            return $this->jsonFactory->create()->setData(['success' => false, 'message' => __('Product not found.')]);
        }

        try {
            $this->cart->addProduct($product, ['qty' => 1]);
            $this->cart->save();
            return $this->jsonFactory->create()->setData(['success' => true, 'message' => __('Product added to cart successfully.')]);
        } catch (\Exception $e) {
            return $this->jsonFactory->create()->setData(['success' => false, 'message' => __('Error adding product to cart.')]);
        }
    }
}
