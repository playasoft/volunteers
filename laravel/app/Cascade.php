<?php

namespace App;

use ReflectionClass;
use ReflectionMethod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;


trait Cascade
{
    /**
     * Structure:
     *
     * @return mixed
     */
    protected abstract static function cascadeUpdateRelationshipFields();

    /**
     * Structure:
     *
     * @return mixed
     */
    protected abstract static function cascadeDeleteRelationships();

    /**
     *
     */
    protected abstract static function relationships();

    /**
     * [bootCascade description]
     * @return [type] [description]
     */
    public static function bootCascade()
    {
        static::updating(function(Model $model) {
            $relationships = $model->relationships();
            $update_fields = $model->cascadeUpdateRelationshipFields();
            $updated_fields = $model->getDirty();
            foreach($update_fields as $update_field => $update_relationships)
            {
                if(!in_array($update_field, array_keys($updated_fields)))
                {
                    continue;
                }
                if($update_relationships === 'all')
                {
                    $update_relationships = $relationships;
                }
                foreach($update_relationships as $update_relationship)
                {
                    if(!in_array($update_relationship, $relationships))
                    {
                        throw new Exception($relationship.' relation not found!');
                    }

                    $dirty_field = $updated_fields[$update_field];
                    static::cascadeRelationship($model->{$update_relationship}, function(Model $model) use ($update_field, $dirty_field) {
                        $model->{$update_field} = $dirty_field;
                        $model->save();
                    });
                }
            }
        });

        static::deleting(function(Model $model) {
            $relationships = $model->relationships();
            $delete_relationships = $model->cascadeDeleteRelationships();
            if($delete_relationships === 'all')
            {
                $delete_relationships = $relationships;
            }
            foreach($delete_relationships as $delete_relationship)
            {
                if(!in_array($delete_relationship, $relationships))
                {
                    throw new Exception($relationship.' relation not found!');
                }

                static::cascadeRelationship($model->{$delete_relationship}, function(Model $model) {
                    $model->delete();
                });
            }
        });
    }

    /**
     * [cascadeRelationship description]
     * @param  [type] $relationship [description]
     * @param  [type] $cascadeFunc  [description]
     * @return [type]               [description]
     */
    protected static function cascadeRelationship($relation, $cascadeFunc)
    {
        if($relation === null)
        {
            return;
        }
        if($relation instanceof Model)
        {
            $cascadeFunc($relation);
        }
        else
        {
            foreach($relation as $related_model)
            {
                $cascadeFunc($related_model);
            }
        }
    }

    /**
     * [relationships description]
     * @param  Model  $model [description]
     * @return [type]        [description]
     */
    // public static function relationships(Model $model)
    // {
    //     $relationships = [];
    //
    //     $reflector = new ReflectionClass($model);
    //     $methods = $reflector->getMethods(ReflectionMethod::IS_PUBLIC);
    //     foreach($methods as $method)
    //     {
    //         // check if method isn't instantiated in child class
    //         if($method->class != get_class($model))
    //         {
    //             continue;
    //         }
    //         //check if method has no parameters
    //         if(!empty($method->getParameters()))
    //         {
    //             continue;
    //         }
    //         //check if method is this method
    //         if($method->getName() == __FUNCTION__)
    //         {
    //             continue;
    //         }
    //
    //         try
    //         {
    //             $return = $model->{$method->getShortName()}();
    //
    //             if($return instanceof Relation)
    //             {
    //                 $relationships[] = $method->getShortName();
    //             }
    //         }
    //         catch(ErrorException $e)
    //         {
    //         }
    //     }
    //
    //     return $relationships;
    // }
}
