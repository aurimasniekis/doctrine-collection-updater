# Doctrine Collection Updater

[![Latest Version](https://img.shields.io/github/release/aurimasniekis/doctrine-collection-updater.svg?style=flat-square)](https://github.com/aurimasniekis/doctrine-collection-updater/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Build Status](https://img.shields.io/travis/aurimasniekis/doctrine-collection-updater.svg?style=flat-square)](https://travis-ci.org/aurimasniekis/doctrine-collection-updater)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/aurimasniekis/doctrine-collection-updater.svg?style=flat-square)](https://scrutinizer-ci.com/g/aurimasniekis/doctrine-collection-updater)
[![Quality Score](https://img.shields.io/scrutinizer/g/aurimasniekis/doctrine-collection-updater.svg?style=flat-square)](https://scrutinizer-ci.com/g/aurimasniekis/doctrine-collection-updater)
[![Total Downloads](https://img.shields.io/packagist/dt/aurimasniekis/doctrine-collection-updater.svg?style=flat-square)](https://packagist.org/packages/aurimasniekis/doctrine-collection-updater)

[![Email](https://img.shields.io/badge/email-aurimas@niekis.lt-blue.svg?style=flat-square)](mailto:aurimas@niekis.lt)

Doctrine Collection Updater provides a trait to update collection (create, remove) element from collection based on array of comparators for e.g. `id`.

## Install

Via Composer

```bash
$ composer require aurimasniekis/doctrine-collection-updater
```

## Usage

```php
<?php

use AurimasNiekis\DoctrineCollectionUpdater\CollectionUpdaterTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityRepository;

class Tag
{
    /**
     * @var int
     */
    private $id;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return Tag
     */
    public function setId(int $id): Tag
    {
        $this->id = $id;

        return $this;
    }
}

class Item
{
    /**
     * @var int[]
     */
    private $rawTags;

    /**
     * @var Tag[]|Collection
     */
    private $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    /**
     * @return int[]
     */
    public function getRawTags(): array
    {
        return $this->rawTags;
    }

    /**
     * @param int[] $rawTags
     *
     * @return Item
     */
    public function setRawTags(array $rawTags): Item
    {
        $this->rawTags = $rawTags;

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    /**
     * @param Collection|Tag[] $tags
     *
     * @return Item
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }
}

class ItemRepository extends EntityRepository
{
    use CollectionUpdaterTrait;
    
    public function save(Item $item)
    {
        $em = $this->getEntityManager();
        
        $tags = $this->updateCollection(
            $item->getTags(),
            $item->getRawTags(),
            function (Item $item): int {
                return $item->getId();
            },
            function (int $id) use ($em): Item {
                $tag = new Tag();
                
                $tag->setId($id);
                
                $em->persist($tag);
                
                return $tag;
            }
        );
        
        $item->setTags($tags);
        
        $em->persist($item);
        $em->flush();
    }
}
```

## Testing

```bash
$ composer test
```


## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.


## License

Please see [License File](LICENSE) for more information.
