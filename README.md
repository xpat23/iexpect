# IExpect - REST API Testing Framework.

## Installation

### 1. Create .env file using the provided template.

```bash
make env
```

### 2. Run the docker containers.

```bash
make build
```

### 3. Install the composer dependencies.

```bash
make install
```

## Basic usage

### 1. Create a test case in `tests` directory.

```php
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
```

### 2. Configure request factory in the `config/services.yaml` file.

```yaml

parameters:
  default.host: host.docker.internal # localhost for Mac
  default.port: 8080

services:
  _defaults:
    autowire: true
    autoconfigure: true

  default.request.factory:
    class: Xpat\ApiTest\Request\DefaultRequestFactory
    arguments: [ '%default.host%', '%default.port%' ]
    autowire: true

  ApiTest\:
    resource: '../tests/*'
    arguments: [ '@default.request.factory' ]
```

### 3. Run the tests via make command.

```bash
make test
```

### 4. Run the tests in the PHP container.

```bash
make ssh
```

```bash
php iexpect tests/CategoriesTest.php
```

## Execute sequential queries

### 1. Create a test case in `tests` directory.

```php
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
```

### 2. Run the tests

```bash
php iexpect tests/CategoryCrudTest.php
```

## Useful commands

### Open a shell in the PHP container.

```bash
make ssh
```

## Expectations

- `FieldEqualityExpectation`
- `ContentExpectation`
- `CallbackExpectation`
- `FieldExistExpectation`
- `ElementsCountExpectation`
- `StatusCodeExpectation`
- `ExpectationResult`

## Create your own expectation

```php
use Xpat\Http\Json\JsonObject;
use Xpat\Http\Response\Response;
use Xpat\ApiTest\Expectation\ExpectationFailed;
use Xpat\ApiTest\Expectation\ExpectationInterface;


class MyExpectation implements ExpectationInterface
{

    public function check(Response $response): void
    {
        $data = new JsonObject($response->content());
        
        if ($data->get('name') !== 'My expectation') {
            throw new ExpectationFailed('Name is not My expectation');
        }
    }

    public function label(): string
    {
        return 'My expectation';
    }
}
```