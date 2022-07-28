<?php

namespace Emmedy\H5PBundle\Controller;


use Emmedy\H5PBundle\Editor\Utilities;
use Emmedy\H5PBundle\Entity\Content;
use Emmedy\H5PBundle\Form\Type\H5pType;
use Emmedy\H5PBundle\Core\H5PIntegration;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/h5p/")
 */
class H5PController extends Controller
{
    /**
     * @Route("list")
     */
    public function listAction()
    {
        $contents = $this->getDoctrine()->getRepository('EmmedyH5PBundle:Content')->findAll();
        return $this->render('@EmmedyH5P/list.html.twig', ['contents' => $contents]);
    }

    /**
     * @Route("show/{content}")
     */
    public function showAction(Content $content)
    {
        $h5pIntegration = $this->get('emmedy_h5p.integration')->getGenericH5PIntegrationSettings();
        $contentIdStr = 'cid-' . $content->getId();
        $h5pIntegration['contents'][$contentIdStr] = $this->get('emmedy_h5p.integration')->getH5PContentIntegrationSettings($content);

        $preloaded_dependencies = $this->get('emmedy_h5p.core')->loadContentDependencies($content->getId(), 'preloaded');

        $files = $this->get('emmedy_h5p.core')->getDependenciesFiles($preloaded_dependencies, $this->get('emmedy_h5p.options')->getRelativeH5PPath());

        if ($content->getLibrary()->isFrame()) {
            $jsFilePaths = array_map(function ($asset) {
                return $asset->path;
            }, $files['scripts']);
            $cssFilePaths = array_map(function ($asset) {
                return $asset->path;
            }, $files['styles']);
            $coreAssets = $this->get('emmedy_h5p.integration')->getCoreAssets();

            $h5pIntegration['core']['scripts'] = $coreAssets['scripts'];
            $h5pIntegration['core']['styles'] = $coreAssets['styles'];
            $h5pIntegration['contents'][$contentIdStr]['scripts'] = $jsFilePaths;
            $h5pIntegration['contents'][$contentIdStr]['styles'] = $cssFilePaths;
        }

/*!!!!
        $h5pIntegration['contents']['cid-11']['jsonContent'] = '{"media":{"type":{"params":{}},"disableImageZooming":false},"answers":[{"correct":true,"tipsAndFeedback":{"tip":"","chosenFeedback":"","notChosenFeedback":""},"text":"Answer 1<\/div>\n"},{"correct":false,"tipsAndFeedback":{"tip":"","chosenFeedback":"","notChosenFeedback":""},"text":"Answer 2<\/div>\n"}],"overallFeedback":[{"from":0,"to":100,"feedback":"good good"}],"behaviour":{"enableRetry":true,"enableSolutionsButton":true,"enableCheckButton":true,"type":"auto","singlePoint":false,"randomAnswers":true,"showSolutionsRequiresInput":true,"confirmCheckDialog":false,"confirmRetryDialog":false,"autoCheck":false,"passPercentage":100,"showScorePoints":true},"UI":{"checkAnswerButton":"Check","submitAnswerButton":"Submit","showSolutionButton":"Show solution","tryAgainButton":"Retry","tipsLabel":"Show tip","scoreBarLabel":"You got :num out of :total points","tipAvailable":"Tip available","feedbackAvailable":"Feedback available","readFeedback":"Read feedback","wrongAnswer":"Wrong answer","correctAnswer":"Correct answer","shouldCheck":"Should have been checked","shouldNotCheck":"Should not have been checked","noInput":"Please answer before viewing the solution","a11yCheck":"Check the answers. The responses will be marked as correct, incorrect, or unanswered.","a11yShowSolution":"Show the solution. The task will be marked with its correct solution.","a11yRetry":"Retry the task. Reset all responses and start the task over again."},"confirmCheck":{"header":"Finish ?","body":"Are you sure you wish to finish ?","cancelLabel":"Cancel","confirmLabel":"Finish"},"confirmRetry":{"header":"Retry ?","body":"Are you sure you wish to retry ?","cancelLabel":"Cancel","confirmLabel":"Confirm"},"question":"THE QUESTION<\/p>\n"}';
*/

        $h5pIntegration['contents'][$contentIdStr]['jsonContent'] = json_encode(json_decode($content->getParameters())->params);

        $vars = [
            'contentId' => $content->getId(),
            'isFrame' => $content->getLibrary()->isFrame(),
            'h5pIntegration' => $h5pIntegration,
            'files' => $files
        ];

    //    echo '<pre>';print_r($vars); die();

        return $this->render('@EmmedyH5P/show.html.twig', $vars);
    }

    /**
     * @Route("new")
     */
    public function newAction(Request $request)
    {
        return $this->handleRequest($request);
    }

    /**
     * @Route("update")
     */
    public function updateAction(Request $request)
    {
        $this->get('emmedy_h5p.core')->updateContentTypeCache();
        return $this->render('@EmmedyH5P/update.html.twig', []);
        //return $this->handleRequest($request);
    }

    /**
     * @Route("edit/{content}")
     */
    public function editAction(Request $request, Content $content)
    {
        return $this->handleRequest($request, $content);
    }

    private function handleRequest(Request $request, Content $content = null)
    {
        $formData = null;
        if ($content) {
            $formData['title'] = (string)$content->getTitle();
            $formData['parameters'] = $content->getParameters();
            $formData['library'] = (string)$content->getLibrary();
        }
        $form = $this->createForm(H5pType::class, $formData);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $data = $form->getData();
            $contentId = $this->get('emmedy_h5p.library_storage')->storeLibraryData($data['library'], $data['parameters'], $content);
            return $this->redirectToRoute('emmedy_h5p_h5p_show', ['content' => $contentId]);
        }
        $h5pIntegration = $this->get('emmedy_h5p.integration')->getEditorIntegrationSettings($content ? $content->getId() : null);

        return $this->render('@EmmedyH5P/edit.html.twig', ['form' => $form->createView(), 'h5pIntegration' => $h5pIntegration, 'h5pCoreTranslations' => $this->get('emmedy_h5p.integration')->getTranslationFilePath()]);
    }

    /**
     * @Route("delete/{contentId}")
     */
    public function deleteAction($contentId)
    {
        $this->get('emmedy_h5p.storage')->deletePackage([
            'id' => $contentId,
            'slug' => 'interactive-content'
        ]);

        return $this->redirectToRoute('emmedy_h5p_h5p_list');
    }
}
