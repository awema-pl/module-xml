<?php

namespace AwemaPL\Xml\User\Sections\Sources\Scopes;
use AwemaPL\Repository\Scopes\ScopeAbstract;

class SearchSource extends ScopeAbstract
{
    /**
     * Scope
     *
     * @param $builder
     * @param $value
     * @param $scope
     * @return mixed
     */
    public function scope($builder, $value, $scope)
    {
        if (!$value){
            return $builder;
        }

        return $builder->whereHas('sourceable', function($query) use (&$value){
            $query->where('name', 'like', '%'.$value.'%');
        });
    }
}
