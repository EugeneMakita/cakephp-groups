<?php
namespace Groups\Test\TestCase\Model\Table;

use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Groups\Shell\Task\UserGroupCleanupTask;

class UserGroupCleanupTaskTest extends TestCase
{
    public $fixtures = [
        'plugin.groups.groups',
        'plugin.groups.groups_users',
        'plugin.CakeDC/Users.users',
    ];

    public function setUp()
    {
        parent::setUp();

        $this->Groups = TableRegistry::get('Groups.Groups');
        $this->Users = TableRegistry::get('CakeDC/Users.Users');

        $this->io = $this->getMockBuilder('Cake\Console\ConsoleIo')
            ->disableOriginalConstructor()
            ->getMock();

        $this->Task = $this->getMockBuilder('Groups\Shell\Task\UserGroupCleanupTask')
            ->setMethods(['in', 'out', 'err', '_stop'])
            ->setConstructorArgs([$this->io])
            ->getMock();

        Configure::load('Groups.groups');
    }

    public function tearDown()
    {
        unset($this->Groups);
        unset($this->Users);
        unset($this->io);
        unset($this->Task);

        parent::tearDown();
    }

    public function testMain()
    {
        $group = $this->Groups->get('00000000-0000-0000-0000-000000000001');
        $user = $this->Users->get('00000000-0000-0000-0000-000000000001');
        // create duplicate many-to-many record between group and user
        $this->Groups->Users->link($group, [$user]);

        // verify user - group links increased
        $result = $this->Groups->get('00000000-0000-0000-0000-000000000001', [
            'contain' => [
                'Users' => function ($q) {
                    return $q->where(['Users.id' => '00000000-0000-0000-0000-000000000001']);
                }
            ]
        ]);
        $this->assertNotEquals(1, count($result->get('users')));

        $this->Task->main();

        // verify duplicated records have been removed
        $result = $this->Groups->get('00000000-0000-0000-0000-000000000001', [
            'contain' => [
                'Users' => function ($q) {
                    return $q->where(['Users.id' => '00000000-0000-0000-0000-000000000001']);
                }
            ]
        ]);
        $this->assertEquals(1, count($result->get('users')));
    }
}
