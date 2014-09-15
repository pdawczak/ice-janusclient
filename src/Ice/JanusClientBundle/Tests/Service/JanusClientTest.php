<?php

namespace Ice\JanusClientBundle\Tests\Service;

use \Guzzle\Http\Exception\BadResponseException;
use Guzzle\Service\Client;
use Ice\JanusClientBundle\Service\JanusClient;

class JanusClientTest extends \PHPUnit_Framework_TestCase
{
    const CLASS_FACTORY = 'Guzzle\Service\Command\Factory\FactoryInterface',
          CLASS_COMMAND = 'Guzzle\Service\Command\CommandInterface';

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $commandFactory;

    /**
     * @var \Ice\JanusClientBundle\Service\JanusClient
     */
    protected $client;

    public function setUp()
    {
        $this->commandFactory = $this->getMock(self::CLASS_FACTORY, array('factory'));

        $guzzleClient = new Client();
        $guzzleClient->setCommandFactory($this->commandFactory);

        $this->client = new JanusClient($guzzleClient);
    }

    public function testGetUser()
    {
        $testingUsername = 'sample';

        $this->stubCommandFactoryWith('GetUser', [ 'username' => $testingUsername ]);

        $this->assertNotEmpty($this->client->getUser($testingUsername));
    }

    public function testCreateUser()
    {
        $testingValues = [
            'key1' => 'val1',
            'key2' => 'val2',
        ];

        $this->stubCommandFactoryWith('CreateUser', $testingValues);

        $this->assertNotEmpty($this->client->createUser($testingValues));
    }

    public function testUpdateUser()
    {
        $testingUsername = 'sampler';
        $testingValues = [
            'key1' => 'val3',
            'key2' => 'val4',
        ];

        $this->stubCommandFactoryWith(
            'UpdateUser',
            array_merge($testingValues, [ 'username' => $testingUsername ])
        );

        $this->assertNotEmpty($this->client->updateUser($testingUsername, $testingValues));
    }

    public function testUpdateAttribute()
    {
        $testingUsername       = 'sampler';
        $testingAttributeName  = 'sampleAttribute';
        $testingAttributeValue = 'a-test';
        $testingUpdatedBy      = 'st303';

        $this->stubCommandFactoryWith(
            'UpdateAttribute',
            [
                'username'      => $testingUsername,
                'attributeName' => $testingAttributeName,
                'value'         => $testingAttributeValue,
                'updatedBy'     => $testingUpdatedBy,
            ]
        );

        $this->client->updateAttribute(
            $testingUsername,
            $testingAttributeName,
            $testingAttributeValue,
            $testingUpdatedBy
        );
    }

    public function testUpdateEmailAddress()
    {
        $testingUsername = 'sampler';
        $testingEmail = 'sample@test.com';

        $this->stubCommandFactoryWith(
            'UpdateEmailAddress',
            [
                'username' => $testingUsername,
                'email'    => $testingEmail,
            ]
        );

        $this->client->updateEmailAddress($testingUsername, $testingEmail);
    }

    public function testUpdateName()
    {
        $testingUsername    = 'sampler';
        $testingTitle       = 'Mr';
        $testingFirstNames  = 'Sampler';
        $testingMiddleNames = 'Test';
        $testingLastNames   = 'Tester';

        $this->stubCommandFactoryWith(
            'UpdateName',
            [
                'username'    => $testingUsername,
                'title'       => $testingTitle,
                'firstNames'  => $testingFirstNames,
                'middleNames' => $testingMiddleNames,
                'lastNames'   => $testingLastNames,
            ]
        );

        $this->client->updateName(
            $testingUsername,
            $testingTitle,
            $testingFirstNames,
            $testingMiddleNames,
            $testingLastNames
        );
    }

    public function testUpdateDob()
    {
        $testingUsername = 'sampler';
        $testingDate     = '2014-09-12';
        $testingDob      = new \DateTime($testingDate);

        $this->stubCommandFactoryWith(
            'UpdateDateOfBirth',
            [
                'username' => $testingUsername,
                'dob'      => $testingDate
            ]
        );

        $this->client->updateDob($testingUsername, $testingDob);
    }

    public function testCreateAttribute()
    {
        $testingUsername       = 'sampler';
        $testingAttributeName  = 'sampleAttribute';
        $testingAttributeValue = 'a-test';

        $this->stubCommandFactoryWith(
            'CreateAttribute',
            [
                'username'  => $testingUsername,
                'fieldName' => $testingAttributeName,
                'value'     => $testingAttributeValue,
            ]
        );

        $this->client->createAttribute($testingUsername, $testingAttributeName, $testingAttributeValue);
    }

    public function testGetUsers()
    {
        $testingFilter = [ 'username' => 'Test' ];

        $this->stubCommandFactoryWith('GetUsers', [ 'query' => $testingFilter ]);

        $this->client->getUsers($testingFilter);
    }

    public function testSearchUsers()
    {
        $testingTerm = 'test';

        $this->stubCommandFactoryWith('SearchUsers', [ 'term' => $testingTerm ]);

        $this->client->searchUsers($testingTerm);
    }

    public function testAuthenticate()
    {
        $testingUsername = 'sampler';
        $testingPassword = 'Secret';

        $command = $this->getPreparedCommandMock($this->anything());
        $command->expects($this->once())
            ->method('prepare');

        $request = $this->getMock('Guzzle\Http\Message\RequestInterface');
        $request->expects($this->once())
            ->method('setAuth')
            ->with($testingUsername, $testingPassword, $this->anything());

        $command->expects($this->once())
            ->method('getRequest')
            ->will($this->returnValue($request));

        $this->commandFactory
            ->expects($this->once())
            ->method('factory')
            ->with('Authenticate')
            ->will($this->returnValue($command));

        $this->client->authenticate($testingUsername, $testingPassword);
    }

    protected function stubCommandFactoryWith($methodName, $params)
    {
        $this->commandFactory
            ->expects($this->once())
            ->method('factory')
            ->with($methodName, $params)
            ->will($this->returnValue(
                $this->getPreparedCommandMock(
                    $this->anything()
                )
            ));
    }

    protected function getPreparedCommandMock($return = null)
    {
        $command = $this->getCommandMock();
        $command->expects($this->once())
            ->method('execute')
            ->will($this->returnValue($return));
        return $command;
    }

    protected function getCommandMock()
    {
        return $this->getMock(self::CLASS_COMMAND, [
            'setClient', 'getName', 'getOperation', 'execute', 'getClient',
            'getRequest', 'getResponse', 'getResult', 'setResult', 'isPrepared',
            'isExecuted', 'prepare', 'getRequestHeaders', 'setOnComplete',
            'offsetExists', 'offsetGet', 'offsetSet', 'offsetUnset', 'toArray'
        ]);
    }
} 
