<?php

    namespace Classes;
    
    class Worldmap extends Location {
        
        public function __construct() {
            parent::__construct();
            
            // Set other tables that we want to include in the result as well
            $this->setLinks([
                Link::LOCATIONS_TO_NOTES
            ]);
        }
        
        protected function getWhereQuery(&$query_params) {
            $where_sql = parent::getWhereQuery($query_params);
            $where_sql = $where_sql . ($where_sql ? " AND " : " WHERE ") . "
                coordinates IS NOT NULL AND
                coordinates <> ''";
            
            return $where_sql;
        }
        
        protected function getReadAllQuery() {
            // The translated table name
            $table = $this->getTable();
            
            // Get all the locations with their details
            $query_params = [];
            $query_string = "SELECT
                    l.id, l.name, l.descr,
                    l.meaning_name, IFNULL(aka.location_name, '') AS aka,
                    l.type, l.coordinates
                FROM
                    {$table} l
                LEFT JOIN
                    (SELECT location_id, CONCAT('[', GROUP_CONCAT(
                        CASE
                            WHEN meaning_name IS NOT NULL AND meaning_name != ''
                                THEN CONCAT('{\"name\": \"', location_name, '\", \"meaning_name\": \"', meaning_name, '\"}')
                                ELSE CONCAT('{\"name\": \"', location_name, '\"}')
                                END SEPARATOR ', '
                            ), ']') AS location_name FROM location_to_aka
                        GROUP BY location_id) AS aka
                            ON aka.location_id = l.id
                WHERE
                    coordinates IS NOT NULL AND
                    coordinates <> ''";
            
            $query = [
                "params" => $query_params,
                "string" => $query_string
            ];
            return $query;
        }
    }
