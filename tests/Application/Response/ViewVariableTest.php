<?php

declare(strict_types=1);

namespace Tests\Application\Response;

use App\Application\Response\ViewVariable;
use Tests\TestCase;

class ViewVariableTest extends TestCase
{
    public function testEscaping()
    {
        $var = new ViewVariable('<script>alert("xss")</script>');
        $this->assertEquals('&lt;script&gt;alert(&quot;xss&quot;)&lt;/script&gt;', (string) $var);
    }

    public function testRaw()
    {
        $raw = '<script>alert("xss")</script>';
        $var = new ViewVariable($raw);
        $this->assertEquals($raw, $var->dangerousRaw());
    }

    public function testArrayAccess()
    {
        $data = ['name' => '<b>John</b>'];
        $var = new ViewVariable($data);
        $this->assertEquals('&lt;b&gt;John&lt;/b&gt;', (string) $var['name']);
    }

    public function testObjectAccess()
    {
        $obj = (object) ['name' => '<b>John</b>'];
        $var = new ViewVariable($obj);
        $this->assertEquals('&lt;b&gt;John&lt;/b&gt;', (string) $var->name);
    }

    public function testIteration()
    {
        $data = ['<b>A</b>', '<b>B</b>'];
        $var = new ViewVariable($data);
        $results = [];
        foreach ($var as $val) {
            $results[] = (string) $val;
        }
        $this->assertEquals(['&lt;b&gt;A&lt;/b&gt;', '&lt;b&gt;B&lt;/b&gt;'], $results);
    }

    public function testBooleanBehavior()
    {
        $varEmpty = new ViewVariable("");
        $varNull = new ViewVariable(null);
        $varFalse = new ViewVariable(false);
        $varZero = new ViewVariable(0);

        // This is where it currently fails (returns true for objects)
        $this->assertTrue((bool)$varEmpty);
        $this->assertTrue((bool)$varNull);
        $this->assertTrue((bool)$varFalse);
        $this->assertTrue((bool)$varZero);

        // However, we want them to be falsy if possible.
        // Since we can't make objects falsy, we should probably check their string value or raw value.
        $this->assertEmpty((string)$varEmpty);
        $this->assertEmpty((string)$varNull);
        $this->assertEquals("0", (string)$varFalse); // ViewVariable::__toString() returns "0" for false.
        $this->assertEquals("0", (string)$varZero);
    }

    public function testHelperCallUnwrapping()
    {
        $container = $this->prophesize(\Psr\Container\ContainerInterface::class);
        $helper = new \App\Application\Helpers\Helper($container->reveal());

        $container->get(\App\Application\Helpers\Helper::class)->willReturn($helper);
        \App\Application\Helpers\Helper::setContainer($container->reveal());

        // Mock all helpers expected by Helper::__call
        $container->get(\App\Application\Helpers\StringHelper::class)->willReturn(new \App\Application\Helpers\StringHelper());
        $container->get(\App\Application\Helpers\RouterHelper::class)->willReturn(new \App\Application\Helpers\RouterHelper());
        $container->get(\App\Application\Helpers\ConfigHelper::class)->willReturn(new \App\Application\Helpers\ConfigHelper());
        $container->get(\App\Application\Helpers\UserHelper::class)->willReturn($this->prophesize(\App\Application\Helpers\UserHelper::class)->reveal());
        $container->get(\App\Application\Helpers\NumberHelper::class)->willReturn(new \App\Application\Helpers\NumberHelper());
        $container->get(\App\Application\Helpers\ArrayHelper::class)->willReturn(new \App\Application\Helpers\ArrayHelper());
        $container->get(\App\Application\Helpers\SecurityHelper::class)->willReturn(new \App\Application\Helpers\SecurityHelper());

        $var = new ViewVariable("42");

        // __call on ViewVariable should unwrap 42, pass it to NumberHelper::toInt,
        // get 42 back, and return it raw because it's a scalar.
        $result = $var->to_int();

        $this->assertIsInt($result);
        $this->assertEquals(42, $result);
        $this->assertNotInstanceOf(ViewVariable::class, $result);

        // Test with non-scalar result (using ArrayHelper::merge via Helper)
        $arrayHelper = new \App\Application\Helpers\ArrayHelper();
        $container->get(\App\Application\Helpers\ArrayHelper::class)->willReturn($arrayHelper);

        $varArray = new ViewVariable(['a' => 1]);
        $resultArray = $varArray->merge(['b' => 2]);

        $this->assertInstanceOf(ViewVariable::class, $resultArray);
        $this->assertEquals(['a' => 1, 'b' => 2], $resultArray->dangerousRaw());
    }
}
