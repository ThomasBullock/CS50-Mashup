        <div class="footer">
            <a href="/">
                <span class="icon">
                    <i class="fas fa-2x fa-home"></i>
                </span>
            </a>
            <a href="/map">
                <span class="icon">
                    <i class="fa fa-2x fa-globe"></i>
                </span>
            </a>
            <a href="/about">
                <span class="icon">
                    <i class="fas fa-2x fa-question"></i>
                </span>
            </a>
            <a href="https://github.com/ThomasBullock/CS50-Mashup">
                <span class="icon">
                    <i class="fab fa-2x fa-github-square"></i>
                </span>
            </a>            
        </div>
        <script
            src="https://code.jquery.com/jquery-3.3.1.min.js"
            integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
            crossorigin="anonymous">
        </script>
        <?php if(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) == '/map') : ?> 
            <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBgmhtgIlAUNMa11QW0uPzFPnsI76OAv64">
            </script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/corejs-typeahead/1.2.1/typeahead.jquery.js"></script>
        <?php endif; ?>
        <script src="https://d3js.org/d3.v5.min.js"></script>
        <script src="https://d3js.org/topojson.v2.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.5/handlebars.min.js"></script>        
        <script src="http://localhost:7878/public/js/main-bundle.js"></script>
        <!-- <script src="http://mashup.tbullock.net/public/js/main-bundle.js"></script> -->
    </body>
</html>