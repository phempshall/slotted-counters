<?php

/**
 * Implementation of the slotted counters pattern as described by Sam Lambert - https://planetscale.com/blog/the-slotted-counter-pattern
 * for Doctrine / Symfony by Paul Hempshall - https://www.paulhempshall.com
 */

namespace App\Entity;

use App\Repository\SlottedCountersRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SlottedCountersRepository::class)]
#[ORM\UniqueConstraint(
  name: 'records_and_slots',
  columns: ['record_type', 'record_id', 'slot']
)]
#[UniqueEntity(['record_type', 'record_id', 'slot'])]
class SlottedCounters
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 11)]
    private ?int $record_type = null;

    #[ORM\Column(length: 11)]
    private ?int $record_id = null;

    #[ORM\Column(length: 11)]
    private ?int $slot = null;

    #[ORM\Column(nullable: true)]
    private ?int $count = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRecordType(): ?int
    {
        return $this->record_type;
    }

    public function setRecordType(int $record_type): self
    {
        $this->record_type = $record_type;

        return $this;
    }

    public function getRecordId(): ?int
    {
        return $this->record_id;
    }

    public function setRecordId(int $record_id): self
    {
        $this->record_id = $record_id;

        return $this;
    }

    public function getSlot(): ?int
    {
        return $this->slot;
    }

    public function setSlot(int $slot): self
    {
        $this->slot = $slot;

        return $this;
    }

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function setCount(?int $count): self
    {
        $this->count = $count;

        return $this;
    }
}
