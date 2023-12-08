<?php

namespace App\DataFixtures;

use App\Entity\Coupon;
use App\Entity\Product;
use App\Entity\Tax;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $productName = ['Iphone', 'Наушники', 'Чехол'];
        $productPrice = [100, 20, 10];
        foreach ($productName as $key => $productItem){
            $product = new Product();
            $product->setName($productItem);
            $product->setPrice($productPrice[$key]);
            $manager->persist($product);
        }

      $couponMane = ['D15'];
      $couponFixed = [false];
      $couponValue = [15];

      foreach ($couponMane as $key => $couponItem){
          $coupon = new Coupon();
          $coupon->setName($couponItem);
          $coupon->setValue($couponValue[$key]);
          $coupon->setIsFixed($couponFixed[$key]);
          $manager->persist($coupon);
      }

      $countryNames = ['Германии', 'Италии', 'Франции', 'Греции'];
      $taxValues = [19, 22, 20, 24];
      $shortCodes = ['DE', 'IT', 'FR', 'GR'];
      foreach ($countryNames as $key => $countryName){
          $tax = new Tax();
          $tax->setCountryName($countryName);
          $tax->setTaxValue($taxValues[$key]);
          $tax->setShortCode($shortCodes[$key]);
          $manager->persist($tax);
      }



        $manager->flush();
    }
}
