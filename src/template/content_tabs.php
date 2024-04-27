<?php require "src/pages/{$page_id}_static.php"; // TODO: Use these brackets everywhere ?>
            <!-- This is for content that remains the same while using this page -->
            <div class="container-fluid">
                <div class="row">
                    <!-- The column with the tabs -->
                    <div class="col-3">
                        <ul class="nav nav-pills flex-column">
                            <?= insertTabList(); ?>
                        </ul>
                    </div>
        
                    <!-- The column with the tab contents -->
                    <div class="col-9">
                        <div class="tab-content">
                            <?= insertTabContent(); ?>
                        </div>
                    </div>
                </div>
            </div>

