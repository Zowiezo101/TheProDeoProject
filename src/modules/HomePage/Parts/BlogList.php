<?php

    class BlogList extends Module {

        public function __construct() {
            global $TYPE_BLOG;
            parent::__construct();

            // Get all the blogs
            $data = getItems($TYPE_BLOG);
            if ($this->checkData($data) === false) {
                // Something went wrong
                $this->addContent($this->getError());
            } else {
                // Insert all the blogs into a Blog Module
                foreach($data->records as $idx => $record) {
                    // Add all the blogs to the bloglist
                    $blog = new BlogListItem($idx, $record);
                    $this->addContent($blog);
                }
            }
        }
    }

