<?php


namespace Emmedy\H5PBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="h5p_content_actions")
 */
class ContentAction
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @var Content
     *
     * @ORM\ManyToOne(targetEntity="Emmedy\H5PBundle\Entity\Content")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $content;
    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $user;
    /**
     * @var integer|null
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $time;
    /**
     * @var string
     *
     * @ORM\Column(name="data", type="text")
     */
    private $data;

    /**
     * ContentResult constructor.
     * @param User $user
     */
    public function __construct($user)
    {
        $this->user = $user;
    }
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @return Content|null
     */
    public function getContent()
    {
        return $this->content;
    }
    /**
     * @param Content|null $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }
    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
    /**
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }
    /**
     * @return object
     */
    public function getDataObject()
    {
        return json_decode($this->getData());
    }
    /**
     * @return string
     */
    public function getTimePretty()
    {
        return date('d.m.Y H:i', $this->getTime());
    }

    /**
     * @param string $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }
    /**
     * @return int|null
     */
    public function getTime()
    {
        return $this->time;
    }
    /**
     * @param int|null $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }
}
