<?php

namespace Ae\FeatureBundle\Service;

use Ae\FeatureBundle\Entity\Feature as FeatureEntity;
use Ae\FeatureBundle\Entity\FeatureManager;
use Ae\FeatureBundle\Security\FeatureSecurity;
use Exception;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author Carlo Forghieri <carlo@adespresso.com>
 */
class Feature implements FeatureInterface
{
    /**
     * @var FeatureManager
     */
    protected $manager;

    /**
     * @var FeatureSecurity
     */
    protected $security;

    /**
     * @param FeatureManager  $manager
     * @param FeatureSecurity $security
     */
    public function __construct(
        FeatureManager $manager,
        FeatureSecurity $security
    ) {
        $this->manager = $manager;
        $this->security = $security;
    }

    /**
     * Check if a feature (defined by name/parent) is granted to the logged user.
     *
     * @param string $name
     * @param string $parent
     *
     * @return bool
     */
    public function isGranted($name, $parent)
    {
        try {
            return $this->security->isGranted(
                $this->manager->find($name, $parent)
            );
        } catch (Exception $exception) {
            return false;
        }
    }

    public function isGrantedForUser(string $name, string $parent, UserInterface $user): bool
    {
        $feature = $this->manager->find($name, $parent);

        if (!$feature instanceof FeatureEntity) {
            return false;
        }

        return $this->security->isGrantedForUser($feature, $user);
    }
}
