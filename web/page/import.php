    
        <!-- Some libaries needed for easier programming -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" crossorigin="anonymous" style=""></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/i18next/20.1.0/i18next.js"></script>

        <!-- Extra functionality per page -->
        <!--<script src="src/template.js"></script>-->
        <script src="/src/<?php echo $id; ?>.js"></script>

        <!-- Accessing the database -->
        <script src="/src/database.js"></script>
        
        <!-- Some basic functions we want everywhere -->
        <script src="/src/base.js"></script>
        <script src="/src/session.js"></script>