<?php

namespace Emmedy\H5PBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Emmedy\H5PBundle\Entity\ContentResult;
use Emmedy\H5PBundle\Entity\ContentAction;
use Symfony\Component\HttpFoundation\Request;

class ResultService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * ResultService constructor.
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param Request $request
     * @param $userId
     * @return ContentResult
     */
    public function handleRequestFinished(Request $request, $userId)
    {
        $contentId = $request->get('contentId', false);
        if (!$contentId) {
            \H5PCore::ajaxError('Invalid content');
            die();
        }
        // TODO: Fire 'h5p_alter_user_result' event here.
        $contentRepo = $this->em->getRepository('EmmedyH5PBundle:Content');
        $contentResultRepo = $this->em->getRepository('EmmedyH5PBundle:ContentResult');
        $result = $contentResultRepo->findOneBy(['userId' => $userId, 'content' => $contentId]);
        if (!$result) {
            $result = new ContentResult($userId);
            $result->setContent($contentRepo->find($contentId));
        }
        $result->setMaxScore($request->get('maxScore') ?? $result->getMaxScore());
        $result->setFinished($request->get('finished') ?? $result->getFinished());
        $result->setOpened($request->get('opened') ?? $result->getOpened());
        $result->setScore($request->get('score') ?? $result->getScore());
        $result->setTime($request->get('time') ?? $result->getTime());
        return $result;
    }

    /**
     * @param Request $request
     * @param $userId
     * @return ContentAction
     */
    public function handleRequestAction(Request $request, $userId)
    {
        $contentId = $request->get('contentId', false);
        if (!$contentId) {
            \H5PCore::ajaxError('Invalid content');
            die();
        }
        if (!$userId) {
            \H5PCore::ajaxError('Invalid user');
            die();
        }
        $user = $this->em->getRepository('AppBundle:User')->find($userId);
        $action = new ContentAction($user);
        $action->setContent($this->em->getRepository('EmmedyH5PBundle:Content')->find($contentId));
        $action->setData($request->get('data') ? $request->get('data') : '{}');
        $action->setTime($request->get('time') ? $request->get('time') : time());
        return $action;
    }

    /**
     * remove data in content User Data
     * @param integer $contentId
     * @param string $dataType
     * @param $user
     * @param integer $subContentId
     */
    public function removeData($contentId, $dataType, $user, $subContentId)
    {
        $ContentUserData = $this->em->getRepository('StuditH5PBundle:ContentUserData')
            ->findBy(
                [
                    'subContentId' => $subContentId,
                    'mainContent' => $contentId,
                    'dataId' => $dataType,
                    'user' => $user->getId()
                ]
            );
        if (count($ContentUserData) > 0){
            foreach ($ContentUserData as $content){
                $this->em->remove($content);
            }
            $this->em->flush();
        }
    }
}
