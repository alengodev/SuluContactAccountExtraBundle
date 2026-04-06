<?php

declare(strict_types=1);

namespace Alengo\SuluContactAccountExtraBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Sulu\Bundle\ContactBundle\Entity\Contact as SuluContact;

#[ORM\Entity]
#[ORM\Table(name: 'co_contacts')]
class Contact extends SuluContact
{
    /**
     * @var array<string, mixed>
     */
    #[ORM\Column(name: 'additionalData', type: Types::JSON, options: ['default' => '{}'])]
    private array $additionalData = [];

    /**
     * @return array<string, mixed>
     */
    public function getAdditionalData(): array
    {
        return $this->additionalData ?? [];
    }

    /**
     * @param array<string, mixed> $additionalData
     */
    public function setAdditionalData(array $additionalData): void
    {
        $this->additionalData = $additionalData;
    }
}
