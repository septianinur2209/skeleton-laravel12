<?php

namespace App\Repositories\v1\API\Query;

class WhereRepository
{
    // Apply filtering and ordering based on request parameters
    public function paramFilter($request, $query){

        // Filter data by specified fields (passed in filter_param)
        $params = collect(explode("|" ,$request['filter_param']));
        foreach ($params as $key => $filter) {
            $filterParam = explode("," ,$filter);
            if (count($filterParam) == 2 ) {
                if ($filterParam[1] != "") {  // Check if filter value is not empty
                    // Use ilike for case-insensitive partial match
                    $query->where($filterParam[0], 'ilike', '%'.$filterParam[1].'%');
                }
            }
        }

        // Order data by specified fields and directions (passed in order_param)
        $orders = collect(explode("|" ,$request['order_param']));
        foreach ($orders as $key => $order) {
            $orderParam = explode("," ,$order);
            if (count($orderParam) == 2 ) {
                if ($orderParam[1] != "") {  // Check if order direction is specified
                    $query->orderBy($orderParam[0], $orderParam[1]);
                }
            }
        }

        return $query;
    }

    // Filter data with support for Eloquent relations and dynamic filters
    public function filterJoinHierarchy($data, $request, $filteredData, $eloquentRelation = false)
    {
        // Define dynamic filters
        $filters = $data;

        // Apply filters for dropdown or filter types with 'ilike' or exact match
        if(($request->type == 'dropdown' || $request->type == 'filter' || $request->type == 'dropdown-waste')){
            foreach ($filters as $column => $filter) {
                $relation = $filter['relation'];
                $attribute = $filter['attribute'];
                $value = $request->{$column};

                // If filter is enabled and column matches request, apply where ilike for partial matching
                if ($filter['is_filter'] && $request->column == $column && $request->has($column) && $request->{$column} !== "") {
                    $filteredData = $filteredData->where($relation ? $relation.'.'.$attribute : $attribute, 'ilike', '%' . $value . '%');
                
                // Otherwise apply exact match where clause
                }else if($filter['is_filter'] && $request->has($column) && $request->{$column} != ""){
                    $filteredData = $filteredData->where($relation ? $relation.'.'.$attribute : $attribute, $value);
                }
            }
        }

        // Additional filtering for other request types
        foreach ($filters as $column => $filter) {
            $value = $request->{$column};

            // Check if value exists and filter enabled, and request type is not dropdown/filter
            if (isset($value) && (isset($filter['is_filter']) ? $filter['is_filter'] : true) && (($request->type != 'dropdown' && $request->type != 'filter' && $request->type != 'dropdown-waste'))) {
                $relation = $filter['relation'];
                $attribute = $filter['attribute'];

                // If relation is set and eloquentRelation enabled, apply whereHas query for relation
                if ($eloquentRelation && !empty($relation)) {
                    $filteredData = $filteredData->whereHas($relation, function ($query) use ($attribute, $value) {
                        $query->where($attribute, $value);
                    });
                } else {
                    // Otherwise apply direct where clause
                    $filteredData = $filteredData->where($relation ? $relation.'.'.$attribute : $attribute, $value);
                }
            }
        }

        return $filteredData;

    }

    // Sort the result set based on allowed sort fields and requested order
    public function sortingColumn($allowedSorts, $request, $filteredData)
    {
        // Split the order string by '|' to get last sorting instruction
        $orderParts = explode('|', $request->order);
        $lastPart = end($orderParts);
        @[$filterField, $operator] = explode(',', $lastPart); // Extract field and operator

        if ($filterField && $operator && $operator != "null" && isset($allowedSorts[$filterField])) {
            $relation = $allowedSorts[$filterField]['relation'] ?? null;
            $attribute = $allowedSorts[$filterField]['attribute'] ?? null;

            if ($relation === null){
                // Direct order by attribute if no relation
                $filteredData->orderBy($attribute, $operator);
            } else if (str_contains($relation, '.')){
                // Handle nested relations for ordering
                $relationParts = explode('.', $relation);
                $filteredData->whereHas($relationParts[0], function($sql) use($relationParts, $attribute, $operator){
                    if(isset($relationParts[1])){
                        $sql->whereHas($relationParts[1], function($subquery) use($attribute, $operator){
                            if(isset($relationParts[2])){
                                $subquery->whereHas($relationParts[2], function($subquery1) use($attribute, $operator){
                                    $subquery1->orderBy($attribute, $operator);
                                });
                            } else {
                                $subquery->orderBy($attribute, $operator);
                            }
                        });
                    }else{
                        $sql->orderBy($attribute, $operator);
                    }
                });
            } else {
                // Simple relation order by using whereHas
                $filteredData->whereHas($relation, function($sql) use($attribute, $operator){
                    $sql->orderBy($attribute, $operator);
                });
            }
        } else {
            // Default ordering by 'id' ascending if no valid sort requested
            $filteredData->orderBy('id', 'asc');
        }

        return $filteredData;
    }

}
