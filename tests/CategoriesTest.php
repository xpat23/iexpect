<?php

declare(strict_types=1);

namespace ApiTest;

use Xpat\ApiTest\Attribute\Test;
use Xpat\ApiTest\Expectation\ElementsCountExpectation;
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
        sleep(3);
        return new ApiTest(
            $this->requestFactory->get('/expense-categories'),
            [
                new StatusCodeExpectation(200),
                new FieldEqualityExpectation(
                    '0.name',
                    'Education'
                ),
                new ElementsCountExpectation('', 1),
            ]
        );
    }

    #[Test]
    public function expenseCategories1(): ApiTestInterface
    {
        sleep(3);
        return new ApiTest(
            $this->requestFactory->get('/expense-categories'),
            [
                new StatusCodeExpectation(200),
                new FieldEqualityExpectation(
                    '0.name',
                    'Education'
                ),
                new ElementsCountExpectation('', 1),
            ]
        );
    }

    #[Test]
    public function expenseCategories3(): ApiTestInterface
    {
        sleep(3);
        return new ApiTest(
            $this->requestFactory->get('/expense-categories'),
            [
                new StatusCodeExpectation(200),
                new FieldEqualityExpectation(
                    '0.name',
                    'Education'
                ),
                new ElementsCountExpectation('', 1),
            ]
        );
    }

    #[Test]
    public function expenseCategories5(): ApiTestInterface
    {
        sleep(3);
        return new ApiTest(
            $this->requestFactory->get('/expense-categories'),
            [
                new StatusCodeExpectation(200),
                new FieldEqualityExpectation(
                    '0.name',
                    'Education'
                ),
                new ElementsCountExpectation('', 1),
            ]
        );
    }

    #[Test]
    public function expenseCategories6(): ApiTestInterface
    {
        sleep(3);
        return new ApiTest(
            $this->requestFactory->get('/expense-categories'),
            [
                new StatusCodeExpectation(200),
                new FieldEqualityExpectation(
                    '0.name',
                    'Education'
                ),
                new ElementsCountExpectation('', 1),
            ]
        );
    }
}