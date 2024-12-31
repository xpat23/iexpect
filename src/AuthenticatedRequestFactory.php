<?php

declare(strict_types=1);

namespace App;

use Xpat\ApiTest\Request\RequestFactory;
use Xpat\Http\Json\JsonObject;
use Xpat\Http\Request\HttpRequest;

class AuthenticatedRequestFactory implements RequestFactory
{
    private string $token = '';

    public function __construct(
        private readonly RequestFactory $requestFactory,
        private readonly string $email,
        private readonly string $password
    ) {
    }

    public function put(string $path, array $data, array $headers = []): HttpRequest
    {
        return $this->requestFactory->put(
            $path,
            $data,
            $this->addAuthorizationHeader($headers)
        );
    }

    private function addAuthorizationHeader(array $headers): array
    {
        return array_merge($headers, [
            'Authorization' => 'Bearer ' . $this->retrieveToken(),
        ]);
    }

    private function retrieveToken(): string
    {
        if (! empty($this->token)) {
            return $this->token;
        }

        $token = $this->requestFactory->post('/api/login', [
            'login' => $this->email,
            'password' => $this->password,
        ])->execute();

        $data = new JsonObject($token->content());

        return $this->token = $data->get('token');
    }

    public function post(string $path, array $data, array $headers = []): HttpRequest
    {
        return $this->requestFactory->post(
            $path,
            $data,
            $this->addAuthorizationHeader($headers)
        );
    }

    public function get(string $path, array $headers = []): HttpRequest
    {
        return $this->requestFactory->get(
            $path,
            $this->addAuthorizationHeader($headers)
        );
    }

    public function delete(string $path, array $headers = []): HttpRequest
    {
        return $this->requestFactory->delete(
            $path,
            $this->addAuthorizationHeader($headers)
        );
    }
}