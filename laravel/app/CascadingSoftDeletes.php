<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

trait CascadingSoftDeletes
{
    protected static function bootCascadingSoftDeletes()
    {
        static::deleting(function ($model) {
            $relationships = $model->getNonNullRelationships();
            foreach($relationships as $relationship)
            {
                if($relationship instanceof Model)
                {
                    $relationship->delete();
                }
                else
                {
                    foreach($relationship as $member)
                    {
                        $member->delete();
                    }
                }
            }
        });
    }

    protected function getNonNullRelationships()
    {
        $relationships = [];
        $relationship_names = $this->cascading_deletes ?? [];
        foreach($relationship_names as $relationship_name)
        {
            if($this->{$relationship_name} === null)
            {
                continue;
            }
            $relationships[] = $this->{$relationship_name};
        }

        return $relationships;
    }
}
