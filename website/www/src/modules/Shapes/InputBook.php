<?php

    namespace Shapes;

    class InputBook extends Module {
        private $name;
        private $session_book_val;
        private $session_chap_val;
        
        public function __construct($name) {
            parent::__construct();
            
            // The name of this textbox
            $this->name = $name;
            
            // If there is a value stored in the session, get that value
            $this->session_book_val = isset($_SESSION["book_{$name}_id"]) ? htmlspecialchars($_SESSION["book_{$name}_id"]) : "-1";
            $this->session_chap_val = isset($_SESSION["book_{$name}_chap"]) ? htmlspecialchars($_SESSION["book_{$name}_chap"]) : "-1";
        }
        
        public function getContent() {
            global $dict;

            // Get the books that can be selected
            $books = $this->getData(TYPE_BOOK);

            $content = "";
            if ($books === null) {
                // Something went wrong
                $content = $this->getError();
            } else {
                $book_list = [];
                foreach($books->records as $book) {
                    $book_list[] = "
                            <option value='{$book->id}' data-num-chapters='{$book->num_chapters}'>
                                {$book->name}
                            </option>";
                }
            
                $content = "
                <!-- ".ucfirst($this->name)." appearance -->    
                <div class='row pb-2'>
                    <div class='col-md-12'>
                        <label class='font-weight-bold float-left'>
                            {$dict["items.book_{$this->name}"]}
                        </label>
                        <button id='book_{$this->name}_clear' type='button' class='close float-left text-secondary d-none' onclick='onBookReset(\"book_{$this->name}_id\")'>
                            <span aria-hidden='true'> &times; </span>
                        </button>
                    </div>
        
                    <div class='col-md-6'>
                        <select id='book_{$this->name}_id'
                                class='custom-select search-field'
                                onchange='onBookChange(\"book_{$this->name}_id\")'
                                data-type='book'
                                data-val='{$this->session_book_val}'>
                            <option selected disabled value='-1'>{$dict["books.book"]}</option>
                            ".implode("", $book_list)."
                        </select>
                    </div>
    
                    <div class='col-md-6'>
                        <select id='book_{$this->name}_chap'
                                class='custom-select search-field'
                                data-type='book'
                                data-val='{$this->session_chap_val}'>
                            <option selected disabled value='-1'>{$dict["books.chapter"]}</option>
                            <!-- Filled in later -->
                        </select>
                    </div>
                </div>";
            }
            
            return $content;
        }
    }
