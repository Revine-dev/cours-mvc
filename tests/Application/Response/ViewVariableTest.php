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
}
