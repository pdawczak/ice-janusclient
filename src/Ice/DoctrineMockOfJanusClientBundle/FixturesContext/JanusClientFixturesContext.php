<?php

namespace Ice\DoctrineMockOfJanusClientBundle\FixturesContext;

use Behat\Behat\Context\BehatContext;
use Behat\Behat\Context\Step\Given;
use Behat\Gherkin\Node\TableNode;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Symfony\Component\HttpKernel\KernelInterface;


class JanusClientFixturesContext extends BehatContext implements KernelAwareInterface
{
    const CLASS_ATTRIBUTE = '\Ice\JanusClientBundle\Entity\AttributeMock',
          CLASS_USER      = '\Ice\JanusClientBundle\Entity\User';

    private $kernel;

    protected $baseUserAttributes = [
        'id', 'username', 'title', 'first_names',
        'middle_names', 'last_names', 'dob', 'email'
    ];

    /**
     * @Given /^there is a testing user$/
     *
     * This method creates fixture User object with Attributes.
     *
     * User with following data is created:
     *  id:           12
     *  username:     st303
     *  email:        sampler@tester.com
     *  title:        Mr
     *  first_names:  Sample
     *  middle_names: Test
     *  last_names:   Tester
     *  dob:          2014-08-09
     *  telephone:    012345678
     *  mobile:       876543210
     *  address1:     7
     *  address2:     JJ
     *  address3:     Thomson
     *  address4:     Avenue
     *  town:         Cambridge
     *  county:       Cambridgeshire
     *  post_code:    CB3 0RB
     *  country:      GBR
     */
    public function thereIsATestingUser()
    {
        $userData = new TableNode(<<<DATA
      | id | username | email              | title | first_names | middle_names | last_names | dob        | telephone | mobile    | address1 | address2 | address3 | address4 | town      | county         | post_code | country |
      | 12 | st303    | sampler@tester.com | Mr    | Sample      | Test         | Tester     | 2014-08-09 | 012345678 | 876543210 | 7        | JJ       | Thomson  | Avenue   | Cambridge | Cambridgeshire | CB3 0RB   | GBR     |
DATA
        );

        return new Given('there are users', $userData);
    }

    /**
     * @Given /^there are users$/
     *
     * Required attributes for building User:
     *      id
     *      username
     *      title
     *      first_names
     *      middle_names
     *      last_names
     *      dob
     *      email
     *
     * All other options will be stored as Attribute belonging
     * to created User.
     */
    public function thereAreUsers(TableNode $users)
    {
        $attributeIdentity = 1;

        foreach ($users->getHash() as $row) {
            $userFixture = [
                'id'          => $row['id'],
                'username'    => $row['username'],
                'title'       => $row['title'],
                'firstNames'  => $row['first_names'],
                'middleNames' => $row['middle_names'],
                'lastNames'   => $row['last_names'],
                'dob'         => new \DateTime($row['dob']),
                'email'       => $row['email'],
                'attributes'  => []
            ];

            foreach ($row as $key => $value) {
                if (! in_array($key, $this->baseUserAttributes)) {
                    $camelcaseKey = $this->underscoreToCamelcase($key);

                    $attributeFixtureId = sprintf('janus_client_attribute:%s:%s', $row['id'], $camelcaseKey);

                    $fixtures[self::CLASS_ATTRIBUTE][$attributeFixtureId] = [
                        'id'        => $attributeIdentity,
                        'fieldName' => $camelcaseKey,
                        'value'     => $value,
                        'user'      => $this->getEntityManager()->getReference(self::CLASS_USER, $row['id'])
                    ];

                    $attributeIdentity++;
                }
            }

            $fixtures[self::CLASS_USER]['janus_client_user:' . $row['id']] = $userFixture;
        }

        $this->getMainContext()->getSubcontext('fixtures')->loadFixtures($fixtures);
    }

    /**
     * Sets Kernel instance.
     *
     * @param KernelInterface $kernel HttpKernel instance
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    protected function underscoreToCamelcase($string)
    {
        $func = create_function('$c', 'return strtoupper($c[1]);');
        return preg_replace_callback('/_([a-z])/', $func, $string);
    }

    /**
     * @return EntityManagerInterface
     */
    private function getEntityManager()
    {
        return $this->kernel->getContainer()->get('doctrine.orm.default_entity_manager');
    }
}
