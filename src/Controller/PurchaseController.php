<?php

namespace App\Controller;

use App\Exception\InvalidJsonException;
use App\Exception\ValidateException;
use App\Payments\PaypalPaymentProcessor;
use App\Payments\StripePaymentProcessor;
use App\Service\PriceCalculatorService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PurchaseController extends ParentController
{
    private PriceCalculatorService $priceCalculatorService;

    public function __construct(PriceCalculatorService $priceCalculatorService)
    {
        $this->priceCalculatorService = $priceCalculatorService;
    }
    #[Route('/purchase', methods: 'POST')]
    public function index(Request $request, ValidatorInterface $validator): JsonResponse
    {
        try {
            $formData = $this->processRequest($request, $validator);

            $price = $this->priceCalculatorService->calculatePrice($formData);

            if($price === null){
                return new JsonResponse(['errors' => 'not found information'], 400);
            }

            $paymentProcessor = $formData->getPaymentProcessor();

            // Initialize the chosen payment processor
            $paymentProcessorInstance = $this->getPaymentProcessor($paymentProcessor);

            // Check if the payment processor instance is valid
            if ($paymentProcessorInstance === null) {
                return new JsonResponse(['error' => 'Invalid payment processor specified.'], 400);
            }


            if ($paymentProcessor === 'paypal') {
                $paymentProcessorInstance->pay($this->convertPriceToSmallestUnit($price));
            } elseif ($paymentProcessor === 'stripe') {
                $success = $paymentProcessorInstance->processPayment($price);
                if (!$success) {
                    return new JsonResponse(['error' => 'Payment failed.'], 400);
                }
            } else {
                return new JsonResponse(['error' => 'Invalid payment processor specified.'], 400);
            }

            return new JsonResponse(['answer' => 'payment successfully'], 200);
        } catch (ValidateException $e) {
            $errors = $e->getErrors();
            // Handle the errors
            return new JsonResponse(['errors' => $errors], 400);
        } catch (InvalidJsonException $e){
            return new JsonResponse(['error' => 'Invalid JSON data'], 400);
        } catch (\Exception $e) {
            return new JsonResponse(['errors' => $e], 400);
        }
    }


    private function convertPriceToSmallestUnit(float $price): int
    {
        // Add your logic to convert the price to the smallest currency unit for PayPal
        // For example, if your currency is in dollars, convert to cents (multiply by 100)
        return (int)($price * 100);
    }

    private function getPaymentProcessor(string $paymentProcessor): PaypalPaymentProcessor|StripePaymentProcessor|null
    {
        return match ($paymentProcessor) {
            'paypal' => new PaypalPaymentProcessor(),
            'stripe' => new StripePaymentProcessor(),
            default => null,
        };
    }
}
