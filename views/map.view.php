<?php require('partials/head.php'); ?>
    <section class="section map">
        <div id="map-canvas"></div>

        <div class="box ui-panel" style="opacity: 0">
            <div class="panel">
                <div class="field">
                    <div class="control has-icons-left">
                        <input name="geo" class="input" type="text" placeholder="City, State, Postal Code" autocomplete="off">
                        <span class="icon is-small is-left">
                            <i class="fas fa-search"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <a class="button is-medium nav-home" href="/">
            <span class="icon">
                <i class="fas fa-home fa-lg"></i>
            </span>
        </a>
    </section>
<?php require('partials/footer.php'); ?>    
