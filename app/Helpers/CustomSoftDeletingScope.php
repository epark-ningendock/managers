<?php
/**
 * Created by PhpStorm.
 * User: thanhtetaung
 * Date: 2019-04-20
 * Time: 12:02
 */

namespace App\Helpers;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Enums\Status;

class CustomSoftDeletingScope extends SoftDeletingScope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $builder->whereNull($model->getQualifiedDeletedAtColumn())
            ->where($model->getTable().'.status', '<>',Status::Deleted);
    }

    /**
     * Add the restore extension to the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */
    protected function addRestore(Builder $builder)
    {
        $builder->macro('restore', function (Builder $builder) {
            $builder->withTrashed();

            return $builder->update([
                'status' => Status::Valid,
                $builder->getModel()->getDeletedAtColumn() => null
            ]);
        });
    }

    /**
     * Add the without-trashed extension to the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */
    protected function addWithoutTrashed(Builder $builder)
    {
        $builder->macro('withoutTrashed', function (Builder $builder) {
            $model = $builder->getModel();

            $builder->withoutGlobalScope($this)->whereNull(
                $model->getQualifiedDeletedAtColumn()
            )->where($model->getTable().'.status', '<>',Status::Deleted);

            return $builder;
        });
    }

    /**
     * Add the only-trashed extension to the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */
    protected function addOnlyTrashed(Builder $builder)
    {
        $builder->macro('onlyTrashed', function (Builder $builder) {
            $model = $builder->getModel();

            $builder->withoutGlobalScope($this)->whereNotNull(
                $model->getQualifiedDeletedAtColumn()
            )->where($model->getTable().'.status', Status::Deleted);

            return $builder;
        });
    }
}