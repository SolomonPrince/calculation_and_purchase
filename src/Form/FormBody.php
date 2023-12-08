<?php

namespace App\Form;

use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as CustomAssert;

class FormBody
{
    #[Assert\NotNull(message: 'Product is required')]
    #[Assert\Type(
        type: 'integer',
        message: 'The value {{ value }} is not a valid {{ type }}.',
    )]
    private int $product;

    #[Assert\NotNull(message: 'Tax number is required')]
    #[CustomAssert\TaxNumber]
    private string $taxNumber;

    #[Assert\Type(
        type: 'string',
        message: 'The value {{ value }} is not a valid {{ type }}.',
    )]
    private ?string $couponCode;


    #[Assert\Type(
        type: 'string',
        message: 'The value {{ value }} is not a valid {{ type }}.',
    )]
    private ?string $paymentProcessor;

    public function setProduct(?int $product): static
    {
        if($product){
            $this->product = $product;
        }
        return $this;
    }

    public function getProduct(): int
    {
        return $this->product;
    }

    public function getTaxNumber(): string
    {
        return $this->taxNumber;
    }

    public function setTaxNumber(?string $taxNumber): static
    {
        if($taxNumber){
            $this->taxNumber = $taxNumber;
        }
        return $this;
    }

    public function setCouponCode(?string $couponCode): static
    {
        $this->couponCode = $couponCode;
        return $this;
    }

    public function getCouponCode(): ?string
    {
        return $this->couponCode;
    }

    public function getPaymentProcessor(): ?string
    {
        return $this->paymentProcessor;
    }

    public function setPaymentProcessor(?string $paymentProcessor): static
    {
        $this->paymentProcessor = $paymentProcessor;
        return $this;
    }


}