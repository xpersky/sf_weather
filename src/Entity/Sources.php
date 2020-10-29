<?php

namespace App\Entity;

use App\Repository\SourcesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SourcesRepository::class)
 */
class Sources
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    private $apikey;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $field;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $options;

    
    /**
     * @ORM\Column(type="integer", length=1, nullable=true)
     */
    private $iscord;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getApikey(): ?string
    {
        return $this->apikey;
    }

    public function setApikey(string $apikey): self
    {
        $this->apikey = $apikey;

        return $this;
    }

    public function getField(): ?string
    {
        return $this->field;
    }

    public function setField(string $field): self
    {
        $this->field = $field;

        return $this;
    }

    public function getOptions(): ?string
    {
        return $this->options;
    }

    public function setOptions($options): self
    {
        $this->options = $options;

        return $this;
    }

    public function getIscord(): ?int
    {
        return $this->iscord;
    }

    public function setIscord($iscord): self
    {
        $this->iscord = $iscord;

        return $this;
    }
}
