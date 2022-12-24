<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Demo;

use App\Entity\Business;
use App\Entity\IndustrySector;
use App\Entity\User;
use App\Google\GoogleMap\Place\Services\PlaceDetails\PlaceBusinessIndustryTypes;
use App\Security\Role\RoleTypes;
use Common\DataManagement\DataGenerator\RandomGenerator;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * This generates random data to operate the system as a demo.
 */
class EntityGenerator extends Fixture
{
    /**
     * An entity manager used to save the random generated entities on DB.
     *
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $_em;

    /**
     * Holds the industry sectors.
     *
     * @var IndustrySector[]
     */
    private array $_industrySectorList = [];

    /**
     * System admin email;
     *
     * @var string
     */
    private string $systemAdminEmail;

    /**
     * Password encoder
     *
     * @var UserPasswordHasherInterface
     */
    protected $passwordHasher;

    /**
     * @param EntityManagerInterface      $em
     * @param UserPasswordHasherInterface $hasher
     */
    public function __construct(EntityManagerInterface $em, UserPasswordHasherInterface $hasher)
    {
        $this->_em              = $em;
        $this->systemAdminEmail = 'yjmorales86@gmail.com';
        $this->passwordHasher   = $hasher;
    }

    /**
     * @inheritdoc
     *
     * @param ObjectManager $manager
     *
     * @return void
     * @throws Exception
     */
    public function load(ObjectManager $manager)
    {
        $this->generateProjectFixtures();
    }

    /**
     * Manages demo fixtures.
     *
     * @return void
     * @throws Exception
     */
    public function generateProjectFixtures(): void
    {
        $this->remove();
        $this->populate();
    }

    /**
     * Removes all entities from Database.
     *
     * @return void
     * @throws Exception
     */
    protected function remove(): void
    {
        $this->_clearTable('review');
        $this->_clearTable('business');
        $this->_clearTable('industry_sector');
        $this->_clearTable('review');
        $this->_clearTable('place');
        $this->_clearTable('user');
    }

    /**
     * Populates the database.
     *
     * @return void
     */
    protected function populate(): void
    {
        $this->_createIndustrySector();
        $this->createUser();
    }

    /**
     * Creates IndustrySector entities.
     *
     * @return void
     */
    protected function _createIndustrySector(): void
    {
        foreach (PlaceBusinessIndustryTypes::MAP as $type) {
            $industry = new IndustrySector();
            $industry->setName(ucfirst($type));
            $this->_em->persist($industry);
            $this->_em->flush();
            $this->_industrySectorList[] = $industry;
        }
    }

    private function createUser()
    {
        $user = new User();
        $user->setEmail($this->systemAdminEmail);
        $user->setPassword($this->passwordHasher->hashPassword($user, 'admin'));
        $user->setRoles([RoleTypes::ROLE_ADMIN, RoleTypes::ROLE_USER]);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * Creates business entities.
     *
     * @return void
     */
    protected function _createBusiness(): void
    {
        for ($i = 0; $i < 15; $i++) {
            $business = new Business();
            $business->setName(RandomGenerator::generateBusinessName());
            $business->setEmail(RandomGenerator::generateEmail());
            $business->setAddress(RandomGenerator::generateAddress());
            $business->setCity(RandomGenerator::generateCity());
            $business->setState(RandomGenerator::generateStateCode());
            $business->setZipCode(RandomGenerator::generateStateZipCode());
            $business->setActive($i % 2 === 0);
            $business->setIndustrySector($this->_industrySectorList[rand(0, count($this->_industrySectorList) - 1)]);
            $this->_em->persist($business);
        }
        $this->_em->flush();
    }

    /**
     * Removes all entities represented by the given class name.
     *
     * @param string $className
     *
     * @return void
     */
    protected function _removeEntity(string $className): void
    {
        foreach ($this->_em->getRepository($className)->findAll() as $item) {
            $this->_em->remove($item);
        }
        $this->_em->flush();
    }

    /**
     * @param string $tableName
     *
     * @return void
     * @throws Exception
     */
    protected function _clearTable(string $tableName): void
    {
        $sql = <<<SQL
DELETE FROM $tableName 
SQL;
        $this->_execute($sql);
    }

    /**
     * @param string $sql
     *
     * @return void
     * @throws Exception
     */
    protected function _execute(string $sql): void
    {
        $this->_em->getConnection()->prepare($sql)->executeStatement();
    }
}