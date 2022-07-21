<?php

namespace Emmedy\H5PBundle\Editor;


use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class EditorAjax implements \H5PEditorAjaxInterface
{
    /**
     * @var EntityManager
     */
    private $manager;
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * EditorAjax constructor.
     * @param EntityManager $manager
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(EntityManager $manager, TokenStorageInterface $tokenStorage)
    {
        $this->manager = $manager;
        $this->tokenStorage = $tokenStorage;
    }


    /**
     * Gets latest library versions that exists locally
     *
     * @return array Latest version of all local libraries
     */
    public function getLatestLibraryVersions()
    {
        return $this->manager->getRepository('EmmedyH5PBundle:Library')->findLatestLibraryVersions();
    }

    /**
     * Get locally stored Content Type Cache. If machine name is provided
     * it will only get the given content type from the cache
     *
     * @param $machineName
     *
     * @return array|object|null Returns results from querying the database
     */
    public function getContentTypeCache($machineName = NULL)
    {

        // Get only the specified content type from cache
        if ($machineName !== NULL) {
            $contentTypeCache = $this->manager->getRepository('EmmedyH5PBundle:LibrariesHubCache')->findOneBy(['machineName' => $machineName]);
            return [$contentTypeCache];
        }

        // Get all cached content types
        return $this->manager->getRepository('EmmedyH5PBundle:LibrariesHubCache')->findAll();
    }

    /**
     * Create a list of the recently used libraries
     *
     * @return array machine names. The first element in the array is the most
     * recently used.
     */
    public function getAuthorsRecentlyUsedLibraries()
    {
        $recentlyUsed = [];

        $user = $this->tokenStorage->getToken()->getUser();
        if (is_object($user)) {
            $events = $this->manager->getRepository('EmmedyH5PBundle:Event')->findRecentlyUsedLibraries($user->getId());
            foreach ($events as $event) {
                $recentlyUsed[] = $event['libraryName'];
            }
        }

        return $recentlyUsed;
    }

    /**
     * Checks if the provided token is valid for this endpoint
     *
     * @param string $token The token that will be validated for.
     *
     * @return bool True if successful validation
     */
    public function validateEditorToken($token)
    {
//    return \H5PCore::validToken('editorajax', $token);
        return true;
    }


    /**
     * Get translations for a language for a list of libraries
     *
     * @param array $libraries An array of libraries, in the form "<machineName> <majorVersion>.<minorVersion>
     * @param string $language_code
     * @return array
     */
    public function getTranslations($libraries, $language_code) {

        // TODO!

        /* WP plugin implementaation
        global $wpdb;

        $querylibs = '';
        foreach ($libraries as $lib) {
        $querylibs .= (empty($querylibs) ? '' : ',') . '%s';
        }

        array_unshift($libraries, $language_code);

        $result = $wpdb->get_results($wpdb->prepare(
        "SELECT hll.translation, CONCAT(hl.name, ' ', hl.major_version, '.', hl.minor_version) AS lib
        FROM {$wpdb->prefix}h5p_libraries hl
        JOIN {$wpdb->prefix}h5p_libraries_languages hll ON hll.library_id = hl.id
        WHERE hll.language_code = %s
        AND CONCAT(hl.name, ' ', hl.major_version, '.', hl.minor_version) IN ({$querylibs})",
        $libraries
        ));
        */
    }
}
