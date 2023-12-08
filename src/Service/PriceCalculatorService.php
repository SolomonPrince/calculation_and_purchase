<?php

namespace App\Service;


use App\Entity\Coupon;
use App\Entity\Tax;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Product;
use App\Form\FormBody;

class PriceCalculatorService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function calculatePrice(FormBody $calculate): ?float
    {

        $product = $this->entityManager->getRepository(Product::class)->find($calculate->getProduct());

        if (!$product) {
            return null;
        }
        $countryShortCode = strtoupper(substr($calculate->getTaxNumber(), 0, 2));
        $tax = $this->entityManager->getRepository(Tax::class)->findOneBy(['shortCode' => $countryShortCode]);
        if (!$tax) {
            return null;
        }
        $coupon = $this->entityManager->getRepository(Coupon::class)->findOneBy(['name' => $calculate->getCouponCode()]);

        if($coupon){
            if($coupon->isIsFixed()){
                $couponSum = $coupon->getValue();
            }else{
                $couponSum = ($tax->getTaxValue() * $coupon->getValue()) / 100;
            }
            $price = ($product->getPrice() - $couponSum) +($tax->getTaxValue() * $product->getPrice()) / 100;
        }else{
            $price = $product->getPrice() + ($tax->getTaxValue() * $product->getPrice()) / 100;
        }
        return $price;
    }

    public function processPayment($product, $taxNumber, $couponCode, $paymentProcessor)
    {
        // Логика проведения оплаты
        // ...

        return true; // или false в случае ошибки оплаты
    }
}