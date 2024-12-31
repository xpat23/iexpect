<?php

declare(strict_types=1);

namespace src;

use Xpat\ApiTest\Attribute\Test;
use Xpat\ApiTest\Expectation\FieldEqualityExpectation;
use Xpat\ApiTest\Expectation\StatusCodeExpectation;
use Xpat\ApiTest\Test\ApiTest;
use Xpat\ApiTest\Test\ApiTestInterface;
use Xpat\ApiTest\Test\TestCase;

readonly class CategoriesTest extends TestCase
{
    #[Test]
    public function expenseCategories(): ApiTestInterface
    {
        return new ApiTest(
            $this->requestFactory->get('/expense-categories'),
            [
                new StatusCodeExpectation(200),
                new FieldEqualityExpectation(
                    '0.name',
                    'Education'
                ),
            ]
        );
    }
}