<?php

    namespace Classes;

    class Timeline extends Event {
        
        protected function getReadPageQuery() {
            // The translated table name
            $table = $this->getTable();

            // Query parameters
            $query_params = [
                ":page_start" => [self::PAGE_SIZE * $this->page, \PDO::PARAM_INT],
                ":page_size" => [self::PAGE_SIZE, \PDO::PARAM_INT]
            ];

            // Parts of the query
            $where_sql = $this->getWhereQuery($query_params);
            $sort_sql = $this->getSortQuery();

            // Query string (where parameters will be plugged in)
            $query_string = "
                SELECT * FROM (
                    SELECT * FROM (
                        SELECT -999 AS id, 
                        'timeline.global' as name) AS e1
                    UNION ALL
                    SELECT * FROM (
                        SELECT
                            i.id, i.name
                        FROM
                            {$table} i
                        {$where_sql}
                        ORDER BY
                            {$sort_sql} ) AS e2
                ) AS e
                LIMIT
                    :page_start, :page_size";

            $query = [
                "params" => $query_params,
                "string" => $query_string
            ];            
            return $query;
        }
        
        protected function getReadOneQuery() {
            $id = $this->getId();
        
            // There are two options here:
            // 1. The global timeline is selected, this consists out of all the 
            // events and shows an overview of the different events (id = -999)
            // 2. An event is selected, which consists out of activities and 
            // shows a more detailed timeline of this specific event (id != -999)
            if ($id === -999) {
                $this->setLinks([
                    Link::TIMELINE_TO_NOTES,
                    Link::TIMELINE_TO_AKA,
                ]);       
                
                // The translated table name
                $table = $this->getTable();
                
                // The query
                $query_params = [];
                $query_string = "WITH RECURSIVE ancestors AS 
                    (
                        SELECT e.order_id, e.id, e.name, 
                            e.descr, e.date, e.length, 
                            e.book_start_id, e.book_start_chap, e.book_start_vers,
                            e.book_end_id, e.book_end_chap, e.book_end_vers, 
                            -999 AS parent_id, 1 AS level, 1 AS gen, 0 AS x, 0 AS y
                        FROM 
                            {$table} e
                        LEFT JOIN
                            event_to_parent e2e
                                ON e.id = e2e.event_id
                        WHERE
                            e2e.parent_id is null

                        UNION DISTINCT

                        SELECT e.order_id, e.id, e.name, 
                            e.descr, e.date, e.length, 
                            e.book_start_id, e.book_start_chap, e.book_start_vers,
                            e.book_end_id, e.book_end_chap, e.book_end_vers, 
                            e2e.parent_id, 1 as level, gen+1, 0 AS x, 0 AS y
                        FROM 
                            {$table} e
                        LEFT JOIN
                            event_to_parent e2e
                                ON e.id = e2e.event_id
                        JOIN
                            ancestors a
                                ON a.id = e2e.parent_id
                    )

                    SELECT -999 AS order_id, -999 AS id, 'global.timeline' AS name, 
                        '' AS descr, '' AS date, '' AS length, 
                        '' AS book_start_id, '' AS book_start_chap, '' AS book_start_vers,
                        '' AS book_end_id, '' AS book_end_chap, '' AS book_end_vers, 
                        -1 AS parent_id, 1 AS level, 0 AS gen, 0 AS x, 0 AS y

                    UNION ALL

                    SELECT distinct(order_id), id, name, 
                        descr, date, length, 
                        book_start_id, book_start_chap, book_start_vers,
                        book_end_id, book_end_chap, book_end_vers, 
                        parent_id, level, gen, x, y FROM ancestors
                    ORDER BY
                        gen ASC, parent_id ASC, order_id ASC";
            } else {
                $this->setLinks([
                    Link::ACTIVITIES_TO_NOTES,
                    Link::ACTIVITIES_TO_AKA,
                ]);
                
                // The translated activity table
                $activity = $this->getActivitiesItem();
                $table_activity = $activity->getTable();
                
                // The translated table name
                $table = $this->getTable();
                
                // Query parameters
                $query_params = [
                    ":event_id" => [$id, \PDO::PARAM_INT],
                    ":id" => [$id, \PDO::PARAM_INT],
                ];
                
                // The query
                $query_string = "WITH RECURSIVE ancestors AS 
                    (
                        SELECT a.order_id, a.id, a.name, 
                            a.descr, a.date, a.length, 
                            a.book_start_id, a.book_start_chap, a.book_start_vers,
                            a.book_end_id, a.book_end_chap, a.book_end_vers, 
                            -999 AS parent_id, a.level, 1 AS gen, 0 AS x, 0 AS y
                        FROM 
                            {$table_activity} a
                        LEFT JOIN
                            activity_to_parent a2a
                                ON a.id = a2a.activity_id
                        LEFT JOIN
                            activity_to_event a2e
                                ON a.id = a2e.activity_id
                        WHERE
                            a2e.event_id = :event_id AND
                            a2a.parent_id is null

                        UNION DISTINCT

                        SELECT a.order_id, a.id, a.name, 
                            a.descr, a.date, a.length, 
                            a.book_start_id, a.book_start_chap, a.book_start_vers,
                            a.book_end_id, a.book_end_chap, a.book_end_vers, 
                            a2a.parent_id, a.level, gen+1, 0 AS x, 0 AS y
                        FROM 
                            {$table_activity} a
                        LEFT JOIN
                            activity_to_parent a2a
                                ON a.id = a2a.activity_id
                        LEFT JOIN
                            activity_to_event a2e
                                ON a.id = a2e.activity_id
                        INNER JOIN
                            ancestors an
                                ON an.id = a2a.parent_id
                    )

                    SELECT -999 AS order_id, -999 AS id, e.name, 
                        e.descr, e.date, e.length, 
                        e.book_start_id, e.book_start_chap, e.book_start_vers,
                        e.book_end_id, e.book_end_chap, e.book_end_vers, 
                        -1 AS parent_id, 1 AS level, 0 AS gen, 0 AS x, 0 AS y 
                    FROM {$table} e
                        WHERE e.id = :id

                    UNION ALL

                    SELECT order_id, id, name, 
                        descr, date, length, 
                        book_start_id, book_start_chap, book_start_vers,
                        book_end_id, book_end_chap, book_end_vers, 
                        parent_id, level, gen, x, y FROM ancestors
                    ORDER BY
                        gen ASC, parent_id ASC, order_id ASC";
            }
            
            $query = [
                "params" => $query_params,
                "string" => $query_string
            ];            
            return $query;
        }
    }
