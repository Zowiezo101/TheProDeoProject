<?php

    namespace AboutUsPage;
    
    use Shapes\Module;
    use Shapes\Text;

    class AboutUsPage extends Module {
        public function __construct() {
            global $dict;
            parent::__construct();
            
            $text = new Text('
            <div class="row text-center justify-content-center">
                <div class="col-md-10">
                    <h1 class="mb-3">'.$dict["navigation.about_us"].'</h1>
                    <p class="lead">'.$dict["about_us.overview"].'</p>
                    <h2>'.$dict["about_prodeo.title"].'</h2>
                    <p class="lead">'.$dict["about_prodeo.overview"].'</p>
                    <h2>'.$dict["other_projects.title"].'</h2>
                    <p class="lead">'.$dict["other_projects.overview"].'</p>
                </div>
            </div>');
            
            $this->addContent($text);
        }
    }
