<?php

namespace App;

use Reshadman\OptimisticLocking\OptimisticLocking;
use Reshadman\OptimisticLocking\StaleModelLockingException;

class Holiday extends SoftDeleteModel
{
    use OptimisticLocking;

    protected $fillable = [ 'date', 'hospital_id', 'lock_version' ];

    protected $dates = [ 'date' ];

    /**
     * Perform the actual delete query on this model instance.
     *
     * @return mixed
     */
    protected function performDeleteOnModel()
    {
        if ($this->forceDeleting) {
            $this->exists = false;

            $versionColumn = static::lockVersionColumn();
            $currentVersion = $this->currentLockVersion();
            $effected = $this->newModelQuery()->where($this->getKeyName(), $this->getKey())->where($versionColumn, $currentVersion)->forceDelete();
            if ($effected === 0) {
                throw new StaleModelLockingException("Model has been changed during update.");
            }
        }

        return $this->runSoftDelete();
    }
}
