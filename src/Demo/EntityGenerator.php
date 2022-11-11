<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Demo;

use App\Entity\Business;
use App\Entity\IndustrySector;
use App\Model\IndustrySectorEnum;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

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
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->_em = $em;
    }

    /**
     * @inheritdoc
     *
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $this->generateProjectFixtures();
    }

    /**
     * Manages demo fixtures.
     *
     * @return void
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
    }

    /**
     * Populates the database.
     *
     * @return void
     */
    protected function populate(): void
    {
        $this->_createIndustrySector();
        $this->_createBusiness();
    }

    /**
     * Creates IndustrySector entities.
     *
     * @return void
     */
    protected function _createIndustrySector(): void
    {
        foreach (IndustrySectorEnum::MAP as $type) {
            $industry = new IndustrySector();
            $industry->setName($type);
            $this->_em->persist($industry);
            $this->_em->flush();
            $this->_industrySectorList[] = $industry;
        }
    }

    /**
     * Creates business entities.
     *
     * @return void
     */
    protected function _createBusiness(): void
    {
        $address  = ['3445 SW 4th Ave', '543 NE 6th St', '34234 N Little Road', '3432 NW 45th AVE'];
        $cities   = ['Miami', 'Seattle', 'Atlanta', 'Oakland'];
        $state    = ['Florida', 'Georgia', 'Washington', 'California'];
        $zipCodes = ['33152', '88245', '62154', '69311'];
        for ($i = 0; $i < 15; $i++) {
            $business = new Business();
            $business->setName("Business $i");
            $business->setAddress($address[rand(0, 3)]);
            $business->setCity($cities[rand(0, 3)]);
            $business->setState($state[rand(0, 3)]);
            $business->setZipCode($zipCodes[rand(0, 3)]);
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