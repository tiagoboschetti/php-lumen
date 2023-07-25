<?php

namespace Tests\Unit\Http\Resources;

use App\Enums\UserTypeEnum;
use App\Http\Resources\UserResource;
use App\Models\UserModel;
use Illuminate\Http\Request;
use PHPUnit\Framework\Assert;
use Tests\TestCase;

class UserResourceTest extends TestCase
{
    private Request $request;

    public function setUp(): void
    {
        parent::setUp();
        $this->request = new Request();
    }

    public function test_InvalidUserResource_ShouldReturnNull(): void
    {
        $resource = new UserResource(null);
        $data = $resource->toArray($this->request);

        Assert::assertEmpty($data);
    }

    public function test_UserResource_ReceivingValidData_ShouldReturnResourceAsArray(): void
    {
        $userData = $this->provideUserResourceData();

        Assert::assertIsArray($userData);
        Assert::assertEquals('Foo', $userData['name']);
        Assert::assertEquals('foo@bar.com', $userData['email']);
        Assert::assertEquals(UserTypeEnum::Common, $userData['type']);
    }

    public function test_ToArray_GivenValidExchange_ShouldReturnAnArrayWithTheExpectedIndexes(): void
    {
        $userData = $this->provideUserResourceData();

        Assert::assertArrayHasKey('name', $userData);
        Assert::assertArrayHasKey('email', $userData);
        Assert::assertArrayHasKey('type', $userData);
        Assert::assertArrayHasKey('document', $userData);
    }

    private function provideUserResourceData(): array
    {
        $userData = new UserModel([
            'name' => 'Foo',
            'email' => 'foo@bar.com',
            'type' => UserTypeEnum::Common,
        ]);

        $resource = new UserResource($userData);
        return $resource->toArray($this->request);
    }
}