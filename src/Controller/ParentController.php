<?php

namespace App\Controller;


use App\Exception\InvalidJsonException;
use App\Exception\ValidateException;
use App\Form\FormBody;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ParentController extends AbstractController
{
    protected function getFormErrors(ValidatorInterface $validator, $formData): array
    {
        $violations = $validator->validate($formData);

        if (count($violations) > 0) {
            // Handle validation errors
            $errors = [];
            foreach ($violations as $violation) {
                $errors[] = $violation->getMessage();
            }

            return  $errors;
        }

        return [];
    }

    protected function createFormBody(Request $request): ?FormBody
    {
        $data = json_decode($request->getContent(), true);

        // Check if JSON decoding was successful
        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            return null;
        }

        $formData = new FormBody();
        $formData->setProduct($data['product'] ?? null);
        $formData->setTaxNumber($data['taxNumber'] ?? null);
        $formData->setCouponCode($data['couponCode'] ?? null);
        $formData->setPaymentProcessor($data['paymentProcessor'] ?? null);

        return $formData;
    }


    /**
     * @throws InvalidJsonException
     * @throws ValidateException
     */
    protected function processRequest(Request $request, ValidatorInterface $validator): FormBody
    {
        $formData = $this->createFormBody($request);

        if ($formData === null) {
            throw new InvalidJsonException('Invalid JSON data');
        }

        $errors = $this->getFormErrors($validator, $formData);

        if (count($errors) > 0) {
            $exception = new ValidateException('Validation thrown exception');
            $exception->setErrors($errors);
            throw $exception;
        }

        return $formData;
    }


}