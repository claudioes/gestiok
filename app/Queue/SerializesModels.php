<?php

namespace App\Queue;

use ReflectionClass;
use ReflectionProperty;
use Illuminate\Contracts\Queue\QueueableEntity;
use Illuminate\Contracts\Database\ModelIdentifier;
use Illuminate\Contracts\Queue\QueueableCollection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

trait SerializesModels
{
    /**
     * Prepare the instance for serialization.
     *
     * @return array
     */
    public function __sleep()
    {
        $properties = (new ReflectionClass($this))->getProperties();

        foreach ($properties as $property) {
            $property->setValue($this, $this->getSerializedPropertyValue(
                $this->getPropertyValue($property)
            ));
        }

        return array_values(array_filter(array_map(function ($p) {
            return $p->isStatic() ? null : $p->getName();
        }, $properties)));
    }

    /**
     * Restore the model after serialization.
     *
     * @return void
     */
    public function __wakeup()
    {
        foreach ((new ReflectionClass($this))->getProperties() as $property) {
            if ($property->isStatic()) {
                continue;
            }

            $property->setValue($this, $this->getRestoredPropertyValue(
                $this->getPropertyValue($property)
            ));
        }
    }

    /**
     * Get the property value for the given property.
     *
     * @param  \ReflectionProperty  $property
     * @return mixed
     */
    protected function getPropertyValue(ReflectionProperty $property)
    {
        $property->setAccessible(true);

        return $property->getValue($this);
    }

    /**
     * Get the property value prepared for serialization.
     *
     * @param  mixed  $value
     * @return mixed
     */
    protected function getSerializedPropertyValue($value)
    {
        if ($value instanceof QueueableCollection) {
            return new ModelIdentifier(
                $value->getQueueableClass(),
                $value->getQueueableIds(),
                $value->getQueueableRelations(),
                $value->getQueueableConnection()
            );
        }

        if ($value instanceof QueueableEntity) {
            return new ModelIdentifier(
                get_class($value),
                $value->getQueueableId(),
                $value->getQueueableRelations(),
                $value->getQueueableConnection()
            );
        }

        return $value;
    }

    /**
     * Get the restored property value after deserialization.
     *
     * @param  mixed  $value
     * @return mixed
     */
    protected function getRestoredPropertyValue($value)
    {
        if (! $value instanceof ModelIdentifier) {
            return $value;
        }

        return is_array($value->id)
                ? $this->restoreCollection($value)
                : $this->restoreModel($value);
    }

    /**
     * Restore a queueable collection instance.
     *
     * @param  \Illuminate\Contracts\Database\ModelIdentifier  $value
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function restoreCollection($value)
    {
        if (! $value->class || count($value->id) === 0) {
            return new EloquentCollection;
        }

        return $this->getQueryForModelRestoration(
            (new $value->class)->setConnection($value->connection), $value->id
        )->useWritePdo()->get();
    }

    /**
     * Restore the model from the model identifier instance.
     *
     * @param  \Illuminate\Contracts\Database\ModelIdentifier  $value
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function restoreModel($value)
    {
        return $this->getQueryForModelRestoration(
            (new $value->class)->setConnection($value->connection), $value->id
        )->useWritePdo()->firstOrFail()->load($value->relations ?? []);
    }

    /**
     * Get the query for model restoration.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  array|int  $ids
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function getQueryForModelRestoration($model, $ids)
    {
        return $model->newQueryForRestoration($ids);
    }
}
