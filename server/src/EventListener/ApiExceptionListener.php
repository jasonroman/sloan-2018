<?php

namespace App\EventListener;

use Doctrine\ORM\NoResultException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApiExceptionListener
{
    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $path = $event->getRequest()->getPathInfo();

        // if not handling an api exception, do nothing
        if (!(strpos($path, '/api') === 0 || strpos($path, '/public/api') === 0)) {
            return;
        }

        $e = $event->getException();

        // handle various status codes depending on the exception
        $statusCode = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : $e->getCode();

        if (!$statusCode) {
            $statusCode = Response::HTTP_NOT_FOUND;
        }

        // display the error message for 400-level errors, but not 500-level errors
        if ($e instanceof NotFoundHttpException || $e instanceof NoResultException) {
            $event->setResponse(new JsonResponse(['error' => 'API endpoint does not exist'], $statusCode));
        } elseif ($statusCode >= 400 && $statusCode < 500) {
            $event->setResponse(new JsonResponse(['error' => $e->getMessage()], $statusCode));
        } elseif ($statusCode >= 500) {
            $event->setResponse(new JsonResponse(['error' => 'Fatal error occurred'], $statusCode));
        }
    }
}