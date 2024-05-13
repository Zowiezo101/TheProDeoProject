<?php
    require "src/modules/Blogs/Blog.php";

    class BlogList extends Module {

        public function getContent() {
            global $TYPE_BLOG;
            $content = "";

            // Get all the blogs
            $data = getItems($TYPE_BLOG);
            if ($this->checkData($data) === false) {
                // Something went wrong
                $content = $this->getError();
            } else {
                $blogs = [];
                // Insert all the blogs into a Blog Module
                foreach($data->records as $idx => $record) {
                    $blog = new Blog($record);
                    $blog->setColor($idx % 5);
                    
                    // Add all the blogs into an array
                    array_push($blogs, $blog->getContent());
                }
                
                // Put it all together
                $content = implode("", $blogs);
            }

            return $content;
        }
    }

