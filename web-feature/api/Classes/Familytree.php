<?php

    namespace Classes;
    
    class Familytree extends People {
        
        public function __construct() {
            parent::__construct();
            
            // Set other tables that we want to include in the result as well
            $this->setLinks([
                Link::FAMILYTREE_TO_NOTES
            ]);
        }
        
        protected function getWhereQuery() {
            $where_sql = " WHERE
                i.id NOT IN (
                    SELECT people_id FROM people_to_parent WHERE parent_id IS NOT NULL)
                AND i.id IN (
                    SELECT parent_id FROM people_to_parent WHERE parent_id IS NOT NULL)";
            
            return $where_sql;
        }
        
        protected function getReadOneQuery() {
            // The translated table name
            $table = $this->getTable();
            
            // query to read a familytree, starting from a single id
            // It uses a recursive function to keep finding children, until there
            // are no more children to be found
            $query_params = [":id" => [$this->parameters["id"], \PDO::PARAM_INT]];
            $query_string = "WITH RECURSIVE ancestors AS 
                    (
                    SELECT p.order_id, p.id, p.name, p.meaning_name, p.descr,
                        t.type_name AS gender, -1 as parent_id, aka.people_name AS aka,
                        1 AS level, 0 AS gen, 0 AS x, 0 AS y
                    FROM 
                        {$table} p
                    LEFT JOIN
                        (SELECT people_id, CONCAT('[', GROUP_CONCAT(
                            CASE
                                WHEN meaning_name IS NOT NULL AND meaning_name != ''
                                    THEN CONCAT('{\"name\": \"', people_name, '\", \"meaning_name\": \"', meaning_name, '\"}')
                                    ELSE CONCAT('{\"name\": \"', people_name, '\"}')
                                    END SEPARATOR ', '
                                ), ']') AS people_name FROM people_to_aka
                            GROUP BY people_id) AS aka
                                ON aka.people_id = p.id
                    LEFT JOIN
                        type_gender AS t
                            ON p.gender = t.type_id
                    WHERE
                        p.id = :id

                    UNION DISTINCT

                    SELECT p.order_id, p.id, p.name, p.meaning_name, p.descr,
                        t.type_name AS gender, p2p.parent_id, aka.people_name AS aka,
                        1 AS level, gen+1, 0 AS x, 0 AS y
                    FROM 
                        {$table} p
                    LEFT JOIN
                        people_to_parent p2p
                            ON p.id = p2p.people_id
                    JOIN
                        ancestors a
                            ON a.id = p2p.parent_id
                    LEFT JOIN
                        (SELECT people_id, CONCAT('[', GROUP_CONCAT(
                            CASE
                                WHEN meaning_name IS NOT NULL AND meaning_name != ''
                                    THEN CONCAT('{\"name\": \"', people_name, '\", \"meaning_name\": \"', meaning_name, '\"}')
                                    ELSE CONCAT('{\"name\": \"', people_name, '\"}')
                                    END SEPARATOR ', '
                                ), ']') AS people_name FROM people_to_aka
                            GROUP BY people_id) AS aka
                                ON aka.people_id = p.id
                    LEFT JOIN
                        type_gender AS t
                            ON p.gender = t.type_id
                )

                SELECT distinct(order_id), id, name, meaning_name, descr,
                    gender, parent_id, aka,
                    level, gen, x, y FROM ancestors
                ORDER BY
                    gen ASC, parent_id ASC, order_id ASC";
            
            $query = [
                "params" => $query_params,
                "string" => $query_string
            ];
            return $query;
        }
    }
