<?php

namespace Emmedy\H5PBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="ContentRepository")
 * @ORM\Table(name="h5p_content")
 */
class Content
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Library
     *
     * @ORM\ManyToOne(targetEntity="\Emmedy\H5PBundle\Entity\Library")
     * @ORM\JoinColumn(name="library_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $library;

    /**
     * @var string
     *
     * @ORM\Column(name="parameters", type="text", nullable=true)
     */
    private $parameters;

    /**
     * @var string
     *
     * @ORM\Column(name="metadata", type="text", nullable=true)
     */
    private $metadata;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="text", nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="filtered_parameters", type="text", nullable=true)
     */
    private $filteredParameters;

    /**
     * @var int
     *
     * @ORM\Column(name="disabled_features", type="integer", nullable=true)
     */
    private $disabledFeatures;

    public function __clone()
    {
        $this->id = null;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getTitle();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return Library
     */
    public function getLibrary()
    {
        return $this->library;
    }

    /**
     * @param Library $library
     */
    public function setLibrary($library)
    {
        $this->library = $library;
    }

    /**
     * @return string
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param string $parameters
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @return string
     */
    public function getFilteredParameters()
    {
        return $this->filteredParameters;
    }

    /**
     * @param string $filteredParameters
     */
    public function setFilteredParameters($filteredParameters)
    {
        $this->filteredParameters = $filteredParameters;
    }

    /**
     * @return int
     */
    public function getDisabledFeatures()
    {
        return $this->disabledFeatures;
    }

    /**
     * @param int $disabledFeatures
     */
    public function setDisabledFeatures($disabledFeatures)
    {
        $this->disabledFeatures = $disabledFeatures;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getMetadata()
    {
        return $this->metadata;
    }
    /**
     * @param string $metadata
     */
    public function setMetadata($metadata)
    {
        $this->metadata = $metadata;
    }
}
