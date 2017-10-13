<?php

namespace AurimasNiekis\DoctrineCollectionUpdater\Test;

use AurimasNiekis\DoctrineCollectionUpdater\CollectionUpdaterTrait;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

/**
 * Class CollectionUpdaterTraitTest
 *
 * @package AurimasNiekis\DoctrineCollectionUpdater\Test
 * @author  Aurimas Niekis <aurimas@niekis.lt>
 */
class CollectionUpdaterTraitTest extends TestCase
{
    /**
     * @var CollectionUpdaterTrait
     */
    private $instance;

    public function setUp()
    {
        $this->instance = new class {
            use CollectionUpdaterTrait;
        };
    }

    public function testCollectionUpdater()
    {
        $ids = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        $elements = new ArrayCollection();

        foreach ($ids as $id) {
            $elements->add(new class($id) {
                private $id;

                public function __construct(int $id)
                {
                    $this->id = $id;
                }

                public function getId(): int
                {
                    return $this->id;
                }
            });
        }

        $extractor = function ($object) {
            return $object->getId();
        };

        $newElement = function ($key) {
            return new class($key) {
                private $id;

                public function __construct(int $id)
                {
                    $this->id = $id;
                }

                public function getId(): int
                {
                    return $this->id;
                }
            };
        };

        $input = [2, 4, 6, 8, 10, 12];

        $result = $this->instance->updateCollection($elements, $input, $extractor, $newElement);

        $resultKeys = $result->map(
            function ($object): int {
                return $object->getId();
            }
        );

        $this->assertEquals($input, array_values($resultKeys->toArray()));
    }

    public function testCollectionUpdaterDeletion()
    {
        $ids = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        $elements = new ArrayCollection();

        foreach ($ids as $id) {
            $elements->add(new class($id) {
                private $id;

                public function __construct(int $id)
                {
                    $this->id = $id;
                }

                public function getId(): int
                {
                    return $this->id;
                }
            });
        }

        $extractor = function ($object) {
            return $object->getId();
        };

        $newElement = function ($key) {
            return new class($key) {
                private $id;

                public function __construct(int $id)
                {
                    $this->id = $id;
                }

                public function getId(): int
                {
                    return $this->id;
                }
            };
        };

        $input = [];
        $result = $this->instance->updateCollection($elements, $input, $extractor, $newElement);

        $this->assertEquals(0, $result->count());
    }

    public function testCollectionUpdaterInsert()
    {
        $elements = new ArrayCollection();

        $extractor = function ($object) {
            return $object->getId();
        };

        $newElement = function ($key) {
            return new class($key) {
                private $id;

                public function __construct(int $id)
                {
                    $this->id = $id;
                }

                public function getId(): int
                {
                    return $this->id;
                }
            };
        };

        $input = [2, 4, 6, 8, 10, 12];

        $result = $this->instance->updateCollection($elements, $input, $extractor, $newElement);

        $resultKeys = $result->map(
            function ($object): int {
                return $object->getId();
            }
        );

        $this->assertEquals($input, array_values($resultKeys->toArray()));
    }
}
