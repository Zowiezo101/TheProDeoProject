<?php

    namespace Classes;

    class People extends Item {
        
        public function __construct() {
            parent::__construct();
            
            $this->setTable("peoples", [
                "order_id",
                "id",
                "name",
                "descr",
                "meaning_name",
                "father_age",
                "mother_age",
                "age",
                "gender",
                "tribe",
                "profession",
                "nationality",
                "book_start_id",
                "book_start_chap",
                "book_start_vers",
                "book_end_id",
                "book_end_chap",
                "book_end_vers"
            ], "id");
            
            $this->setTableLang([
                "id",
                "people_id",
                "name",
                "descr",
                "meaning_name",
                "profession",
                "nationality",
                "lang"
            ], "people_id");
            
            // Set other tables that we want to include in the result as well
            $this->setLinks([
                Link::PEOPLES_TO_GENDER,
                Link::PEOPLES_TO_TRIBE,
                Link::PEOPLES_TO_PARENTS,
                Link::PEOPLES_TO_CHILDREN,
                Link::PEOPLES_TO_EVENTS,
                Link::PEOPLES_TO_LOCATIONS,
                Link::PEOPLES_TO_AKA,
                Link::PEOPLES_TO_NOTES
            ]);
            
            // The following options are returned for the item type People:
            // - Min/Max own age
            // - Min/Max parent age
            // - All genders
            // - All tribes
            $this->setOptions([
                Options::AGE_MIN_MAX,
                Options::PARENT_AGE_MIN_MAX,
                Options::GENDER_TYPES,
                Options::TRIBE_TYPES,
            ]);
        }
        
        protected function getReadMapsQuery() {
            // The translated table name
            $table = $this->getTable();
            
            // Query parameters
            $query_params = [
                ":people_id" => [$this->parameters["id"], \PDO::PARAM_INT],
                ":parent_id" => [$this->parameters["id"], \PDO::PARAM_INT]
            ];
            
            // Query string (where parameters will be plugged in)
            $query_string = "
                WITH RECURSIVE cte (p1, p2) AS 
                    (
                        SELECT people_id, parent_id FROM people_to_parent WHERE people_id = :people_id
                        UNION ALL
                        SELECT people_id, parent_id FROM people_to_parent JOIN cte ON people_id = p2
                    )

                SELECT DISTINCT id, name FROM (
                    SELECT id, name FROM {$table} p
                        LEFT JOIN people_to_parent p2p
                        ON p.id = p2p.people_id 
                        WHERE p.id IN (SELECT p2 FROM cte)
                        AND parent_id IS NULL
                    UNION ALL
                    SELECT id, name FROM {$table} p
                        LEFT JOIN people_to_parent p1
                        ON p.id = p1.parent_id 
                        LEFT JOIN people_to_parent p2
                        ON p.id = p2.people_id
                        WHERE p1.parent_id = :parent_id
                        AND p1.people_id IS NOT NULL
                        AND p2.parent_id IS NULL
                        )
                AS ancestor";
            
            $query = [
                "params" => $query_params,
                "string" => $query_string
            ];
            return $query;
        }
    }
