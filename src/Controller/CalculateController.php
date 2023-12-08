<?php

namespace App\Controller;

use App\Exception\InvalidJsonException;
use App\Exception\ValidateException;
use App\Service\PriceCalculatorService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CalculateController extends ParentController
{
    private PriceCalculatorService $priceCalculatorService;

    public function __construct(PriceCalculatorService $priceCalculatorService)
    {
        $this->priceCalculatorService = $priceCalculatorService;
    }

    /**
     */
    #[Route('/calculate-price', methods: 'POST')]
    public function index(Request $request, ValidatorInterface $validator): JsonResponse
    {
        try {
            $formData = $this->processRequest($request, $validator);

            $price = $this->priceCalculatorService->calculatePrice($formData);

            if($price === null){
                return new JsonResponse(['errors' => 'not found information'], 400);
            }

            return new JsonResponse(['price' => $price], 200);
        } catch (ValidateException $e) {
            $errors = $e->getErrors();
            // Handle the errors
            return new JsonResponse(['errors' => $errors], 400);
        } catch (InvalidJsonException $e){
            return new JsonResponse(['error' => 'Invalid JSON data'], 400);
        }
    }
}
