<?php

namespace Drush\Boot;

abstract class BaseBoot implements Boot
{
    protected $uri = false;
    protected $phase = false;

    public function __construct()
    {
        register_shutdown_function([$this, 'terminate']);
    }

    public function findUri($root, $uri)
    {
        return 'default';
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function setUri($uri)
    {
        $this->uri = $uri;
    }

    /**
     * @return int
     */
    public function getPhase()
    {
        return $this->phase;
    }

    /**
     * @param int $phase
     */
    public function setPhase($phase)
    {
        $this->phase = $phase;
    }

    public function validRoot($path)
    {
    }

    public function getVersion($root)
    {
    }

    public function commandDefaults()
    {
    }

    public function reportCommandError($command)
    {
        // No longer used.
    }

    public function bootstrapPhases()
    {
        return [
            DRUSH_BOOTSTRAP_DRUSH => 'bootstrapDrush',
        ];
    }

    public function bootstrapPhaseMap()
    {
        return [
            'none' => DRUSH_BOOTSTRAP_DRUSH,
            'drush' => DRUSH_BOOTSTRAP_DRUSH,
            'max' => DRUSH_BOOTSTRAP_MAX,
            'root' => DRUSH_BOOTSTRAP_DRUPAL_ROOT,
            'site' => DRUSH_BOOTSTRAP_DRUPAL_SITE,
            'configuration' => DRUSH_BOOTSTRAP_DRUPAL_CONFIGURATION,
            'database' => DRUSH_BOOTSTRAP_DRUPAL_DATABASE,
            'full' => DRUSH_BOOTSTRAP_DRUPAL_FULL
        ];
    }

    public function lookUpPhaseIndex($phase)
    {
        $phaseMap = $this->bootstrapPhaseMap();
        if (isset($phaseMap[$phase])) {
            return $phaseMap[$phase];
        }

        if ((substr($phase, 0, 16) != 'DRUSH_BOOTSTRAP_') || (!defined($phase))) {
            return;
        }
        return constant($phase);
    }

    public function bootstrapDrush()
    {
    }

    protected function hasRegisteredSymfonyCommand($application, $name)
    {
        try {
            $application->get($name);
            return true;
        } catch (\InvalidArgumentException $e) {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function terminate()
    {
    }
}
