<?php

namespace App\Repositories\Query;

class WhereRepository
{
    public function paramFilter($request, $query){

        // /** fungsi untuk filter data berdasarkan field yang sudah di tentukan pada postman */
        $params = collect(explode("|" ,$request['filter_param']));
        foreach ($params as $key => $filter) {
            $filterParam = explode("," ,$filter);
            if (count($filterParam) == 2 ) {
                if ($filterParam[1] != "") {  //bisa di string length
                    $query->where($filterParam[0], 'ilike', '%'.$filterParam[1].'%');
                }
            }
        }

        /** fungsi untuk order data desc / asc berdasarkan field yang sudah di tentukan pada postman */
        $orders = collect(explode("|" ,$request['order_param']));
        foreach ($orders as $key => $order) {
            $orderParam = explode("," ,$order);
            if (count($orderParam) == 2 ) {
                if ($orderParam[1] != "") {  //bisa di string length
                    $query->orderBy($orderParam[0], $orderParam[1]);
                }
            }
        }

        return $query;
    }

    public function filterJoinHierarchy($data, $request, $filteredData, $eloquentRelation = false)
    {
        // Definisikan filter secara dinamis
        $filters = $data;

        // Terapkan filter berdasarkan request
        if(($request->type == 'dropdown' || $request->type == 'filter' || $request->type == 'dropdown-waste')){
            foreach ($filters as $column => $filter) {
                $relation = $filter['relation'];
                $attribute = $filter['attribute'];
                $value = $request->{$column};

                if ($filter['is_filter'] && $request->column == $column && $request->has($column) && $request->{$column} !== "") { // query where like for column want to find
                
                    $filteredData = $filteredData->where($relation ? $relation.'.'.$attribute : $attribute, 'ilike', '%' . $value . '%');
                
                }else if($filter['is_filter'] && $request->has($column) && $request->{$column} != ""){ // query where null for column want to find
                
                    $filteredData = $filteredData->where($relation ? $relation.'.'.$attribute : $attribute, $value);
                
                }
            }
        }

        // Filter tambahan berdasarkan kolom request
        foreach ($filters as $column => $filter) {
            $value = $request->{$column};

            // Periksa apakah kolom ada dan memiliki value
            if (isset($value) && (isset($filter['is_filter']) ? $filter['is_filter'] : true) && (($request->type != 'dropdown' && $request->type != 'filter' && $request->type != 'dropdown-waste'))) {
                $relation = $filter['relation'];
                $attribute = $filter['attribute'];

                if ($eloquentRelation && !empty($relation)) {
                    $filteredData = $filteredData->whereHas($relation, function ($query) use ($attribute, $value) {
                        $query->where($attribute, $value);
                    });
                } else {
                    $filteredData = $filteredData->where($relation ? $relation.'.'.$attribute : $attribute, $value);
                }
            }
        }

        return $filteredData;

    }

    public function sortingColumn($allowedSorts, $request, $filteredData)
    {
        // Split the 'order' string from the request into parts
        $orderParts = explode('|', $request->order);
        $lastPart = end($orderParts); // Get the last part of the 'order' string
        @[$filterField, $operator] = explode(',', $lastPart); // Split the last part into field and operator

        if ($filterField && $operator && $operator != "null" && isset($allowedSorts[$filterField])) {
            $relation = $allowedSorts[$filterField]['relation'] ?? null; // Get the relation if it exists
            $attribute = $allowedSorts[$filterField]['attribute'] ?? null; // Get the attribute for sorting

            if ($relation === null){
                $filteredData->orderBy($attribute, $operator);
            } else if (str_contains($relation, '.')){
                // Split the relation into parts if it contains '.'
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
                $filteredData->whereHas($relation, function($sql) use($attribute, $operator){
                    $sql->orderBy($attribute, $operator);
                });
            }
        } else {
            // Apply default sorting if the conditions are not met
            $filteredData->orderBy('id', 'asc');
        }

        return $filteredData;
    }

}
