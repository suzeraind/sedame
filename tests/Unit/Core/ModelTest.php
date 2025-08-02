<?php

namespace Tests\Unit\Core;

use App\Core\Model;
use App\Core\QueryBuilder;
use App\Core\Database;
use PHPUnit\Framework\TestCase;


class TestModel extends Model
{
    protected string $table = 'test_table';
    protected string $primaryKey = 'id';

    protected function fillable(): array
    {
        return ['name', 'email'];
    }
}

class ModelTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $reflection = new \ReflectionClass(Database::class);
        $instanceProperty = $reflection->getProperty('instance');
        $instanceProperty->setAccessible(true);
        $instanceProperty->setValue(null, null);
    }

    public function test_inst_method_returns_model_instance(): void
    {
        $model = TestModel::inst();
        $this->assertInstanceOf(TestModel::class, $model);
    }

    public function test_query_method_returns_query_builder_with_correct_table(): void
    {
        $model = new TestModel;
        $queryBuilder = $model->query();

        $this->assertInstanceOf(QueryBuilder::class, $queryBuilder);

        $reflection = new \ReflectionClass($queryBuilder);
        $tableProperty = $reflection->getProperty('table');
        $tableProperty->setAccessible(true);
        $tableName = $tableProperty->getValue($queryBuilder);

        $this->assertEquals('test_table', $tableName);
    }

    public function test_all_method_calls_query_get(): void
    {
        $mockQueryBuilder = $this->createMock(QueryBuilder::class);
        $mockQueryBuilder->expects($this->once())
            ->method('get')
            ->willReturn([['id' => 1, 'name' => 'Test']]);

        $model = $this->getMockBuilder(TestModel::class)
            ->onlyMethods(['query'])
            ->getMock();

        $model->expects($this->once())
            ->method('query')
            ->willReturn($mockQueryBuilder);

        $result = $model->all();
        $this->assertEquals([['id' => 1, 'name' => 'Test']], $result);
    }

    public function test_where_method_calls_query_where(): void
    {
        $mockQueryBuilder = $this->createMock(QueryBuilder::class);
        $mockQueryBuilder->expects($this->once())
            ->method('where')
            ->with('column', '=', 'value')
            ->willReturn($mockQueryBuilder);

        $model = $this->getMockBuilder(TestModel::class)
            ->onlyMethods(['query'])
            ->getMock();

        $model->expects($this->once())
            ->method('query')
            ->willReturn($mockQueryBuilder);

        $result = $model->where('column', '=', 'value');
        $this->assertInstanceOf(QueryBuilder::class, $result);
    }

    public function test_first_where_method_calls_where_and_first(): void
    {
        $mockQueryBuilder = $this->createMock(QueryBuilder::class);
        $mockQueryBuilder->expects($this->once())
            ->method('where')
            ->with('column', '=', 'value')
            ->willReturn($mockQueryBuilder);
        $mockQueryBuilder->expects($this->once())
            ->method('first')
            ->willReturn(['id' => 1, 'name' => 'Test']);

        $model = $this->getMockBuilder(TestModel::class)
            ->onlyMethods(['query'])
            ->getMock();

        $model->expects($this->once())
            ->method('query')
            ->willReturn($mockQueryBuilder);

        $result = $model->firstWhere('column', '=', 'value');
        $this->assertEquals(['id' => 1, 'name' => 'Test'], $result);
    }

    public function test_find_method_calls_query_where_and_first(): void
    {
        $mockQueryBuilder = $this->createMock(QueryBuilder::class);
        $mockQueryBuilder->expects($this->once())
            ->method('where')
            ->with('id', '=', 1)
            ->willReturn($mockQueryBuilder);
        $mockQueryBuilder->expects($this->once())
            ->method('first')
            ->willReturn(['id' => 1, 'name' => 'Found']);

        $model = $this->getMockBuilder(TestModel::class)
            ->onlyMethods(['query'])
            ->getMock();

        $model->expects($this->once())
            ->method('query')
            ->willReturn($mockQueryBuilder);

        $result = $model->find(1);
        $this->assertEquals(['id' => 1, 'name' => 'Found'], $result);
    }

    public function test_create_method_inserts_data_and_finds_new_record(): void
    {
        $mockQueryBuilder = $this->createMock(QueryBuilder::class);
        $mockQueryBuilder->expects($this->once())
            ->method('insert')
            ->with($this->equalTo(['name' => 'New User', 'email' => 'new@example.com']))
            ->willReturn(true);

        $model = $this->getMockBuilder(TestModel::class)
            ->onlyMethods(['query', 'find', 'getLastInsertId'])
            ->getMock();

        $model->expects($this->once())
            ->method('query')
            ->willReturn($mockQueryBuilder);

        $model->expects($this->once())
            ->method('getLastInsertId')
            ->willReturn('1');

        $model->expects($this->once())
            ->method('find')
            ->with('1') // Expect '1' as string from lastInsertId
            ->willReturn(['id' => 1, 'name' => 'New User', 'email' => 'new@example.com']);

        $data = ['name' => 'New User', 'email' => 'new@example.com', 'extra' => 'should_be_ignored'];
        $result = $model->create($data);

        $this->assertEquals(['id' => 1, 'name' => 'New User', 'email' => 'new@example.com'], $result);
    }

    public function test_create_method_returns_false_on_insert_failure(): void
    {
        $mockQueryBuilder = $this->createMock(QueryBuilder::class);
        $mockQueryBuilder->expects($this->once())
            ->method('insert')
            ->willReturn(false);

        $model = $this->getMockBuilder(TestModel::class)
            ->onlyMethods(['query'])
            ->getMock();

        $model->expects($this->once())
            ->method('query')
            ->willReturn($mockQueryBuilder);

        $data = ['name' => 'New User', 'email' => 'new@example.com'];
        $result = $model->create($data);

        $this->assertFalse($result);
    }

    public function test_update_method_updates_data_and_finds_updated_record(): void
    {
        $mockQueryBuilder = $this->createMock(QueryBuilder::class);
        $mockQueryBuilder->expects($this->once())
            ->method('where')
            ->with('id', '=', 1)
            ->willReturn($mockQueryBuilder);
        $mockQueryBuilder->expects($this->once())
            ->method('update')
            ->with($this->equalTo(['name' => 'Updated User']))
            ->willReturn(true);

        $model = $this->getMockBuilder(TestModel::class)
            ->onlyMethods(['query', 'find'])
            ->getMock();

        $model->expects($this->once())
            ->method('query')
            ->willReturn($mockQueryBuilder);

        $model->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(['id' => 1, 'name' => 'Updated User', 'email' => 'user@example.com']);

        $data = ['name' => 'Updated User', 'extra' => 'should_be_ignored'];
        $result = $model->update(1, $data);

        $this->assertEquals(['id' => 1, 'name' => 'Updated User', 'email' => 'user@example.com'], $result);
    }

    public function test_update_method_returns_false_on_update_failure(): void
    {
        $mockQueryBuilder = $this->createMock(QueryBuilder::class);
        $mockQueryBuilder->expects($this->once())
            ->method('where')
            ->willReturn($mockQueryBuilder);
        $mockQueryBuilder->expects($this->once())
            ->method('update')
            ->willReturn(false);

        $model = $this->getMockBuilder(TestModel::class)
            ->onlyMethods(['query'])
            ->getMock();

        $model->expects($this->once())
            ->method('query')
            ->willReturn($mockQueryBuilder);

        $data = ['name' => 'Updated User'];
        $result = $model->update(1, $data);

        $this->assertFalse($result);
    }

    public function test_delete_method_calls_query_delete(): void
    {
        $mockQueryBuilder = $this->createMock(QueryBuilder::class);
        $mockQueryBuilder->expects($this->once())
            ->method('where')
            ->with('id', '=', 1)
            ->willReturn($mockQueryBuilder);
        $mockQueryBuilder->expects($this->once())
            ->method('delete')
            ->willReturn(true);

        $model = $this->getMockBuilder(TestModel::class)
            ->onlyMethods(['query'])
            ->getMock();

        $model->expects($this->once())
            ->method('query')
            ->willReturn($mockQueryBuilder);

        $result = $model->delete(1);
        $this->assertTrue($result);
    }

    public function test_delete_method_returns_false_on_delete_failure(): void
    {
        $mockQueryBuilder = $this->createMock(QueryBuilder::class);
        $mockQueryBuilder->expects($this->once())
            ->method('where')
            ->willReturn($mockQueryBuilder);
        $mockQueryBuilder->expects($this->once())
            ->method('delete')
            ->willReturn(false);

        $model = $this->getMockBuilder(TestModel::class)
            ->onlyMethods(['query'])
            ->getMock();

        $model->expects($this->once())
            ->method('query')
            ->willReturn($mockQueryBuilder);

        $result = $model->delete(1);
        $this->assertFalse($result);
    }
}
