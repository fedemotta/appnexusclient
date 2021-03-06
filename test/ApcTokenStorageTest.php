<?php
namespace F3\AppNexusClient;

// This allow us to configure the behavior of the "global mock"
$mockApcStore = false;
$mockApcFetch = false;

// apc_store and apc_fetch are not working on cli
function apc_store()
{
    global $mockApcStore;
    if (isset($mockApcStore)) {
        return $mockApcStore;
    } else {
        return false;
    }
}

function apc_fetch()
{
    global $mockApcFetch;
    if (isset($mockApcFetch)) {
        return $mockApcFetch;
    } else {
        return false;
    }
}

class ApcTokenStorageTest extends \PHPUnit_Framework_TestCase
{
    private $storage;

    protected function setUp()
    {
        if ( ! extension_loaded('apc')) {
            $this->markTestSkipped('APC extension is not loaded');
        }
        global $mockApcStore;
        global $mockApcFetch;
        $mockApcStore = true;
        $mockApcFetch = 'bar';
        $this->storage = new ApcTokenStorage('pref_', 0);
    }

    public function testInterface()
    {
        $this->assertInstanceOf('F3\\AppNexusClient\\TokenStorage', $this->storage);
    }

    public function testSet()
    {
        $this->assertTrue($this->storage->set('foo', 'bar'));
        $this->assertEquals('bar', $this->storage->get('foo'));

    }

    public function testGet()
    {
        $this->assertEquals('bar', $this->storage->get('foo'));
    }
}
