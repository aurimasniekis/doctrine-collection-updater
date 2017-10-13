<?php

namespace AurimasNiekis\DoctrineCollectionUpdater;

use Doctrine\Common\Collections\Collection;

/**
 * Trait CollectionUpdaterTrait
 *
 * @package AurimasNiekis\DoctrineCollectionUpdater
 * @author  Aurimas Niekis <aurimas@niekis.lt>
 */
trait CollectionUpdaterTrait
{
    /**
     * @param Collection    $collection        Collection to update
     * @param array         $input             Array of comparators for e.g. id's
     * @param callable      $extractComparator A callback to extract comparator from Element
     * @param callable      $createElement     A callback to create new element for Collection
     *
     * @return Collection
     */
    public function updateCollection(
        Collection $collection,
        array $input,
        callable $extractComparator,
        callable $createElement
    ): Collection {
        $existingElements = [];

        foreach ($collection as $element) {
            $key = $extractComparator($element);

            $existingElements[$key] = $element;
        }

        foreach ($input as $value) {
            if (true !== isset($existingElements[$value])) {
                $collection->add($createElement($value));
            }

            unset($existingElements[$value]);
        }

        foreach ($existingElements as $key => $element) {
            $collection->removeElement($element);
        }

        return $collection;
    }
}