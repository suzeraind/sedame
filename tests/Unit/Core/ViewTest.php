<?php

namespace Tests\Unit\Core;

use App\Core\View;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Exception;

class ViewTest extends TestCase
{
    protected function tearDown(): void
    {

        if (file_exists(VIEW_PATH . '/pages/test_view.php')) {
            unlink(VIEW_PATH . '/pages/test_view.php');
        }
        if (file_exists(VIEW_PATH . '/layouts/test_layout.php')) {
            unlink(VIEW_PATH . '/layouts/test_layout.php');
        }
        if (file_exists(VIEW_PATH . '/components/test_component.php')) {
            unlink(VIEW_PATH . '/components/test_component.php');
        }
    }

    public function test_with_method_sets_data_correctly_with_key_value(): void
    {
        $view = new View;
        $view->with('name', 'John Doe');


        $reflection = new \ReflectionClass($view);
        $property = $reflection->getProperty('data');
        $property->setAccessible(true);
        $data = $property->getValue($view);

        $this->assertArrayHasKey('name', $data);
        $this->assertEquals('John Doe', $data['name']);
    }

    public function test_with_method_sets_data_correctly_with_array(): void
    {
        $view = new View;
        $view->with(['name' => 'John Doe', 'age' => 30]);


        $reflection = new \ReflectionClass($view);
        $property = $reflection->getProperty('data');
        $property->setAccessible(true);
        $data = $property->getValue($view);

        $this->assertArrayHasKey('name', $data);
        $this->assertEquals('John Doe', $data['name']);
        $this->assertArrayHasKey('age', $data);
        $this->assertEquals(30, $data['age']);
    }

    public function test_layout_method_sets_layout_correctly(): void
    {
        $view = new View;
        $view->layout('main');


        $reflection = new \ReflectionClass($view);
        $property = $reflection->getProperty('layout');
        $property->setAccessible(true);
        $layout = $property->getValue($view);

        $this->assertEquals('main', $layout);
    }

    public function test_component_method_renders_component(): void
    {

        file_put_contents(VIEW_PATH . '/components/test_component.php', '<p>Test Component: <?php echo $component_data; ?></p>');

        $view = new View;
        ob_start();
        $view->component('test_component', ['component_data' => 'Hello']);
        $output = ob_get_clean();

        $this->assertStringContainsString('<p>Test Component: Hello</p>', $output);
    }

    public function test_component_method_throws_exception_if_component_not_found(): void
    {
        $view = new View;
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageMatches('/Component not found: .*non_existent_component.php/');
        $view->component('non_existent_component');
    }

    public function test_render_method_renders_view_without_layout(): void
    {

        file_put_contents(VIEW_PATH . '/pages/test_view.php', '<h1>Test View</h1><?php echo $data_var; ?>');

        $view = new View;
        $view->with('data_var', 'Some Data');
        ob_start();
        $view->render('test_view');
        $output = ob_get_clean();

        $this->assertStringContainsString('<h1>Test View</h1>Some Data', $output);
        $this->assertStringNotContainsString('<html>', $output);
    }

    public function test_render_method_renders_view_with_layout(): void
    {

        file_put_contents(VIEW_PATH . '/pages/test_view.php', '<h1>Test View</h1><?php echo $data_var; ?>');
        file_put_contents(VIEW_PATH . '/layouts/test_layout.php', '<html><body><?php echo $content; ?></body></html>');

        $view = new View;
        $view->with('data_var', 'Layout Data')->layout('test_layout');
        ob_start();
        $view->render('test_view');
        $output = ob_get_clean();

        $this->assertStringContainsString('<html><body><h1>Test View</h1>Layout Data</body></html>', $output);
    }

    public function test_render_method_throws_exception_if_view_not_found(): void
    {
        $view = new View;
        $this->expectException(Exception::class);
        $this->expectExceptionMessageMatches('/View not found: .*non_existent_view.php/');
        $view->render('non_existent_view');
    }

    public function test_render_method_throws_exception_if_layout_not_found(): void
    {

        file_put_contents(VIEW_PATH . '/pages/test_view.php', '<h1>Test View</h1>');

        $view = new View;
        $view->layout('non_existent_layout');
        $this->expectException(Exception::class);
        $this->expectExceptionMessageMatches('/Layout not found: .*non_existent_layout.php/');
        $view->render('test_view');
    }
}
