<?php

    namespace Tabs;
    
    use Shapes\Module;
    use Parts\TabListItem;
    use Parts\TabContentItem;

    class Tab extends Module {
        private $tab_list_item;
        private $tab_content_item;
        
        public function createTabListItem($params) {
            $this->tab_list_item = new TabListItem($params);
            return $this->tab_list_item;
        }
        
        public function createTabContentItem($params) {
            $this->tab_content_item = new TabContentItem($params);
            return $this->tab_content_item;
        }
        
        public function getTabListItem() {
            return $this->tab_list_item;
        }
        
        public function getTabContentItem() {
            return $this->tab_content_item;
        }

        function getBlogList() {
            global $data;
    
            $data = getItems(TYPE_BLOG);

            // Collect the blogs if there are any
            $blogs = [];
            if (isset($data->records) && ($data->records !== [])) {
                $blogs = $data->records;
            }

            // The HTML to insert
            $options = "";

            // Insert the blogs
            foreach ($blogs as $blog) {

                // The date of the blog, saved in UTC timezone
                $timezone = new \DateTimeZone("UTC");

                // A datetime object
                $datetime = new \DateTime($blog->date, $timezone);

                // The date and time, formatted to a string
                $date = $datetime->format("d-m-Y H:i:s");

                $options = $options.'
                                            <option value="'.$blog->id.'">
                                                '.$date.' - '.$blog->title.'
                                            </option>';
            }

            return $options;
        }
    }
