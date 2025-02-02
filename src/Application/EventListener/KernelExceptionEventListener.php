<?php

namespace App\Application\EventListener;

use App\Controller\Exception\HttpCompliantExceptionInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class KernelExceptionEventListener
{
    private const DEFAULT_PROPERTY = 'error';

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        // Обрабатываем нарушение уникальности (например, дублирующийся email)
        if ($exception instanceof UniqueConstraintViolationException) {
            $message = $this->extractUniqueViolationMessage($exception);
            $event->setResponse($this->getHttpResponse($message, Response::HTTP_CONFLICT));
        }
        // Если это HTTP-исключение с кастомным ответом (например, 404, 403)
        elseif ($exception instanceof HttpCompliantExceptionInterface) {
            $event->setResponse($this->getHttpResponse($exception->getHttpResponseBody(), $exception->getHttpCode()));
        }
        // Если ресурс не найден (например, запись в БД)
        elseif ($exception instanceof NotFoundHttpException) {
            $event->setResponse($this->getHttpResponse('Resource not found', Response::HTTP_NOT_FOUND));
        }
        // Если это стандартное HttpExceptionInterface, получаем предыдущее исключение
        elseif ($exception instanceof HttpExceptionInterface) {
            $exception = $exception->getPrevious();
            $event->setResponse($this->getHttpResponse($exception->getMessage(), Response::HTTP_BAD_REQUEST));
        }
        // Если это ошибка валидации
        elseif ($exception instanceof ValidationFailedException) {
            $event->setResponse($this->getValidationFailedResponse($exception));
        }
        // Общая обработка других ошибок
        else {
            $event->setResponse($this->getHttpResponse('Something went wrong!', Response::HTTP_INTERNAL_SERVER_ERROR));
        }
    }

    private function getHttpResponse($message, $code): Response
    {
        return new JsonResponse(['message' => $message], $code);
    }

    private function getValidationFailedResponse(ValidationFailedException $exception): Response
    {
        $response = [];
        foreach ($exception->getViolations() as $violation) {
            $property = empty($violation->getPropertyPath()) ? self::DEFAULT_PROPERTY : $violation->getPropertyPath();
            $response[$property] = $violation->getMessage();
        }

        return new JsonResponse($response, Response::HTTP_BAD_REQUEST);
    }

    private function extractUniqueViolationMessage(UniqueConstraintViolationException $exception): string
    {
        $message = 'This value already exists.'; // Значение по умолчанию

        // Пытаемся извлечь имя колонки из текста ошибки
        if (preg_match('/Key \((.*?)\)=/', $exception->getMessage(), $matches)) {
            $field = $matches[1] ?? 'value';
            $message = sprintf('The %s is already in use.', $field);
        }

        return $message;
    }
}
