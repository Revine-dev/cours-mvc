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
}
