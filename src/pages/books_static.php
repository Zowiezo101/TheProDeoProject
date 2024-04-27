<?php 
    // TODO: Enable all coding standard and try to adhere to them
    $type = "TYPE_BOOK";
    $page_base_url = "books/book";
    
    
    function insertContent($data_item) {
        global $dict;
        $content = "";
        
        if (isset($data_item->records) && isset($data_item->records[0]->id)) {
            $book = $data_item->records[0];
            
            $content = '
            <div class="row">
                <div class="col-lg-11 px-lg-5 px-md-3 text-center">
                    <h1 class="mb-3">'.$book->name.'</h1>
                    <p class="lead">'.$book->summary.'</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-11 px-lg-5 px-md-3 text-center">
                    <p class="lead font-weight-bold mt-4">'.$dict["items.details"].'</p>
                    <div class="table-responsive">
                        <table class="table table-striped table-borderless">
                            <tbody>
                                '.insertTable($book).'
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>';
        } else {
            $content = '
            <div class="row">
                <div class="col-12 text-center">
                    '.$dict["settings.database_err"].'
                </div>
            </div>';
        }
        
        return $content;
    }
    
    function insertTable($book) {
        global $dict;
        $rows = [];
        
        // Row for number of chapters
        $num_chapters_head = $dict["items.num_chapters"];
        $num_chapters_data = $book->num_chapters;
        array_push($rows, [$num_chapters_head, $num_chapters_data]);
        
        // Row for notes about this book
        $notes_head = $dict["items.notes"];
        $notes_data = getNotesString($book->notes);
        array_push($rows, [$notes_head, $notes_data]);
        
        $table = "";
        foreach($rows as $row) {
            // Take every row head and row data that we got and put them in 
            // a nice HTML format
            $head = $row[0];
            $data = $row[1];
            
            $table = $table.insertTableRow($head, $data);
        }
        
        return $table;
    }
?>
