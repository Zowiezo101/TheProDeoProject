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
        
        // readAll for worldmap is the only exception to all readAlls
        // It's the only version where links need to be inserted, this is
        // not the case for any other readAll
        public function readAll() {
            $this->action = self::ACTION_READ_ALL;
            
            // Succefully reading an item should return code '200'
            $this->action_success = Message::SUCCESS_READ;
            
            // Execute the action
            $this->executeAction();
            
            // Retrieve the data
            $data = $this->message->getData();
            
            // Insert the links
            $this->link->insertLinks($data);
            
            // Update the data
            $this->message->updateData($data);
        }
        
        protected function getReadAllQuery() {
            // The translated table name
            $table = $this->getTable();
            
            // Get all the locations with their details
            $query_params = [];
            $query_string = "SELECT l.order_id,
                    l.id, l.name, l.descr,
                    l.meaning_name, aka.location_name AS aka,
                    t.type_name as type, l.coordinates
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
                LEFT JOIN
                    type_location t
                        ON t.type_id = l.type
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
