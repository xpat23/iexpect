<?php

declare(strict_types=1);

namespace ApiTest;

use Xpat\ApiTest\Attribute\Test;
use Xpat\ApiTest\Expectation\FieldEqualityExpectation;
use Xpat\ApiTest\Expectation\StatusCodeExpectation;
use Xpat\ApiTest\Request\RequestFactory;
use Xpat\ApiTest\Test\ApiTest;
use Xpat\ApiTest\Test\ApiTestGroup;
use Xpat\ApiTest\Test\ApiTestInterface;
use Xpat\ApiTest\Test\TestCase;

readonly class CategoryCrudTest extends TestCase
{
    private string $uuid;
    private string $name;
    private string $updatedName;

    public function __construct(protected RequestFactory $requestFactory)
    {
        $this->uuid = 'f170f95b-490c-44ce-ac25-a7d6979c805b';
        $this->name = 'new category';
        $this->updatedName = 'updated category';
    }

    #[Test]
    public function categoryCrud(): ApiTestInterface
    {
        return (new ApiTestGroup())
            ->add(fn(): ApiTestInterface => $this->create())
            ->add(fn(): ApiTestInterface => $this->getCategoryById($this->name))
            ->add(fn(): ApiTestInterface => $this->update())
            ->add(fn(): ApiTestInterface => $this->getCategoryById($this->updatedName))
            ->add(fn(): ApiTestInterface => $this->delete())
            ->add(fn(): ApiTestInterface => new ApiTest(
                $this->requestFactory->get('/expense-categories/' . $this->uuid),
                [
                    new StatusCodeExpectation(404),
                ]
            ));
    }

    private function create(): ApiTestInterface
    {
        return new ApiTest(
            $this->requestFactory->post('/expense-categories', [
                'id' => $this->uuid,
                'name' => $this->name,
            ]),
            [
                new StatusCodeExpectation(201),
                new FieldEqualityExpectation('id', $this->uuid),
                new FieldEqualityExpectation('name', $this->name),
            ]
        );
    }

    private function getCategoryById(string $name): ApiTestInterface
    {
        return new ApiTest(
            $this->requestFactory->get('/expense-categories/' . $this->uuid),
            [
                new StatusCodeExpectation(200),
                new FieldEqualityExpectation(
                    'name',
                    $name
                ),
            ]
        );
    }

    private function update(): ApiTestInterface
    {
        return new ApiTest(
            $this->requestFactory->put(
                '/expense-categories/' . $this->uuid,
                [
                    'id' => $this->uuid,
                    'name' => 'updated category',
                ]
            ),
            [
                new StatusCodeExpectation(200),
            ]
        );
    }

    private function delete(): ApiTestInterface
    {
        return new ApiTest(
            $this->requestFactory->delete(
                '/expense-categories/' . $this->uuid
            ),
            [
                new StatusCodeExpectation(200),
            ]
        );
    }
}