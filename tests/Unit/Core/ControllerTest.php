<?php

namespace Tests\Unit\Core;

use App\Core\Controller;
use App\Core\View;
use PHPUnit\Framework\TestCase;

class ControllerTest extends TestCase
{
    public function test_controller_instantiates_view(): void
    {
        $mockView = $this->createMock(View::class);
        $controller = new class($mockView) extends Controller {};
        $this->assertInstanceOf(Controller::class, $controller);
    }

    public function test_render_method_calls_view_methods(): void
    {
        $mockView = $this->createMock(View::class);
        $mockView->expects($this->once())
            ->method('with')
            ->with($this->equalTo(['test_data' => 'value']))
            ->willReturn($mockView);
        $mockView->expects($this->once())
            ->method('layout')
            ->with($this->equalTo('custom_layout'))
            ->willReturn($mockView);
        $mockView->expects($this->once())
            ->method('render')
            ->with($this->equalTo('test_view'));

        $controller = new class ($mockView) extends Controller {
            public function __construct(View $view)
            {
                parent::__construct($view);
            }

            public function callRender(string $view, ?array $data = [], ?string $layout = 'main'): void
            {
                $this->render($view, $data, $layout);
            }
        };

        $controller->callRender('test_view', ['test_data' => 'value'], 'custom_layout');
    }

    public function test_view_method_returns_view_instance(): void
    {
        $mockView = $this->createMock(View::class);
        $controller = new class($mockView) extends Controller {
            public function __construct(View $view)
            {
                parent::__construct($view);
            }
            public function publicView(): View
            {
                return $this->view();
            }
        };
        $this->assertInstanceOf(View::class, $controller->publicView());
    }

}
