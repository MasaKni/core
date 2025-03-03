<?php

namespace MixerApi\Core\Test\TestCase\Event;

use Cake\Event\EventManager;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;
use MixerApi\Core\Event\EventListenerLoader;
use MixerApi\Core\Test\App\Model\Entity\Actor;

class EventListenerLoaderTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * @var string[] fixtures
     */
    public $fixtures = [
        'plugin.MixerApi/Core.Actors',
    ];

    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        static::setAppNamespace('MixerApi\Core\Test\App');
    }

    public function test_load(): void
    {
        (new EventListenerLoader())->load('MixerApi\Core\Test\App\Event');
        $listeners = EventManager::instance()->listeners('Model.initialize');
        $results = array_filter($listeners, function ($listener){
            return $listener['callable'][1] == 'eventListenerLoaderTest';
        });
        $this->assertCount(1, $results);
    }
}
