<?php namespace Pingpong\Modules\Publishing;

class AssetPublisher extends Publisher {

    /**
     * Get destination path.
     *
     * @return string
     */
    public function getDestinationPath()
    {
        return $this->repository->assetPath($this->module->getLowerName());
    }

    /**
     * Get source path.
     *
     * @return string
     */
    public function getSourcePath()
    {
        return $this->getModule()->getExtraPath(
            $this->repository->config('paths.generator.assets')
        );
    }

}