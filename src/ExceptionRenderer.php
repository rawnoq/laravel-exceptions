<?php

namespace Rawnod\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Rawnoq\ApiResponse\Support\Respond;
use Spatie\QueryBuilder\Exceptions\AllowedFieldsMustBeCalledBeforeAllowedIncludes;
use Spatie\QueryBuilder\Exceptions\InvalidAppendQuery;
use Spatie\QueryBuilder\Exceptions\InvalidDirection;
use Spatie\QueryBuilder\Exceptions\InvalidFieldQuery;
use Spatie\QueryBuilder\Exceptions\InvalidFilterQuery;
use Spatie\QueryBuilder\Exceptions\InvalidFilterValue;
use Spatie\QueryBuilder\Exceptions\InvalidIncludeQuery;
use Spatie\QueryBuilder\Exceptions\InvalidSortQuery;
use Spatie\QueryBuilder\Exceptions\UnknownIncludedFieldsQuery;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

/**
 * Central renderer for API error responses.
 * Handles unified structure, codes, and messages for all API exceptions.
 */
class ExceptionRenderer
{
    public function __construct(
        private readonly Respond $respond,
    ) {
    }

    /**
     * Convert an exception to a consistent JSON response.
     */
    public function render(Throwable $e): JsonResponse
    {
        if ($e instanceof HttpResponseException) {
            $response = $e->getResponse();
            
            if ($response instanceof JsonResponse) {
                return $this->enforceStatus(
                    $response,
                    $response->getStatusCode()
                );
            }
            
            // Convert non-JSON response to JSON
            return $this->enforceStatus(
                new JsonResponse([
                    'success' => false,
                    'message' => $response->getContent(),
                    'data' => null,
                ], $response->getStatusCode()),
                $response->getStatusCode()
            );
        }

        if ($e instanceof ModelNotFoundException) {
            $model = class_basename($e->getModel());
            $message = __('exceptions::exceptions.model_not_found', ['model' => $model]);

            return $this->enforceStatus(
                $this->respond->notFound($message),
                SymfonyResponse::HTTP_NOT_FOUND
            );
        }

        if ($e instanceof NotFoundHttpException) {
            return $this->enforceStatus(
                $this->respond->notFound(__('exceptions::exceptions.resource_not_found')),
                SymfonyResponse::HTTP_NOT_FOUND
            );
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            return $this->enforceStatus(
                $this->respond->methodNotAllowed(__('exceptions::exceptions.method_not_allowed')),
                SymfonyResponse::HTTP_METHOD_NOT_ALLOWED
            );
        }

        if ($e instanceof ValidationException) {
            $errors = $e->errors();
            $messages = $e->validator?->errors()->all() ?? [];

            if (empty($messages)) {
                $messages = [__('exceptions::exceptions.validation_failed')];
            }

            return $this->enforceStatus(
                $this->respond->unprocessableEntity($messages, $errors),
                SymfonyResponse::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if ($e instanceof AuthenticationException) {
            return $this->enforceStatus(
                $this->respond->unauthorized(__('exceptions::exceptions.unauthenticated')),
                SymfonyResponse::HTTP_UNAUTHORIZED
            );
        }

        if ($e instanceof AuthorizationException) {
            return $this->enforceStatus(
                $this->respond->forbidden(__('exceptions::exceptions.unauthorized')),
                SymfonyResponse::HTTP_FORBIDDEN
            );
        }

        if ($this->isBadRequestQueryException($e)) {
            $message = $e->getMessage() ?: __('exceptions::exceptions.bad_request');
            
            return $this->enforceStatus(
                $this->respond->badRequest($message),
                SymfonyResponse::HTTP_BAD_REQUEST
            );
        }

        if ($e instanceof HttpExceptionInterface) {
            $message = $this->httpExceptionMessage($e);

            $response = $this->enforceStatus(
                $this->respond->error($message),
                $e->getStatusCode()
            );

            return $response->withHeaders($e->getHeaders());
        }

        $message = config('app.debug')
            ? $e->getMessage()
            : __('exceptions::exceptions.server_error');

        Log::error('Unhandled API exception', [
            'exception' => $e,
        ]);

        return $this->enforceStatus(
            $this->respond->internalServerError($message),
            SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR
        );
    }

    /**
     * Check if the exception is caused by an invalid query (Spatie QueryBuilder).
     */
    private function isBadRequestQueryException(Throwable $e): bool
    {
        return $e instanceof InvalidFilterQuery
            || $e instanceof InvalidIncludeQuery
            || $e instanceof InvalidSortQuery
            || $e instanceof InvalidFieldQuery
            || $e instanceof InvalidAppendQuery
            || $e instanceof UnknownIncludedFieldsQuery
            || $e instanceof InvalidFilterValue
            || $e instanceof InvalidDirection
            || $e instanceof AllowedFieldsMustBeCalledBeforeAllowedIncludes;
    }

    /**
     * Ensure the response carries the appropriate status code even if the library modifies it later.
     */
    private function enforceStatus(JsonResponse $response, int $status): JsonResponse
    {
        return $response->setStatusCode($status);
    }

    /**
     * Prepare an appropriate message for standard Http exceptions.
     */
    private function httpExceptionMessage(HttpExceptionInterface $e): string
    {
        $message = $e->getMessage();

        if (!empty($message)) {
            return $message;
        }

        return SymfonyResponse::$statusTexts[$e->getStatusCode()]
            ?? __('exceptions::exceptions.http_exception');
    }
}

