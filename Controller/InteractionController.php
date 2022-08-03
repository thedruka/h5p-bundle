<?php

namespace Emmedy\H5PBundle\Controller;

use Emmedy\H5PBundle\Entity\Content;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/h5p/interaction")
 */
class InteractionController extends Controller
{
    /**
     * Access callback for the setFinished feature
     *
     * @Route("/set-finished/{token}")
     */
    public function setFinished(Request $request, $token)
    {
        return new JsonResponse();
    }

    /**
     * Handles insert, updating and deleting content user data through AJAX.
     *
     * @Route("/content-user-data/{contentId}/{dataType}/{subContentId}")
     */
    public function contentUserData(Request $request, $contentId, $dataType, $subContentId)
    {
        return new JsonResponse();
    }

    /**
     * @Route("/embed/{content}")
     */
    public function embedAction(Request $request, Content $content)
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
            $h5pIntegration['contents'][$contentIdStr]['displayOptions']['copyright'] = false;
            $h5pIntegration['contents'][$contentIdStr]['displayOptions']['copy'] = false;
            $h5pIntegration['contents'][$contentIdStr]['displayOptions']['icon'] = false;
        }

        $vars = [
            'contentId' => $content->getId(),
            'isFrame' => $content->getLibrary()->isFrame(),
            'h5pIntegration' => $h5pIntegration,
            'coreAssets' => $coreAssets,
            'files' => $files,
        ];
        return $this->render('@EmmedyH5P/embed.html.twig', $vars);
    }
}
